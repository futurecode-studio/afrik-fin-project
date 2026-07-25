<?php

namespace App\Livewire\Admin;

use App\Models\InvestmentAppointment;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class Appointments extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterType = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $appointmentId = null;
    public $status = '';
    public $confirmed_date = '';
    public $admin_notes = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewAppointment($id)
    {
        $appointment = InvestmentAppointment::findOrFail($id);
        $this->appointmentId = $appointment->id;
        $this->status = $appointment->status;
        $this->confirmed_date = $appointment->confirmed_date ? $appointment->confirmed_date->format('Y-m-d\TH:i') : '';
        $this->admin_notes = $appointment->admin_notes;
        $this->showModal = true;
    }

    public function closeModal()
{
        $this->showModal = false;
    }

    public function updateAppointment()
    {
        $this->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'confirmed_date' => 'nullable|date',
        ]);

        $appointment = InvestmentAppointment::findOrFail($this->appointmentId);
        
        $appointment->update([
            'status' => $this->status,
            'confirmed_date' => $this->confirmed_date ? $this->confirmed_date : null,
            'admin_notes' => $this->admin_notes,
        ]);

        $this->swalSuccess('Rendez-vous mis à jour avec succès.');
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->appointmentId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $appointment = InvestmentAppointment::findOrFail($this->appointmentId);
        $appointment->delete();
        
        $this->swalSuccess('Rendez-vous supprimé avec succès.');
        $this->showDeleteModal = false;
        $this->appointmentId = null;
    }

    public function render()
    {
        $query = InvestmentAppointment::query()->with('user');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('company', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterType !== '') {
            $query->where('investment_type', $this->filterType);
        }

        $appointments = $query->latest()->paginate(10);

        return view('livewire.admin.appointments', [
            'appointments' => $appointments,
        ])
            ->extends('layouts.admin', ['title' => 'Rendez-vous'])
            ->section('content');
    }
}
