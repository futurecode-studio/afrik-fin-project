<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\SgiAccountRequest;
use Livewire\Component;
use Livewire\WithPagination;

class SgiAccountRequests extends Component
{
    use WithPagination, WithSweetAlert;

    public string $status = '';

    public string $search = '';

    public ?int $viewId = null;

    public string $admin_notes = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setStatus(int $id, string $status): void
    {
        if (! in_array($status, ['pending', 'contacted', 'in_progress', 'done', 'cancelled'], true)) {
            return;
        }

        $req = SgiAccountRequest::findOrFail($id);
        $data = ['status' => $status];
        if ($status === 'contacted' && ! $req->contacted_at) {
            $data['contacted_at'] = now();
        }
        $req->update($data);
        $this->swalSuccess('Statut mis à jour.');
    }

    public function openNotes(int $id): void
    {
        $req = SgiAccountRequest::findOrFail($id);
        $this->viewId = $req->id;
        $this->admin_notes = (string) ($req->admin_notes ?? '');
    }

    public function saveNotes(): void
    {
        if (! $this->viewId) {
            return;
        }
        SgiAccountRequest::whereKey($this->viewId)->update([
            'admin_notes' => $this->admin_notes !== '' ? $this->admin_notes : null,
        ]);
        $this->viewId = null;
        $this->admin_notes = '';
        $this->swalSuccess('Notes enregistrées.');
    }

    public function closeNotes(): void
    {
        $this->viewId = null;
        $this->admin_notes = '';
    }

    public function render()
    {
        $query = SgiAccountRequest::with('user')->latest();

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if (trim($this->search) !== '') {
            $s = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('email', 'like', $s)
                    ->orWhere('phone', 'like', $s);
            });
        }

        return view('livewire.admin.sgi-account-requests', [
            'requests' => $query->paginate(20),
            'pendingCount' => SgiAccountRequest::where('status', 'pending')->count(),
            'totalCount' => SgiAccountRequest::count(),
        ])
            ->extends('layouts.admin', ['title' => 'Demandes compte SGI'])
            ->section('content');
    }
}
