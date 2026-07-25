<?php

namespace App\Livewire\Admin;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class Contacts extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $showModal = false;
    public $selectedContact = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewContact($id)
    {
        $this->selectedContact = Contact::findOrFail($id);
        
        // Marquer comme lu si nouveau
        if ($this->selectedContact->status === 'new') {
            $this->selectedContact->update(['status' => 'read']);
        }
        
        $this->showModal = true;
    }

    public function closeModal()
{
        $this->showModal = false;
    }

    public function markAsReplied($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => 'replied']);
        $this->swalSuccess('Message marqué comme répondu.');
        
        if ($this->selectedContact && $this->selectedContact->id === $id) {
            $this->selectedContact = Contact::findOrFail($id);
        }
    }

    public function deleteContact($id)
    {
        Contact::findOrFail($id)->delete();
        $this->swalSuccess('Message supprimé avec succès.');
        
        if ($this->selectedContact && $this->selectedContact->id === $id) {
            $this->closeModal();
        }
    }

    public function render()
    {
        $query = Contact::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('subject', 'like', '%' . $this->search . '%');
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
