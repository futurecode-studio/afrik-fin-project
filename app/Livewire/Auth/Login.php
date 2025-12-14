<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();

        // Rediriger selon le rôle
        if ($user->isAdmin()) {
            $this->redirect(route('admin.dashboard'), navigate: true);
        } else {
            $this->redirect(route('client.dashboard'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->extends('layouts.site', ['title' => 'Connexion'])
            ->section('content');
    }
}
