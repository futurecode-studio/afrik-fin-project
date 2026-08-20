<?php

namespace App\Livewire\Concerns;

use App\Models\SgiAccountRequest;
use App\Models\SgiRequiredDocument;
use App\Services\SgiAccountRequestCommunicationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Modal d’envoi d’ordres : compte SGI existant OU demande d’ouverture via ADF.
 */
trait WithSgiOrderModal
{
    public bool $showOrderModal = false;

    /** choice|has_account|create_step1|create_step2 */
    public string $modalScreen = 'choice';

    public string $sgi_account_number = '';

    /** Coordonnées saisies dans le modal (espace client / champs manquants). */
    public string $contact_name = '';

    public string $contact_email = '';

    public string $contact_phone = '';

    public function openOrderModal(): void
    {
        $this->resetErrorBag();
        $this->modalScreen = 'choice';
        $this->sgi_account_number = '';
        $this->hydrateContactFields();
        $this->showOrderModal = true;
    }

    public function closeOrderModal(): void
    {
        $this->showOrderModal = false;
        $this->modalScreen = 'choice';
        $this->sgi_account_number = '';
        $this->resetErrorBag();
    }

    public function selectHasAccount(): void
    {
        $this->resetErrorBag(['partner_id', 'sgi_account_number']);
        $this->modalScreen = 'has_account';
    }

    public function selectCreateAccount(): void
    {
        $this->resetErrorBag();
        $this->hydrateContactFields();
        $existing = $this->findOpenAccountRequest();
        $this->modalScreen = $existing ? 'create_step2' : 'create_step1';
    }

    public function backToChoice(): void
    {
        $this->resetErrorBag();
        $this->modalScreen = 'choice';
        $this->sgi_account_number = '';
    }

    public function confirmCreateAccount(): void
    {
        [$name, $email, $phone] = $this->resolveContactForAccountRequest();

        if ($this->findOpenAccountRequest()) {
            $this->modalScreen = 'create_step2';

            return;
        }

        $request = SgiAccountRequest::create([
            'user_id' => Auth::id(),
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'source' => $this->sgiAccountRequestSource(),
            'status' => 'pending',
        ]);

        app(SgiAccountRequestCommunicationService::class)->sendNewRequestNotifications($request);

        $this->modalScreen = 'create_step2';
    }

    /**
     * Champs manquants à afficher dans l’étape 1 (espace client authentifié).
     *
     * @return list<string>
     */
    public function missingContactFields(): array
    {
        if ($this->usesPublicContactFields()) {
            return [];
        }

        $missing = [];
        if (trim($this->contact_name) === '' || mb_strlen(trim($this->contact_name)) < 2) {
            $missing[] = 'name';
        }
        if (trim($this->contact_email) === '' || ! filter_var(trim($this->contact_email), FILTER_VALIDATE_EMAIL)) {
            $missing[] = 'email';
        }
        if (strlen(trim($this->contact_phone)) < 8) {
            $missing[] = 'phone';
        }

        return $missing;
    }

    protected function hydrateContactFields(): void
    {
        if ($this->usesPublicContactFields()) {
            $this->contact_name = property_exists($this, 'name') ? (string) $this->name : '';
            $this->contact_email = property_exists($this, 'email') ? (string) $this->email : '';
            $this->contact_phone = property_exists($this, 'phone') ? (string) $this->phone : '';

            return;
        }

        $user = Auth::user();
        if (! $user) {
            return;
        }

        $this->contact_name = (string) ($user->name ?? '');
        $this->contact_email = (string) ($user->email ?? '');
        $this->contact_phone = (string) ($user->phone ?? '');
    }

    /**
     * @return array{0: string, 1: string, 2: string}
     */
    protected function resolveContactForAccountRequest(): array
    {
        if ($this->usesPublicContactFields()) {
            $this->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|min:8|max:30',
            ]);

            return [$this->name, $this->email, $this->phone];
        }

        $user = Auth::user();
        if (! $user) {
            throw ValidationException::withMessages([
                'contact_email' => 'Connectez-vous pour continuer.',
            ]);
        }

        // Préremplir uniquement les champs encore vides (ne pas écraser la saisie du modal).
        if (trim($this->contact_name) === '') {
            $this->contact_name = (string) ($user->name ?? '');
        }
        if (trim($this->contact_email) === '') {
            $this->contact_email = (string) ($user->email ?? '');
        }
        if (trim($this->contact_phone) === '') {
            $this->contact_phone = (string) ($user->phone ?? '');
        }

        $this->validate([
            'contact_name' => 'required|string|min:2|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|min:8|max:30',
        ], [
            'contact_name.required' => 'Le nom est requis.',
            'contact_name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'contact_email.required' => 'L’email est requis.',
            'contact_email.email' => 'Adresse email invalide.',
            'contact_phone.required' => 'Le téléphone est requis.',
            'contact_phone.min' => 'Le numéro de téléphone semble trop court.',
        ]);

        $name = trim($this->contact_name);
        $email = trim($this->contact_email);
        $phone = trim($this->contact_phone);

        $updates = [];
        if ($user->name !== $name) {
            $updates['name'] = $name;
        }
        if ($user->email !== $email) {
            $updates['email'] = $email;
        }
        if ((string) ($user->phone ?? '') !== $phone) {
            $updates['phone'] = $phone;
        }
        if ($updates !== []) {
            $user->update($updates);
        }

        return [$name, $email, $phone];
    }

    /**
     * Carnet public : name/email/phone sur le composant. Espace client : profil Auth.
     */
    protected function usesPublicContactFields(): bool
    {
        return false;
    }

    protected function findOpenAccountRequest(): ?SgiAccountRequest
    {
        $query = SgiAccountRequest::query()
            ->whereIn('status', ['pending', 'contacted', 'in_progress']);

        if (Auth::check()) {
            $query->where(function ($q) {
                $q->where('user_id', Auth::id())
                    ->orWhere('email', Auth::user()->email);
            });
        } else {
            $email = $this->usesPublicContactFields() ? trim((string) $this->email) : '';
            if ($email === '') {
                return null;
            }
            $query->where('email', $email);
        }

        return $query->latest()->first();
    }

    protected function sgiRequiredDocuments(): Collection
    {
        return SgiRequiredDocument::active()->get();
    }

    /**
     * Source pour les demandes d’ouverture de compte.
     */
    abstract protected function sgiAccountRequestSource(): string;
}
