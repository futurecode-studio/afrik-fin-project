<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\SgiRequiredDocument;
use Livewire\Component;

class SgiRequiredDocuments extends Component
{
    use WithSweetAlert;

    public bool $showModal = false;

    public bool $editMode = false;

    public ?int $documentId = null;

    public string $title = '';

    public string $description = '';

    public string $display_order = '0';

    public bool $is_active = true;

    public function openModal(): void
    {
        $this->resetForm();
        $this->editMode = false;
        $this->display_order = (string) ((int) SgiRequiredDocument::max('display_order') + 10);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $doc = SgiRequiredDocument::findOrFail($id);
        $this->documentId = $doc->id;
        $this->title = $doc->title;
        $this->description = (string) ($doc->description ?? '');
        $this->display_order = (string) $doc->display_order;
        $this->is_active = $doc->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description !== '' ? $this->description : null,
            'display_order' => (int) ($this->display_order ?: 0),
            'is_active' => $this->is_active,
        ];

        if ($this->editMode && $this->documentId) {
            SgiRequiredDocument::whereKey($this->documentId)->update($data);
            $this->swalSuccess('Document mis à jour.');
        } else {
            SgiRequiredDocument::create($data);
            $this->swalSuccess('Document ajouté.');
        }

        $this->closeModal();
    }

    public function toggleActive(int $id): void
    {
        $doc = SgiRequiredDocument::findOrFail($id);
        $doc->update(['is_active' => ! $doc->is_active]);
        $this->swalSuccess($doc->is_active ? 'Document activé.' : 'Document masqué.');
    }

    public function delete(int $id): void
    {
        SgiRequiredDocument::whereKey($id)->delete();
        $this->swalSuccess('Document supprimé.');
    }

    protected function resetForm(): void
    {
        $this->documentId = null;
        $this->title = '';
        $this->description = '';
        $this->display_order = '0';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.sgi-required-documents', [
            'documents' => SgiRequiredDocument::orderBy('display_order')->orderBy('id')->get(),
        ])
            ->extends('layouts.admin', ['title' => 'Documents ouverture SGI'])
            ->section('content');
    }
}
