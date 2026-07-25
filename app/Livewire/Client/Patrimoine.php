<?php

namespace App\Livewire\Client;

use App\Models\PortfolioHolding;
use App\Models\Stock;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class Patrimoine extends Component
{
    use WithSweetAlert;
    public string $stock_id = '';
    public string $quantity = '';
    public string $avg_cost = '';
    public string $asset_type = 'action';
    public string $label = '';

    public function addHolding(): void
    {
        $this->validate([
            'asset_type' => 'required|in:action,fcp,obligation,cash',
            'stock_id' => 'nullable|required_if:asset_type,action|exists:stocks,id',
            'quantity' => 'required|numeric|min:0.0001',
            'avg_cost' => 'nullable|numeric|min:0',
            'label' => 'nullable|string|max:255',
        ]);

        PortfolioHolding::create([
            'user_id' => Auth::id(),
            'stock_id' => $this->asset_type === 'action' ? $this->stock_id : null,
            'label' => $this->label ?: null,
            'asset_type' => $this->asset_type,
            'quantity' => $this->quantity,
            'avg_cost' => $this->avg_cost !== '' ? $this->avg_cost : null,
            'currency' => 'XOF',
        ]);

        $this->swalSuccess('Ligne de patrimoine ajoutée.');
        $this->reset(['stock_id', 'quantity', 'avg_cost', 'label']);
        $this->asset_type = 'action';
    }

    public function remove(int $id): void
    {
        PortfolioHolding::where('user_id', Auth::id())->whereKey($id)->delete();
        $this->swalSuccess('Ligne retirée.');
    }

    public function render(MarketsDataService $markets)
    {
        $holdings = PortfolioHolding::with('stock')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $watchValue = Auth::user()->watchlistItems()->with('stock')->get()
            ->sum(fn ($w) => (float) ($w->stock->current_price ?? 0));

        $rows = $holdings->map(function (PortfolioHolding $h) {
            $mv = $h->marketValue();
            $cost = $h->costBasis();

            return [
                'model' => $h,
                'name' => $h->stock?->company_name ?? ($h->label ?: strtoupper($h->asset_type)),
                'symbol' => $h->stock?->symbol,
                'market_value' => $mv,
                'cost' => $cost,
                'pnl' => $mv - $cost,
                'pnl_pct' => $cost > 0 ? (($mv - $cost) / $cost) * 100 : 0,
            ];
        });

        $total = $rows->sum('market_value');
        $allocation = $holdings->groupBy('asset_type')->map(fn ($g) => $g->sum(fn ($h) => $h->marketValue()));

        return view('livewire.client.patrimoine', [
            'rows' => $rows,
            'total' => $total,
            'allocation' => $allocation,
            'watchValue' => $watchValue,
            'stocks' => $markets->stocks(),
        ])
            ->extends('layouts.client', ['title' => 'Vue Consolidée du Patrimoine'])
            ->section('content');
    }
}
