<?php

namespace App\Livewire\Pages;

use App\Models\EventRegistration;
use App\Services\EventRegistrationService;
use Livewire\Component;

class EventTicketPublic extends Component
{
    public EventRegistration $registration;

    public function mount($qrCode)
    {
        $this->registration = EventRegistration::where('qr_code', $qrCode)
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
        return view('livewire.pages.event-ticket-public')
            ->extends('layouts.site', ['title' => 'Mon Ticket — ' . $this->registration->event->title])
            ->section('content');
    }
}
