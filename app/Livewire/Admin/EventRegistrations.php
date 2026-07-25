<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class EventRegistrations extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public Event $event;
    public $search = '';
    public $statusFilter = '';
    public $showCancelModal = false;
    public $registrationId = null;
    public $cancellationReason = '';

    protected $paginationTheme = 'tailwind';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmCancel($id)
    {
        $this->registrationId = $id;
        $this->showCancelModal = true;
    }

    public function cancelRegistration(EventRegistrationService $service)
    {
        $registration = EventRegistration::findOrFail($this->registrationId);
        $service->cancel($registration, $this->cancellationReason);
        $this->swalSuccess('Inscription annulée avec succès. La place a été libérée.');
        $this->showCancelModal = false;
        $this->registrationId = null;
        $this->cancellationReason = '';
        $this->dispatch('registration-updated');
    }

    public function checkInManual($id, EventRegistrationService $service)
    {
        $registration = EventRegistration::findOrFail($id);
        try {
            $service->checkIn($registration, Auth::user(), 'manual');
            $this->swalSuccess('Présence enregistrée pour ' . $registration->fullName() . '.');
        } catch (\Exception $e) {
            $this->swalError($e->getMessage());
        }
        $this->dispatch('registration-updated');
    }

    public function exportCsv()
    {
        $filename = 'inscriptions-' . $this->event->slug . '-' . date('Ymd') . '.csv';
        $registrations = EventRegistration::where('event_id', $this->event->id)
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Institution', 'T-shirt', 'Statut', 'QR', 'Date inscription']);
            foreach ($registrations as $r) {
                fputcsv($file, [
                    $r->id,
                    $r->last_name,
                    $r->first_name,
                    $r->email,
                    $r->phone,
                    $r->institution_name,
                    $r->t_shirt_size,
                    $r->status,
                    $r->qr_code,
                    $r->created_at?->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $registrations = EventRegistration::where('event_id', $this->event->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('institution_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->with('ticketType', 'checkIn')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $stats = [
            'total' => EventRegistration::where('event_id', $this->event->id)->count(),
            'confirmed' => EventRegistration::where('event_id', $this->event->id)->whereIn('status', ['registered','confirmed'])->count(),
            'checked_in' => EventRegistration::where('event_id', $this->event->id)->where('status', 'checked_in')->count(),
            'cancelled' => EventRegistration::where('event_id', $this->event->id)->where('status', 'cancelled')->count(),
            'capacity' => $this->event->capacity,
            'remaining' => $this->event->seatsRemaining(),
        ];

        return view('livewire.admin.event-registrations', [
            'registrations' => $registrations,
            'stats' => $stats,
        ])->extends('layouts.admin', ['title' => 'Inscriptions — ' . $this->event->title])->section('content');
    }
}
