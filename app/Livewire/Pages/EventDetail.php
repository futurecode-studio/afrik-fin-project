<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Event;
use App\Services\EventCommunicationService;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventDetail extends Component
{
    use WithSweetAlert;

    public Event $event;

    public $showRegistrationModal = false;

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

    public function mount($slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->with([
                'programItems' => fn ($q) => $q->orderBy('display_order')->orderBy('starts_at'),
                'documents' => fn ($q) => $q->where('is_downloadable', true)->orderBy('display_order'),
                'sponsors', 'galleries',
            ])
            ->firstOrFail();

        if (Auth::check()) {
            $parts = preg_split('/\s+/', trim(Auth::user()->name ?? ''), 2);
            $this->first_name = $parts[0] ?? Auth::user()->name;
            $this->last_name = $parts[1] ?? '';
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone ?? '';
        }
    }

    public function openRegistrationModal(): void
    {
        if (! $this->event->isRegistrationOpen()) {
            $this->swalError('Les inscriptions sont fermées pour cet événement.');

            return;
        }

        $this->resetValidation();
        $this->showRegistrationModal = true;
    }

    public function closeRegistrationModal(): void
    {
        $this->showRegistrationModal = false;
        $this->resetValidation();
    }

    public function submitRegistration(EventRegistrationService $service, EventCommunicationService $comms): void
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
        ]);

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
            $registration = $service->register(Auth::user(), $this->event, $data);
            $comms->sendRegistrationConfirmed($registration);
            $this->showRegistrationModal = false;
            $this->redirect(route('event.ticket.public', $registration->qr_code), navigate: true);
        } catch (\Exception $e) {
            $this->swalError($e->getMessage());
        }
    }

    public function getIsUserRegisteredProperty(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->event->registrations()
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->exists();
    }

    public function render()
    {
        return view('livewire.pages.event-detail', [
            'isRegistered' => $this->getIsUserRegisteredProperty(),
            'publicUrl' => $this->event->publicUrl(),
        ])->extends('layouts.site', ['title' => $this->event->title])->section('content');
    }
}
