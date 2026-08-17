<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectToDestination(Auth::user());
        }
    }

    public function login(): void
    {
        $this->resetErrorBag();

        $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'L’email est obligatoire.',
            'email.email' => 'Entrez une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey(), 60);

            throw ValidationException::withMessages([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        session()->regenerate();

        $user = Auth::user();
        $user->forceFill(['last_login_at' => now()])->save();
        \App\Models\UserActivityLog::record(
            $user->id,
            \App\Models\UserActivityLog::LOGIN,
            'Connexion réussie'
        );

        $this->redirectToDestination($user);
    }

    private function redirectToDestination($user): void
    {
        if ($user->isAdmin() || $user->can('dashboard.view')) {
            $this->redirect(route('admin.dashboard'), navigate: false);

            return;
        }

        if ($user->isClient()) {
            $this->redirect(route('client.my-events'), navigate: false);

            return;
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        throw ValidationException::withMessages([
            'email' => 'Ce compte n’a pas de rôle valide. Contactez le support.',
        ]);
    }

    private function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->extends('layouts.site', ['title' => 'Connexion — Africaine des Finances'])
            ->section('content');
    }
}
