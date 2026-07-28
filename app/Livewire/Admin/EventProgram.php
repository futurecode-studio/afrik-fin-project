<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventProgramItem;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class EventProgram extends Component
{
    use WithSweetAlert;

    public Event $event;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $itemId;

    public $title = '';
    public $description = '';
    public $starts_at = '';
    public $ends_at = '';
    public $location_detail = '';
    public $display_order = 0;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'starts_at' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'ends_at' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'location_detail' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'starts_at.required' => 'L’heure de début est obligatoire.',
            'starts_at.date_format' => 'Format d’heure invalide (HH:MM).',
            'ends_at.date_format' => 'Format d’heure invalide (HH:MM).',
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->display_order = (int) $this->event->programItems()->max('display_order') + 1;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function edit($id)
    {
        $item = EventProgramItem::where('event_id', $this->event->id)->findOrFail($id);
        $this->itemId = $item->id;
        $this->title = $item->title;
        $this->description = $item->description;
        $this->starts_at = $this->formatTime($item->starts_at);
        $this->ends_at = $this->formatTime($item->ends_at);
        $this->location_detail = $item->location_detail;
        $this->display_order = $item->display_order;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'event_id' => $this->event->id,
            'title' => $this->title,
            'description' => $this->description ?: null,
            'starts_at' => substr($this->starts_at, 0, 5) . ':00',
            'ends_at' => $this->ends_at ? (substr($this->ends_at, 0, 5) . ':00') : null,
            'location_detail' => $this->location_detail ?: null,
            'display_order' => (int) ($this->display_order ?? 0),
        ];

        if ($this->editMode) {
            EventProgramItem::where('event_id', $this->event->id)->findOrFail($this->itemId)->update($data);
            $this->swalSuccess('Créneau mis à jour.');
        } else {
            EventProgramItem::create($data);
            $this->swalSuccess('Créneau ajouté au programme.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->itemId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        EventProgramItem::where('event_id', $this->event->id)->findOrFail($this->itemId)->delete();
        $this->swalSuccess('Créneau supprimé.');
        $this->showDeleteModal = false;
        $this->itemId = null;
    }

    private function formatTime($value): string
    {
        if (!$value) {
            return '';
        }
        if ($value instanceof \Carbon\CarbonInterface) {
            return $value->format('H:i');
        }

        return substr((string) $value, 0, 5);
    }

    private function resetForm()
    {
        $this->itemId = null;
        $this->title = '';
        $this->description = '';
        $this->starts_at = '';
        $this->ends_at = '';
        $this->location_detail = '';
        $this->display_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $items = EventProgramItem::where('event_id', $this->event->id)
            ->orderBy('display_order')
            ->orderBy('starts_at')
            ->get();

        return view('livewire.admin.event-program', [
            'items' => $items,
        ])->extends('layouts.admin', ['title' => 'Programme — ' . $this->event->title])->section('content');
    }
}
