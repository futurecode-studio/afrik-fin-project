<?php

namespace App\Livewire\Client;

use App\Models\MarketAlert;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class AlertesMarche extends Component
{
    use WithSweetAlert;
    public string $severity = '';
    public string $category = '';
    public bool $showForm = false;

    public string $stock_symbol = '';
    public string $trigger_type = 'price_above';
    public string $threshold = '';
    public string $alert_severity = 'normale';
    public string $channel = 'in_app';

    public function openForm(MarketsDataService $markets): void
    {
        $this->showForm = true;
        if ($this->stock_symbol === '') {
            $this->stock_symbol = $markets->topVolume(1)->first()?->symbol ?? '';
        }
    }

    public function create(MarketsDataService $markets): void
    {
        $this->validate([
            'stock_symbol' => 'required|string',
            'trigger_type' => 'required|in:price_above,price_below,rsi,volume,calendar',
            'threshold' => 'nullable|numeric|min:0',
            'alert_severity' => 'required|in:faible,normale,critique',
            'channel' => 'required|in:in_app,email,sms',
        ]);

        $stock = $markets->stockBySymbol($this->stock_symbol);
        if (! $stock) {
            $this->addError('stock_symbol', 'Titre introuvable.');

            return;
        }

        MarketAlert::create([
            'user_id' => Auth::id(),
            'stock_id' => $stock->id,
            'asset_label' => $stock->company_name,
            'asset_category' => 'action',
            'trigger_type' => $this->trigger_type,
            'threshold' => $this->threshold !== '' ? $this->threshold : null,
            'severity' => $this->alert_severity,
            'status' => 'active',
            'channel' => $this->channel,
        ]);

        $this->reset('threshold', 'showForm');
        $this->swalSuccess('Alerte créée.');
    }

    public function deleteAlert(int $id): void
    {
        MarketAlert::where('user_id', Auth::id())->where('id', $id)->delete();
        $this->swalSuccess('Alerte supprimée.');
    }

    public function render(MarketsDataService $markets)
    {
        $query = MarketAlert::with('stock')->where('user_id', Auth::id())->latest();
        if ($this->severity !== '') {
            $query->where('severity', $this->severity);
        }
        if ($this->category !== '') {
            $query->where('asset_category', $this->category);
        }
        $alerts = $query->get();
        $stocks = $markets->stocks();
        $critical = $alerts->where('severity', 'critique')->count();
        $active = $alerts->where('status', 'active')->count();

        return view('livewire.client.alertes-marche', compact('alerts', 'stocks', 'critical', 'active'))
            ->extends('layouts.client', ['title' => 'Alertes de Marché'])
            ->section('content');
    }
}
