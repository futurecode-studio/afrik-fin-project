<?php

namespace App\Livewire;

use App\Models\NewsletterSubscriber;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    public $email = '';
    public $successMessage = '';
    public $errorMessage = '';

    protected $rules = [
        'email' => 'required|email|unique:newsletter_subscribers,email',
    ];

    protected $messages = [
        'email.required' => 'Veuillez entrer votre adresse email.',
        'email.email' => 'Veuillez entrer une adresse email valide.',
        'email.unique' => 'Cette adresse email est déjà abonnée à notre newsletter.',
    ];

    public function subscribe()
    {
        $this->resetErrorBag();
        $this->successMessage = '';
        $this->errorMessage = '';

        $this->validate();

        try {
            NewsletterSubscriber::create([
                'email' => $this->email,
                'is_active' => true,
                'subscribed_at' => now(),
            ]);

            $this->successMessage = 'Merci pour votre abonnement ! Vous recevrez nos prochaines actualités.';
            $this->reset('email');
        } catch (\Exception $e) {
            $this->errorMessage = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }

    public function render()
    {
        return view('livewire.newsletter-subscribe');
    }
}
