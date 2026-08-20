<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'client'; // Les inscriptions depuis le site sont des clients

        $user = User::create($validated);
        $user->assignRole('client');

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('client.dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->extends('layouts.site', ['title' => 'Inscription — Africaine des Finances'])
            ->section('content');
    }
}
