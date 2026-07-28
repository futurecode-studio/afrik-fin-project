<?php

namespace App\Livewire\Client;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\MarketAlert;
use App\Services\MarketAlertEvaluator;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AlertesMarche extends Component
{
    use WithSweetAlert;

    public string $severity = '';

    public string $category = '';

    public string $statusFilter = '';

    public bool $showForm = false;

    public string $stock_symbol = '';

    public string $trigger_type = 'price_above';

    public string $threshold = '';

    public string $alert_severity = 'normale';

    public string $channel = 'in_app';

    public function mount(MarketsDataService $markets, MarketAlertEvaluator $evaluator): void
    {
        $evaluator->evaluate(Auth::id());

        if ($this->stock_symbol === '') {
            $this->stock_symbol = $markets->topVolume(1)->first()?->symbol ?? '';
        }
        $this->suggestThreshold($markets);
    }

    public function openForm(MarketsDataService $markets): void
    {
        $this->showForm = true;
        $this->resetValidation();
        if ($this->stock_symbol === '') {
            $this->stock_symbol = $markets->topVolume(1)->first()?->symbol ?? '';
        }
        $this->suggestThreshold($markets);
    }

    public function updatedStockSymbol(MarketsDataService $markets): void
    {
        $this->suggestThreshold($markets);
    }

    public function updatedTriggerType(MarketsDataService $markets): void
    {
        $this->suggestThreshold($markets);
    }

    public function create(MarketsDataService $markets, MarketAlertEvaluator $evaluator): void
    {
        $this->validate([
            'stock_symbol' => 'required|string',
            'trigger_type' => 'required|in:price_above,price_below,volume,variation_above,variation_below',
            'threshold' => 'required|numeric',
            'alert_severity' => 'required|in:faible,normale,critique',
            'channel' => 'required|in:in_app,email',
        ], [
            'threshold.required' => 'Indiquez un seuil de déclenchement.',
        ]);

        $stock = $markets->stockBySymbol($this->stock_symbol);
        if (! $stock) {
            $this->addError('stock_symbol', 'Titre introuvable.');

            return;
        }

        $alert = MarketAlert::create([
            'user_id' => Auth::id(),
            'stock_id' => $stock->id,
            'asset_label' => $stock->company_name,
            'asset_category' => 'action',
            'trigger_type' => $this->trigger_type,
            'threshold' => $this->threshold,
            'severity' => $this->alert_severity,
            'status' => 'active',
            'channel' => $this->channel,
        ]);

        // Déclenche immédiatement si la condition est déjà vraie
        if ($evaluator->conditionMet($alert->load('stock'))) {
            $evaluator->markTriggered($alert);
            $this->swalSuccess('Alerte créée — condition déjà atteinte, alerte déclenchée.');
        } else {
            $this->swalSuccess('Alerte créée et active.');
        }

        $this->reset('threshold', 'showForm');
        $this->trigger_type = 'price_above';
        $this->alert_severity = 'normale';
        $this->channel = 'in_app';
        $this->suggestThreshold($markets);
    }

    public function togglePause(int $id): void
    {
        $alert = MarketAlert::where('user_id', Auth::id())->findOrFail($id);

        if ($alert->status === 'paused') {
            $alert->update(['status' => 'active', 'triggered_at' => null, 'notes' => null]);
            $this->swalSuccess('Alerte réactivée.');
        } elseif ($alert->status === 'active') {
            $alert->update(['status' => 'paused']);
            $this->swalSuccess('Alerte mise en pause.');
        }
    }

    public function rearm(int $id, MarketAlertEvaluator $evaluator): void
    {
        $alert = MarketAlert::with('stock')->where('user_id', Auth::id())->findOrFail($id);
        $alert->update([
            'status' => 'active',
            'triggered_at' => null,
            'notes' => null,
        ]);

        if ($evaluator->conditionMet($alert->fresh('stock'))) {
            $evaluator->markTriggered($alert->fresh('stock'));
            $this->swalSuccess('Réarmée — toujours dans la zone de déclenchement.');
        } else {
            $this->swalSuccess('Alerte réarmée et active.');
        }
    }

    public function deleteAlert(int $id): void
    {
        MarketAlert::where('user_id', Auth::id())->where('id', $id)->delete();
        $this->swalSuccess('Alerte supprimée.');
    }

    public function checkNow(MarketAlertEvaluator $evaluator): void
    {
        $triggered = $evaluator->evaluate(Auth::id());
        if ($triggered->isEmpty()) {
            $this->swalSuccess('Vérification terminée — aucune nouvelle alerte.');
        } else {
            $this->swalSuccess($triggered->count().' alerte(s) déclenchée(s).');
        }
    }

    public function render(MarketsDataService $markets, MarketAlertEvaluator $evaluator)
    {
        $query = MarketAlert::with('stock')->where('user_id', Auth::id())->latest();

        if ($this->severity !== '') {
            $query->where('severity', $this->severity);
        }
        if ($this->category !== '') {
            $query->where('asset_category', $this->category);
        }
        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        $alerts = $query->get();
        $all = MarketAlert::where('user_id', Auth::id())->get();
        $stocks = $markets->stocks();
        $selected = $markets->stockBySymbol($this->stock_symbol);
        $sentiment = $evaluator->marketSentiment($stocks);

        return view('livewire.client.alertes-marche', [
            'alerts' => $alerts,
            'stocks' => $stocks,
            'selected' => $selected,
            'critical' => $all->where('severity', 'critique')->where('status', '!=', 'paused')->count(),
            'active' => $all->where('status', 'active')->count(),
            'triggeredCount' => $all->where('status', 'triggered')->count(),
            'sentiment' => $sentiment,
        ])
            ->extends('layouts.client', ['title' => 'Alertes de Marché'])
            ->section('content');
    }

    private function suggestThreshold(MarketsDataService $markets): void
    {
        $stock = $markets->stockBySymbol($this->stock_symbol);
        if (! $stock) {
            return;
        }

        $this->threshold = match ($this->trigger_type) {
            'price_above' => (string) round((float) $stock->current_price * 1.02, 2),
            'price_below' => (string) round((float) $stock->current_price * 0.98, 2),
            'volume' => (string) max(1, (int) round(((float) ($stock->volume ?? 0)) * 1.5)),
            'variation_above' => '2',
            'variation_below' => '-2',
            default => (string) $stock->current_price,
        };
    }
}
