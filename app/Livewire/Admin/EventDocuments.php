<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventDocument;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Concerns\WithSweetAlert;

class EventDocuments extends Component
{
    use WithSweetAlert;
    use WithFileUploads;

    public Event $event;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $documentId;

    public $title = '';
    public $file = null;
    public $file_path = '';
    public $file_type = 'pdf';
    public $file_size = 0;
    public $is_downloadable = true;
    public $display_order = 0;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'file' => ($this->editMode && $this->file_path)
                ? 'nullable|file|max:10240'
                : 'required|file|max:10240',
            'is_downloadable' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'file.required' => 'Le fichier est obligatoire.',
            'file.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->display_order = (int) $this->event->documents()->max('display_order') + 1;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function edit($id)
    {
        $doc = EventDocument::where('event_id', $this->event->id)->findOrFail($id);
        $this->documentId = $doc->id;
        $this->title = $doc->title;
        $this->file_path = $doc->file_path;
        $this->file_type = $doc->file_type;
        $this->file_size = $doc->file_size;
        $this->is_downloadable = $doc->is_downloadable;
        $this->display_order = $doc->display_order;
        $this->file = null;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->file) {
            $this->file_path = $this->file->store('events/documents', 'public');
            $this->file_type = strtolower($this->file->getClientOriginalExtension() ?: 'file');
            $this->file_size = (int) $this->file->getSize();
        }

        $data = [
            'event_id' => $this->event->id,
            'title' => $this->title,
            'file_path' => $this->file_path,
            'file_type' => $this->file_type ?: 'file',
            'file_size' => (int) $this->file_size,
            'is_downloadable' => (bool) $this->is_downloadable,
            'display_order' => (int) ($this->display_order ?? 0),
        ];

        if ($this->editMode) {
            EventDocument::where('event_id', $this->event->id)->findOrFail($this->documentId)->update($data);
            $this->swalSuccess('Document mis à jour.');
        } else {
            EventDocument::create($data);
            $this->swalSuccess('Document ajouté.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->documentId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        EventDocument::where('event_id', $this->event->id)->findOrFail($this->documentId)->delete();
        $this->swalSuccess('Document supprimé.');
        $this->showDeleteModal = false;
        $this->documentId = null;
    }

    public function toggleDownloadable($id)
    {
        $doc = EventDocument::where('event_id', $this->event->id)->findOrFail($id);
        $doc->update(['is_downloadable' => !$doc->is_downloadable]);
        $this->swalSuccess($doc->is_downloadable ? 'Document visible en téléchargement.' : 'Document masqué du public.');
    }

    private function resetForm()
    {
        $this->documentId = null;
        $this->title = '';
        $this->file = null;
        $this->file_path = '';
        $this->file_type = 'pdf';
        $this->file_size = 0;
        $this->is_downloadable = true;
        $this->display_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $documents = EventDocument::where('event_id', $this->event->id)
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        return view('livewire.admin.event-documents', [
            'documents' => $documents,
        ])->extends('layouts.admin', ['title' => 'Documents — ' . $this->event->title])->section('content');
    }
}
