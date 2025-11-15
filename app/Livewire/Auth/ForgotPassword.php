<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';

    protected $rules = [
        'email' => ['required', 'string', 'email'],
    ];

    protected $messages = [
        'email.required' => 'L\'adresse email est obligatoire.',
        'email.email' => 'Veuillez entrer une adresse email valide.',
    ];

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink()
    {
        $this->validate();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');

        session()->flash('message', 'Un email de réinitialisation a été envoyé à votre adresse.');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->extends('layouts.site', ['title' => 'Mot de passe oublié'])
            ->section('content');
    }
}
