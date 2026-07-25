<?php

namespace App\Livewire\Pages;

use App\Models\NewsletterSubscriber;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class Newsletter extends Component
{
    use WithSweetAlert;
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $topics = [];
    public $consent = false;
    
    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:newsletter_subscribers,email',
        'consent' => 'accepted',
    ];

    protected $messages = [
        'first_name.required' => 'Le prénom est requis.',
        'last_name.required' => 'Le nom est requis.',
        'email.required' => 'L\'email est requis.',
        'email.email' => 'Veuillez entrer une adresse email valide.',
        'email.unique' => 'Cette adresse email est déjà abonnée à notre newsletter.',
        'consent.accepted' => 'Vous devez accepter de recevoir la newsletter.',
    ];

    public function subscribe()
    {
        $this->validate();
        
        try {
            // Enregistrer l'abonné avec son nom complet
            $fullName = trim($this->first_name . ' ' . $this->last_name);
            
            NewsletterSubscriber::create([
                'email' => $this->email,
                'name' => $fullName,
                'is_active' => true,
                'subscribed_at' => now(),
            ]);

            $this->swalSuccess('Merci pour votre inscription ! Vous recevrez notre prochaine newsletter.');
            
            // Réinitialiser le formulaire
            $this->reset(['first_name', 'last_name', 'email', 'topics', 'consent']);
        } catch (\Exception $e) {
            dd($e);
            $this->swalError('Une erreur est survenue. Veuillez réessayer.');
        }
    }

    public function render()
    {
        return view('livewire.pages.newsletter')
            ->extends('layouts.site', ['title' => 'Newsletter'])
            ->section('content');
    }
}
