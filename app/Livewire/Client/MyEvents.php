<?php

namespace App\Livewire\Client;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyEvents extends Component
{
    use WithSweetAlert;

    public $registrations;

    public function mount()
    {
        $this->loadRegistrations();
    }

    public function loadRegistrations()
    {
        $this->registrations = EventRegistration::where('user_id', Auth::id())
            ->with(['event', 'checkIn'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function downloadTicket($registrationId, EventRegistrationService $service)
    {
        $registration = EventRegistration::where('id', $registrationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pdf = $service->generateTicketPdf($registration);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'ticket-'.$registration->event->slug.'.pdf');
    }

    public function cancelRegistration($registrationId, EventRegistrationService $service)
    {
        $registration = EventRegistration::where('id', $registrationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $service->cancel($registration, 'Annulation par le participant.');
            $this->swalSuccess('Inscription annulée avec succès.');
            $this->loadRegistrations();
        } catch (\Exception $e) {
            $this->swalError($e->getMessage());
        }
    }

    public function render()
    {
        $upcomingWebinars = Event::query()
            ->whereIn('status', ['published', 'ongoing'])
            ->where('starts_at', '>=', now())
            ->where(function ($query) {
                $query->whereIn('event_type', ['online', 'hybrid'])
                    ->orWhere('category', 'like', '%web%')
                    ->orWhere('title', 'like', '%web%');
            })
            ->orderBy('starts_at')
            ->take(6)
            ->get();

        return view('livewire.client.my-events', compact('upcomingWebinars'))
            ->extends('layouts.client', ['title' => 'Mes Webinaires'])
            ->section('content');
    }
}
