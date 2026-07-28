<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventSpeaker;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Concerns\WithSweetAlert;

class EventSpeakers extends Component
{
    use WithSweetAlert;
    use WithFileUploads;

    public Event $event;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $speakerId;

    public $name = '';
    public $role = '';
    public $bio = '';
    public $company = '';
    public $display_order = 0;
    public $photo = null;
    public $photo_url = '';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'company' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'photo.image' => 'La photo doit être une image.',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo.',
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->display_order = (int) $this->event->speakers()->max('display_order') + 1;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function edit($id)
    {
        $speaker = EventSpeaker::where('event_id', $this->event->id)->findOrFail($id);
        $this->speakerId = $speaker->id;
        $this->name = $speaker->name;
        $this->role = $speaker->role;
        $this->bio = $speaker->bio;
        $this->company = $speaker->company;
        $this->display_order = $speaker->display_order;
        $this->photo_url = $speaker->photo;
        $this->photo = null;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->photo) {
            $this->photo_url = $this->photo->store('events/speakers', 'public');
        }

        $data = [
            'event_id' => $this->event->id,
            'name' => $this->name,
            'role' => $this->role ?: null,
            'bio' => $this->bio ?: null,
            'company' => $this->company ?: null,
            'photo' => $this->photo_url ?: null,
            'display_order' => (int) ($this->display_order ?? 0),
        ];

        if ($this->editMode) {
            EventSpeaker::where('event_id', $this->event->id)->findOrFail($this->speakerId)->update($data);
            $this->swalSuccess('Intervenant mis à jour.');
        } else {
            EventSpeaker::create($data);
            $this->swalSuccess('Intervenant ajouté.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->speakerId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        EventSpeaker::where('event_id', $this->event->id)->findOrFail($this->speakerId)->delete();
        $this->swalSuccess('Intervenant supprimé.');
        $this->showDeleteModal = false;
        $this->speakerId = null;
    }

    private function resetForm()
    {
        $this->speakerId = null;
        $this->name = '';
        $this->role = '';
        $this->bio = '';
        $this->company = '';
        $this->display_order = 0;
        $this->photo = null;
        $this->photo_url = '';
        $this->resetValidation();
    }

    public function render()
    {
        $speakers = EventSpeaker::where('event_id', $this->event->id)
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        return view('livewire.admin.event-speakers', [
            'speakers' => $speakers,
        ])->extends('layouts.admin', ['title' => 'Intervenants — ' . $this->event->title])->section('content');
    }
}
