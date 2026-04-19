<?php

namespace App\Livewire\Concerns;

use App\Models\InvestmentAppointment;
use App\Mail\InvestmentAppointmentNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Trait mutualisé pour les pages Livewire d'investissement (Actions BRVM, Obligations, FCP).
 * Fournit :
 *  - les règles + messages de validation du formulaire de RDV
 *  - une méthode submitAppointment() sécurisée (rate-limit, queue, fallback admin email)
 *  - l'auto-remplissage nom/email depuis l'utilisateur authentifié
 */
trait HandlesInvestmentAppointment
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $company = '';
    public $message = '';

    /**
     * Règles de validation communes. Les composants qui les surchargent
     * doivent déclarer explicitement leur propre propriété $rules.
     */
    protected function appointmentRules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'regex:/^[+\d\s\-\(\)]{8,20}$/'],
            'company' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ];
    }

    protected function appointmentMessages(): array
    {
        return [
            'name.required' => 'Votre nom est requis.',
            'name.min' => 'Votre nom doit contenir au moins 2 caractères.',
            'email.required' => 'Votre email est requis.',
            'email.email' => "L'email fourni n'est pas valide.",
            'phone.required' => 'Votre numéro de téléphone est requis.',
            'phone.regex' => 'Format de téléphone invalide (ex : +229 01 23 45 67).',
        ];
    }

    /**
     * Pré-remplit les champs à partir de l'utilisateur connecté.
     */
    protected function prefillFromAuthUser(): void
    {
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }
    }

    /**
     * Crée un rendez-vous + envoie les notifications (queue) avec protection anti-spam.
     *
     * @param string $investmentType Clé logique pour InvestmentAppointment::investment_type
     * @return bool True si la demande a été enregistrée, false si bloquée par le rate-limit.
     */
    protected function submitAppointment(string $investmentType): bool
    {
        // Rate-limiting anti-spam : 3 tentatives / 10 min par user ou IP
        $throttleKey = "appointment:{$investmentType}:" . (Auth::id() ?: request()->ip());
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Trop de demandes. Réessayez dans {$seconds} secondes.");
            return false;
        }
        RateLimiter::hit($throttleKey, 600);

        $this->validate($this->appointmentRules(), $this->appointmentMessages());

        $appointment = InvestmentAppointment::create([
            'user_id' => Auth::id(),
            'investment_type' => $investmentType,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'message' => $this->message,
            'status' => 'pending',
        ]);

        $adminEmail = config('mail.from.address') ?: env('MAIL_ADMIN_ADDRESS');
        try {
            Mail::to($this->email)->queue(new InvestmentAppointmentNotification($appointment, false));
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new InvestmentAppointmentNotification($appointment, true));
            }
        } catch (\Throwable $e) {
            Log::error("Envoi email RDV ({$investmentType}) échoué", [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }

        session()->flash('success', 'Votre demande de rendez-vous a été envoyée avec succès ! Nous vous contacterons bientôt.');
        $this->reset(['company', 'message']);
        return true;
    }
}
