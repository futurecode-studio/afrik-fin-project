<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterAgregateur = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Modal
    public $showModal = false;
    public $modalMode = 'view'; // view, edit
    public $selectedTransaction = null;
    
    // Statistiques
    public $stats = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterAgregateur' => ['except' => ''],
    ];

    public function mount()
    {
        $this->loadStats();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterAgregateur()
    {
        $this->resetPage();
    }

    public function loadStats()
    {
        $this->stats = [
            'total' => Transaction::count(),
            'succeeded' => Transaction::successful()->count(),
            'pending' => Transaction::pending()->count(),
            'failed' => Transaction::failed()->count(),
            'totalAmount' => Transaction::successful()->sum('amount'),
            'totalFees' => Transaction::successful()->sum('fees'),
            'todayCount' => Transaction::whereDate('created_at', today())->count(),
            'todayAmount' => Transaction::successful()->whereDate('created_at', today())->sum('amount'),
        ];
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewTransaction($id)
    {
        $this->selectedTransaction = Transaction::with(['user', 'course'])->find($id);
        $this->modalMode = 'view';
        $this->showModal = true;
    }

    public function editTransaction($id)
    {
        $this->selectedTransaction = Transaction::find($id);
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function updateStatus($id, $status)
    {
        $transaction = Transaction::find($id);
        
        if ($transaction) {
            $transaction->update(['status' => $status]);
            $this->loadStats();
            
            session()->flash('success', 'Statut de la transaction mis à jour avec succès.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedTransaction = null;
        $this->modalMode = 'view';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterAgregateur = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function exportTransactions()
    {
        // TODO: Implémenter l'export CSV/Excel
        session()->flash('info', 'Fonctionnalité d\'export en cours de développement.');
    }

    public function render()
    {
        $query = Transaction::with(['user', 'course'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('external_transaction_id', 'like', '%' . $this->search . '%')
                        ->orWhere('fullname', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterAgregateur, function ($q) {
                $q->where('agregateur', $this->filterAgregateur);
            })
            ->when($this->filterDateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->filterDateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $transactions = $query->paginate(15);

        return view('livewire.admin.transactions', [
            'transactions' => $transactions,
        ])
            ->extends('layouts.admin', ['title' => 'Transactions'])
            ->section('content');
    }
}
