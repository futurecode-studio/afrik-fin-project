<?php

namespace App\Livewire\Admin;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class JobApplications extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $applicationId = null;
    public $admin_notes = '';
    public $status = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewApplication($id)
    {
        $application = JobApplication::findOrFail($id);
        $this->applicationId = $application->id;
        $this->admin_notes = $application->admin_notes;
        $this->status = $application->status;
        $this->showModal = true;
    }

    public function closeModal()
{
        $this->showModal = false;
    }

    public function updateApplication()
    {
        $application = JobApplication::findOrFail($this->applicationId);
        
        $application->update([
            'status' => $this->status,
            'admin_notes' => $this->admin_notes,
            'reviewed_at' => now(),
        ]);

        $this->swalSuccess('Candidature mise à jour avec succès.');
        $this->closeModal();
    }

    public function downloadCV($id)
    {
        $application = JobApplication::findOrFail($id);
        return Storage::disk('public')->download($application->cv_path);
    }

    public function confirmDelete($id)
    {
        $this->applicationId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $application = JobApplication::findOrFail($this->applicationId);
        
        if ($application->cv_path && Storage::disk('public')->exists($application->cv_path)) {
            Storage::disk('public')->delete($application->cv_path);
        }
        
        $application->delete();
        
        $this->swalSuccess('Candidature supprimée avec succès.');
        $this->showDeleteModal = false;
        $this->applicationId = null;
    }

    public function render()
    {
        $query = JobApplication::query()->with('user');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('position_applied', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        $applications = $query->latest()->paginate(10);

        return view('livewire.admin.job-applications', [
            'applications' => $applications,
        ])
            ->extends('layouts.admin', ['title' => 'Gestion des Candidatures'])
            ->section('content');
    }
}
