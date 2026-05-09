<?php

namespace App\Livewire\Pages;

use App\Models\Event;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventDetail extends Component
{
    public Event $event;
    public $selectedTicketTypeId = null;
    public $showRegistrationModal = false;

    // Formulaire inscription
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $institution_name = '';
    public $job_title = '';
    public $t_shirt_size = '';
    public $medical_notes = '';
    public $emergency_contact_name = '';
    public $emergency_contact_phone = '';
    public $paymentProvider = 'kkiapay';

    public function mount($slug)
    {
        $this->event = Event::where('slug', $slug)
            ->with(['ticketTypes' => fn($q) => $q->where('is_active', true), 'programItems', 'speakers', 'sponsors', 'documents', 'galleries'])
            ->firstOrFail();

        if (Auth::check()) {
            $this->first_name = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone;
        }
    }

    public function selectTicket($ticketTypeId)
    {
        $this->selectedTicketTypeId = $ticketTypeId;
    }

    public function openRegistrationModal()
    {
        if (!Auth::check()) {
            session(['intended_event' => $this->event->slug]);
            return $this->redirect(route('connexion'), navigate: true);
        }

        if (!$this->event->isRegistrationOpen()) {
            session()->flash('error', 'Les inscriptions sont fermées pour cet événement.');
            return;
        }

        $this->showRegistrationModal = true;
    }

    public function closeRegistrationModal()
    {
        $this->showRegistrationModal = false;
        $this->resetValidation();
    }

    public function submitRegistration(EventRegistrationService $service)
    {
        if (!Auth::check()) {
            return;
        }

        $ticketType = $this->event->ticketTypes->firstWhere('id', $this->selectedTicketTypeId);

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'institution_name' => $this->institution_name,
            'job_title' => $this->job_title,
            't_shirt_size' => $this->t_shirt_size,
            'medical_notes' => $this->medical_notes,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'source' => 'web',
        ];

        try {
            $registration = $service->register(Auth::user(), $this->event, $data, $ticketType);

            if ($ticketType && $ticketType->price > 0) {
                // Redirection paiement
                $this->dispatch('initiatePayment', [
                    'provider' => $this->paymentProvider,
                    'amount' => (int) $ticketType->price,
                    'reference' => 'EVNTR-' . $registration->id . '-' . time(),
                    'email' => $this->email,
                    'name' => $this->first_name . ' ' . $this->last_name,
                    'phone' => $this->phone,
                    'registration_id' => $registration->id,
                ]);
            } else {
                session()->flash('success', 'Inscription confirmée ! Votre ticket est disponible dans votre espace.');
            }

            $this->showRegistrationModal = false;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function getIsUserRegisteredProperty(): bool
    {
        if (!Auth::check()) return false;
        return $this->event->registrations()
            ->where('user_id', Auth::id())
            ->whereNotIn('status', ['cancelled','no_show'])
            ->exists();
    }

    public function render()
    {
        return view('livewire.pages.event-detail', [
            'isRegistered' => $this->getIsUserRegisteredProperty(),
        ])->extends('layouts.site', ['title' => $this->event->title])->section('content');
    }
}
