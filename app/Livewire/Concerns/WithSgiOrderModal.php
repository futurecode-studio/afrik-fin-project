<?php

namespace App\Livewire\Concerns;

use App\Models\SgiAccountRequest;
use App\Models\SgiRequiredDocument;
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

    public function openOrderModal(): void
    {
        $this->resetErrorBag();
        $this->modalScreen = 'choice';
        $this->sgi_account_number = '';
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

        SgiAccountRequest::create([
            'user_id' => Auth::id(),
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'source' => $this->sgiAccountRequestSource(),
            'status' => 'pending',
        ]);

        $this->modalScreen = 'create_step2';
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
                'email' => 'Connectez-vous pour continuer.',
            ]);
        }

        $phone = (string) ($user->phone ?? '');
        if (strlen($phone) < 8) {
            throw ValidationException::withMessages([
                'phone' => 'Complétez votre téléphone dans votre profil avant de continuer.',
            ]);
        }

        return [$user->name, $user->email, $phone];
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
