<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Newsletter extends Component
{
    public $email = '';
    public $consent = false;
    
    protected $rules = [
        'email' => 'required|email',
        'consent' => 'accepted',
    ];

    public function subscribe()
    {
        $this->validate();
        
        // Logique d'inscription à la newsletter
        session()->flash('message', 'Inscription réussie à la newsletter !');
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.pages.newsletter')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
