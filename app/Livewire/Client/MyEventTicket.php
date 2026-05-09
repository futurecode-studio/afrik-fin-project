<?php

namespace App\Livewire\Client;

use App\Models\EventRegistration;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyEventTicket extends Component
{
    public EventRegistration $registration;

    public function mount($id)
    {
        $this->registration = EventRegistration::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['event', 'ticketType'])
            ->firstOrFail();
    }

    public function downloadTicket(EventRegistrationService $service)
    {
        $pdf = $service->generateTicketPdf($this->registration);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'ticket-' . $this->registration->event->slug . '.pdf');
    }

    public function render()
    {
        return view('livewire.client.my-event-ticket')
            ->extends('layouts.client', ['title' => 'Mon Ticket'])
            ->section('content');
    }
}
