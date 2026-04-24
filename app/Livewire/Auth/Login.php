<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $errorMessage = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->errorMessage = 'Email ou mot de passe incorrect';
            return;
        }

        Session::regenerate();

        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->redirect('/admin/dashboard');
        }
        
        return $this->redirect('/client/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->extends('layouts.site', ['title' => 'Connexion'])
            ->section('content');
    }
}