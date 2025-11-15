<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Formations extends Component
{
    public $showModal = false;
    public $selectedFormation = null;
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    
    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
    ];

    protected $messages = [
        'first_name.required' => 'Le prénom est requis.',
        'last_name.required' => 'Le nom est requis.',
        'email.required' => 'L\'email est requis.',
        'email.email' => 'Veuillez entrer une adresse email valide.',
        'phone.required' => 'Le téléphone est requis.',
    ];

    public function openModal($formationId, $formationTitle)
    {
        $this->selectedFormation = [
            'id' => $formationId,
            'title' => $formationTitle
        ];
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['first_name', 'last_name', 'email', 'phone', 'selectedFormation']);
        $this->resetValidation();
    }

    public function submitInscription()
    {
        $this->validate();
        
        try {
            // Ici vous pouvez enregistrer l'inscription en base de données
            // Par exemple : Inscription::create([...])
            
            session()->flash('success', 'Votre demande d\'inscription a été envoyée avec succès ! Nous vous contactons sous peu.');
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    public function render()
    {
        return view('livewire.pages.formations')
            ->extends('layouts.site', ['title' => 'Formations'])
            ->section('content');
    }
}
