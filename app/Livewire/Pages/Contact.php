<?php

namespace App\Livewire\Pages;

use App\Models\Contact as ContactModel;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class Contact extends Component
{
    use WithSweetAlert;
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $subject = '';
    public $message = '';
    
    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10',
    ];

    protected $messages = [
        'first_name.required' => 'Le prénom est requis.',
        'last_name.required' => 'Le nom est requis.',
        'email.required' => 'L\'email est requis.',
        'email.email' => 'Veuillez entrer une adresse email valide.',
        'phone.required' => 'Le téléphone est requis.',
        'subject.required' => 'Le sujet est requis.',
        'message.required' => 'Le message est requis.',
        'message.min' => 'Le message doit contenir au moins 10 caractères.',
    ];

    public function submit()
    {
        $this->validate();
        
        try {
            // Enregistrer le message de contact dans la base de données
            ContactModel::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'subject' => $this->subject,
                'message' => $this->message,
                'status' => 'new',
            ]);

            $this->swalSuccess('Message envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
            
            // Réinitialiser le formulaire
            $this->reset(['first_name', 'last_name', 'email', 'phone', 'subject', 'message']);
        } catch (\Exception $e) {
            $this->swalError('Une erreur est survenue. Veuillez réessayer.');
        }
    }

    public function render()
    {
        return view('livewire.pages.contact')
            ->extends('layouts.site', ['title' => 'Contact'])
            ->section('content');
    }
}
