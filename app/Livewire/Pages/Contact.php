<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Contact extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10',
    ];

    public function submit()
    {
        $this->validate();
        
        // Logique d'envoi du message de contact
        session()->flash('message', 'Message envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
        
        // Réinitialiser le formulaire
        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.pages.contact')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
