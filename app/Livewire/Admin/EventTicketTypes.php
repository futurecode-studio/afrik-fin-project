<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventTicketType;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class EventTicketTypes extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public Event $event;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $ticketTypeId;

    public $name = '';
    public $description = '';
    public $price = 0;
    public $quantity = 0;
    public $is_active = true;
    public $display_order = 0;

    protected $paginationTheme = 'tailwind';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du billet est obligatoire.',
            'price.required' => 'Le prix est obligatoire (0 = gratuit).',
            'price.min' => 'Le prix ne peut pas être négatif.',
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->display_order = (int) $this->event->ticketTypes()->max('display_order') + 1;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function edit($id)
    {
        $ticket = EventTicketType::where('event_id', $this->event->id)->findOrFail($id);
        $this->ticketTypeId = $ticket->id;
        $this->name = $ticket->name;
        $this->description = $ticket->description;
        $this->price = $ticket->price;
        $this->quantity = $ticket->quantity;
        $this->is_active = $ticket->is_active;
        $this->display_order = $ticket->display_order;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if (!$this->event->usesTickets()) {
            $this->swalError('Activez d’abord « Types de billets » sur la fiche événement, puis configurez les billets gratuits et/ou payants.');
            return;
        }

        $data = [
            'event_id' => $this->event->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'quantity' => (int) ($this->quantity ?? 0),
            'is_active' => (bool) $this->is_active,
            'display_order' => (int) ($this->display_order ?? 0),
        ];

        if ($this->editMode) {
            $ticket = EventTicketType::where('event_id', $this->event->id)->findOrFail($this->ticketTypeId);
            if ((int) $data['quantity'] > 0 && (int) $data['quantity'] < (int) $ticket->sold) {
                $this->swalError('La quantité ne peut pas être inférieure aux billets déjà vendus (' . $ticket->sold . ').');
                return;
            }
            $ticket->update($data);
            $this->swalSuccess('Type de billet mis à jour.');
        } else {
            EventTicketType::create($data);
            $this->swalSuccess('Type de billet créé.');
        }

        $this->closeModal();
    }

    public function toggleActive($id)
    {
        $ticket = EventTicketType::where('event_id', $this->event->id)->findOrFail($id);
        $ticket->update(['is_active' => !$ticket->is_active]);
        $this->swalSuccess($ticket->is_active ? 'Billet activé.' : 'Billet désactivé.');
    }

    public function confirmDelete($id)
    {
        $this->ticketTypeId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $ticket = EventTicketType::where('event_id', $this->event->id)->findOrFail($this->ticketTypeId);

        if ($ticket->sold > 0 || $ticket->registrations()->exists()) {
            $this->swalError('Impossible de supprimer : des inscriptions utilisent déjà ce billet. Désactivez-le plutôt.');
            $this->showDeleteModal = false;
            return;
        }

        $ticket->delete();
        $this->swalSuccess('Type de billet supprimé.');
        $this->showDeleteModal = false;
        $this->ticketTypeId = null;
    }

    private function resetForm()
    {
        $this->ticketTypeId = null;
        $this->name = '';
        $this->description = '';
        $this->price = 0;
        $this->quantity = 0;
        $this->is_active = true;
        $this->display_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $tickets = EventTicketType::where('event_id', $this->event->id)
            ->orderBy('display_order')
            ->orderBy('id')
            ->paginate(20);

        return view('livewire.admin.event-ticket-types', [
            'tickets' => $tickets,
        ])->extends('layouts.admin', ['title' => 'Billets — ' . $this->event->title])->section('content');
    }
}
