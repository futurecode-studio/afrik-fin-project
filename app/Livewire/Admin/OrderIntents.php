<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\ScheduledOrder;
use App\Models\StockOrderIntent;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIntents extends Component
{
    use WithPagination, WithSweetAlert;

    /** @var string carnet|programmes */
    public string $tab = 'carnet';

    public string $status = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingTab(): void
    {
        $this->resetPage();
        $this->status = '';
    }

    public function setCarnetStatus(int $id, string $status): void
    {
        if (! in_array($status, ['pending', 'relayed', 'cancelled', 'done'], true)) {
            return;
        }
        StockOrderIntent::whereKey($id)->update(['status' => $status]);
        $this->swalSuccess('Statut mis à jour.');
    }

    public function setProgrammeStatus(int $id, string $status): void
    {
        if (! in_array($status, ['pending', 'relayed', 'cancelled', 'done', 'triggered'], true)) {
            return;
        }
        ScheduledOrder::whereKey($id)->update(['status' => $status]);
        $this->swalSuccess('Statut mis à jour.');
    }

    public function render()
    {
        $carnetQuery = StockOrderIntent::with(['stock', 'user', 'partner'])->latest();
        $progQuery = ScheduledOrder::with(['stock', 'user', 'partner'])->latest();

        if ($this->status !== '') {
            $carnetQuery->where('status', $this->status);
            $progQuery->where('status', $this->status);
        }

        return view('livewire.admin.order-intents', [
            'tab' => $this->tab,
            'carnetCount' => StockOrderIntent::count(),
            'programmeCount' => ScheduledOrder::count(),
            'pendingTotal' => StockOrderIntent::where('status', 'pending')->count()
                + ScheduledOrder::where('status', 'pending')->count(),
            'intents' => $this->tab === 'carnet'
                ? $carnetQuery->paginate(20)
                : $progQuery->paginate(20),
        ])
            ->extends('layouts.admin', ['title' => 'Intentions d’ordres'])
            ->section('content');
    }
}
