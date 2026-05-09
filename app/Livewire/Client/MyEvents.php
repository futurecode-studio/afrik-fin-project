<?php

namespace App\Livewire\Client;

use App\Models\EventRegistration;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyEvents extends Component
{
    public $registrations;

    public function mount()
    {
        $this->loadRegistrations();
    }

    public function loadRegistrations()
    {
        $this->registrations = EventRegistration::where('user_id', Auth::id())
            ->with(['event', 'ticketType', 'checkIn'])
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
        }, 'ticket-' . $registration->event->slug . '.pdf');
    }

    public function cancelRegistration($registrationId, EventRegistrationService $service)
    {
        $registration = EventRegistration::where('id', $registrationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $service->cancel($registration, 'Annulation par le participant.');
            session()->flash('success', 'Inscription annulée avec succès.');
            $this->loadRegistrations();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.client.my-events')
            ->extends('layouts.client', ['title' => 'Mes Événements'])
            ->section('content');
    }
}
