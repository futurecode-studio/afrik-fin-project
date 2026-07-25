<?php

namespace App\Livewire\Client;

use App\Models\ScheduledOrder;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class OrdresProgrammes extends Component
{
    use WithSweetAlert;
    public string $symbol = '';
    public string $condition_type = 'threshold';
    public string $side = 'buy';
    public string $quantity = '10';
    public string $target_price = '';
    public string $stop_loss = '';
    public string $take_profit = '';
    public bool $protection_active = true;

    public function mount(MarketsDataService $markets): void
    {
        $first = $markets->topVolume(1)->first();
        $this->symbol = $first?->symbol ?? '';
        if ($first) {
            $this->target_price = (string) $first->current_price;
            $this->stop_loss = (string) round($first->current_price * 0.96, 2);
            $this->take_profit = (string) round($first->current_price * 1.05, 2);
        }
    }

    public function updatedSymbol(MarketsDataService $markets): void
    {
        $stock = $markets->stockBySymbol($this->symbol);
        if ($stock) {
            $this->target_price = (string) $stock->current_price;
            $this->stop_loss = (string) round($stock->current_price * 0.96, 2);
            $this->take_profit = (string) round($stock->current_price * 1.05, 2);
        }
    }

    public function place(MarketsDataService $markets): void
    {
        $this->validate([
            'symbol' => 'required|string',
            'condition_type' => 'required|in:threshold,oco,trailing,linked',
            'side' => 'required|in:buy,sell',
            'quantity' => 'required|numeric|min:1',
            'target_price' => 'nullable|numeric|min:0',
            'stop_loss' => 'nullable|numeric|min:0',
            'take_profit' => 'nullable|numeric|min:0',
        ]);

        $stock = $markets->stockBySymbol($this->symbol);
        if (! $stock) {
            $this->addError('symbol', 'Titre introuvable.');

            return;
        }

        ScheduledOrder::create([
            'user_id' => Auth::id(),
            'stock_id' => $stock->id,
            'condition_type' => $this->condition_type,
            'side' => $this->side,
            'quantity' => $this->quantity,
            'target_price' => $this->target_price !== '' ? $this->target_price : null,
            'stop_loss' => $this->protection_active && $this->stop_loss !== '' ? $this->stop_loss : null,
            'take_profit' => $this->protection_active && $this->take_profit !== '' ? $this->take_profit : null,
            'protection_active' => $this->protection_active,
            'status' => 'pending',
            'notes' => 'Intention programmée — exécution via SGI agréée.',
        ]);

        $this->swalSuccess('Ordre programmé enregistré (intention). L’exécution passe par une SGI.');
    }

    public function cancelOrder(int $id): void
    {
        ScheduledOrder::where('user_id', Auth::id())->where('id', $id)->update(['status' => 'cancelled']);
        $this->swalSuccess('Ordre annulé.');
    }

    public function render(MarketsDataService $markets)
    {
        $stocks = $markets->stocks();
        $stock = $markets->stockBySymbol($this->symbol);
        $orders = ScheduledOrder::with('stock')->where('user_id', Auth::id())->latest()->limit(20)->get();

        return view('livewire.client.ordres-programmes', compact('stocks', 'stock', 'orders'))
            ->extends('layouts.client', ['title' => 'Ordres Programmés'])
            ->section('content');
    }
}
