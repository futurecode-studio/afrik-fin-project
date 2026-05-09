<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventCheckInManager extends Component
{
    public Event $event;
    public $scanResult = null;
    public $lastCheckIn = null;
    public $totalCheckedIn = 0;
    public $soundEnabled = true;

    protected $listeners = ['qrDecoded' => 'handleQrDecoded'];

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->updateStats();
    }

    public function updateStats()
    {
        $this->totalCheckedIn = EventRegistration::where('event_id', $this->event->id)
            ->where('status', 'checked_in')
            ->count();
    }

    public function handleQrDecoded($qrCode, EventRegistrationService $service)
    {
        $this->scanResult = null;
        $this->lastCheckIn = null;

        $registration = $service->findByQr($qrCode);

        if (!$registration || $registration->event_id !== $this->event->id) {
            $this->scanResult = ['type' => 'error', 'message' => 'QR Code invalide ou non trouvé.'];
            return;
        }

        if ($registration->isCheckedIn()) {
            $this->scanResult = [
                'type' => 'warning',
                'message' => 'Déjà enregistré à ' . $registration->checked_in_at?->format('H:i'),
                'participant' => $registration->fullName(),
            ];
            $this->lastCheckIn = $registration;
            return;
        }

        try {
            $service->checkIn($registration, Auth::user(), 'qr_scan');
            $this->scanResult = [
                'type' => 'success',
                'message' => 'Bienvenue ' . $registration->fullName() . ' !',
                'participant' => $registration->fullName(),
                'institution' => $registration->institution_name,
                'ticket' => $registration->ticketType?->name,
            ];
            $this->lastCheckIn = $registration;
            $this->updateStats();
            $this->dispatch('checkin-success');
        } catch (\Exception $e) {
            $this->scanResult = ['type' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function manualCheckIn($registrationId, EventRegistrationService $service)
    {
        $registration = EventRegistration::find($registrationId);
        if (!$registration) return;

        $this->handleQrDecoded($registration->qr_code, $service);
    }

    public function render()
    {
        $recent = EventRegistration::where('event_id', $this->event->id)
            ->where('status', 'checked_in')
            ->with('checkIn')
            ->orderBy('checked_in_at', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.admin.event-checkin-manager', [
            'recent' => $recent,
        ])->extends('layouts.admin', ['title' => 'Émargement — ' . $this->event->title])->section('content');
    }
}
