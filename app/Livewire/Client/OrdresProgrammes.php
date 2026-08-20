<?php

namespace App\Livewire\Client;

use App\Livewire\Concerns\WithSgiOrderModal;
use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Partner;
use App\Models\ScheduledOrder;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrdresProgrammes extends Component
{
    use WithSgiOrderModal;
    use WithSweetAlert;

    public string $symbol = '';

    public string $condition_type = 'threshold';

    public string $side = 'buy';

    public string $quantity = '10';

    public string $target_price = '';

    public string $stop_loss = '';

    public string $take_profit = '';

    public bool $protection_active = true;

    public string $partner_id = '';

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

    public function prepareSubmit(): void
    {
        if (! feature_enabled('client.ordres')) {
            $this->swalSuccess('Service bientôt disponible.');

            return;
        }

        $this->openOrderModal();
    }

    public function submitWithSgiAccount(MarketsDataService $markets): void
    {
        if (! feature_enabled('client.ordres')) {
            $this->swalSuccess('Service bientôt disponible.');

            return;
        }

        $this->validate([
            'symbol' => 'required|string',
            'condition_type' => 'required|in:threshold,oco,trailing,linked',
            'side' => 'required|in:buy,sell',
            'quantity' => 'required|numeric|min:1',
            'target_price' => 'nullable|numeric|min:0',
            'stop_loss' => 'nullable|numeric|min:0',
            'take_profit' => 'nullable|numeric|min:0',
            'partner_id' => 'required|exists:partners,id',
            'sgi_account_number' => 'required|string|min:3|max:120',
        ]);

        $stock = $markets->stockBySymbol($this->symbol);
        if (! $stock) {
            $this->addError('symbol', 'Titre introuvable.');

            return;
        }

        $ok = Partner::sgi()->active()->whereKey($this->partner_id)->exists();
        if (! $ok) {
            $this->addError('partner_id', 'SGI invalide ou inactive.');

            return;
        }

        ScheduledOrder::create([
            'user_id' => Auth::id(),
            'partner_id' => (int) $this->partner_id,
            'sgi_account_number' => trim($this->sgi_account_number),
            'stock_id' => $stock->id,
            'condition_type' => $this->condition_type,
            'side' => $this->side,
            'quantity' => $this->quantity,
            'target_price' => $this->target_price !== '' ? $this->target_price : null,
            'stop_loss' => $this->protection_active && $this->stop_loss !== '' ? $this->stop_loss : null,
            'take_profit' => $this->protection_active && $this->take_profit !== '' ? $this->take_profit : null,
            'protection_active' => $this->protection_active,
            'status' => 'pending',
            'notes' => 'Intention — à relayer vers une SGI (pas d’exécution ADF).',
        ]);

        $this->closeOrderModal();
        $this->swalSuccess('Intention enregistrée. Votre SGI agréée devra l’exécuter.');
        $this->partner_id = '';
        $this->sgi_account_number = '';
    }

    protected function sgiAccountRequestSource(): string
    {
        return 'ordres';
    }

    public function render(MarketsDataService $markets)
    {
        if (! feature_enabled('client.ordres')) {
            $stock = $markets->topVolume(1)->first();

            return view('livewire.client.ordres-programmes', compact('stock'))
                ->extends('layouts.client', ['title' => 'Souscriptions'])
                ->section('content');
        }

        $stocks = $markets->stocks();
        $stock = $markets->stockBySymbol($this->symbol);
        $partners = Partner::sgi()->active()->orderBy('nom')->get();
        $requiredDocs = $this->sgiRequiredDocuments();
        $orders = ScheduledOrder::with(['stock', 'partner'])
            ->where('user_id', Auth::id())
            ->latest()
            ->limit(20)
            ->get();

        return view('livewire.client.ordres-programmes-live', compact('stocks', 'stock', 'orders', 'partners', 'requiredDocs'))
            ->extends('layouts.client', ['title' => 'Souscriptions'])
            ->section('content');
    }
}
