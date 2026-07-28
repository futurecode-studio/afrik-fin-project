<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class Contacts extends Component
{
    use WithPagination;
    use WithSweetAlert;

    public $search = '';

    public $statusFilter = 'all';

    public $showModal = false;

    public $selectedContact = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function viewContact($id): void
    {
        $this->selectedContact = Contact::findOrFail($id);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedContact = null;
    }

    public function markAsRead($id): void
    {
        $contact = Contact::findOrFail($id);

        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
            $this->swalSuccess('Message marqué comme lu.');
        }

        if ($this->selectedContact && $this->selectedContact->id === (int) $id) {
            $this->selectedContact = $contact->fresh();
        }
    }

    public function markAsReplied($id): void
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => 'replied']);
        $this->swalSuccess('Message marqué comme répondu.');

        if ($this->selectedContact && $this->selectedContact->id === (int) $id) {
            $this->selectedContact = $contact->fresh();
        }
    }

    public function deleteContact($id): void
    {
        Contact::findOrFail($id)->delete();
        $this->swalSuccess('Message supprimé avec succès.');

        if ($this->selectedContact && $this->selectedContact->id === (int) $id) {
            $this->closeModal();
        }
    }

    public function render()
    {
        $query = Contact::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('subject', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $contacts = $query->latest()->paginate(15);

        return view('livewire.admin.contacts', [
            'contacts' => $contacts,
        ])
            ->extends('layouts.admin', ['title' => 'Messages de Contact'])
            ->section('content');
    }
}
