<?php

namespace App\Livewire\Pages\Marches;

use App\Livewire\Concerns\WithSgiOrderModal;
use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Partner;
use App\Models\StockOrderIntent;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Carnet d'ordres "intention" — n'exécute pas sur le marché.
 * Les demandes sont relayées vers une SGI via mise en relation.
 */
class CarnetOrdres extends Component
{
    use WithSgiOrderModal;
    use WithSweetAlert;

    #[Url(as: 'symbol', except: '')]
    public string $symbol = '';

    public string $side = 'buy';

    public string $order_type = 'limit';

    public string $quantity = '10';

    public string $limit_price = '';

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $notes = '';

    public string $partner_id = '';

    public function mount(MarketsDataService $markets): void
    {
        if ($this->symbol === '') {
            $first = $markets->stocks()->first();
            $this->symbol = $first?->symbol ?? 'SNTS';
        }
        $stock = $markets->stockBySymbol($this->symbol);
        if ($stock && $this->limit_price === '') {
            $this->limit_price = (string) $stock->current_price;
        }
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone ?? '';
        }
    }

    public function updatedSymbol(MarketsDataService $markets): void
    {
        $stock = $markets->stockBySymbol($this->symbol);
        if ($stock) {
            $this->limit_price = (string) $stock->current_price;
        }
    }

    /**
     * Ouvre le modal SGI (montants validés uniquement si compte SGI existant).
     */
    public function prepareSubmit(): void
    {
        if (! feature_enabled('marches.carnet')) {
            $this->swalSuccess('Service bientôt disponible — contactez une SGI via nos partenaires.');

            return;
        }

        $this->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|min:8|max:30',
        ]);

        $this->openOrderModal();
    }

    public function submitWithSgiAccount(MarketsDataService $markets): void
    {
        if (! feature_enabled('marches.carnet')) {
            $this->swalSuccess('Service bientôt disponible.');

            return;
        }

        $this->validate([
            'symbol' => 'required|string',
            'side' => 'required|in:buy,sell',
            'order_type' => 'required|in:limit,market',
            'quantity' => 'required|numeric|min:1',
            'limit_price' => 'nullable|numeric|min:0',
            'partner_id' => 'required|exists:partners,id',
            'sgi_account_number' => 'required|string|min:3|max:120',
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|min:8|max:30',
            'notes' => 'nullable|string|max:1000',
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

        StockOrderIntent::create([
            'user_id' => Auth::id(),
            'partner_id' => (int) $this->partner_id,
            'sgi_account_number' => trim($this->sgi_account_number),
            'stock_id' => $stock->id,
            'side' => $this->side,
            'order_type' => $this->order_type,
            'quantity' => $this->quantity,
            'limit_price' => $this->order_type === 'limit' ? $this->limit_price : null,
            'status' => 'pending',
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'notes' => $this->notes,
        ]);

        $this->closeOrderModal();
        $this->swalSuccess('Intention d’ordre enregistrée. Votre SGI sera contactée pour l’exécution — Africaine des Finances n’exécute pas les ordres.');
        $this->reset('notes', 'sgi_account_number', 'partner_id');
    }

    protected function sgiAccountRequestSource(): string
    {
        return 'carnet';
    }

    protected function usesPublicContactFields(): bool
    {
        return true;
    }

    public function render(MarketsDataService $markets)
    {
        if (! feature_enabled('marches.carnet')) {
            return view('livewire.pages.marches.carnet-ordres-soon')
                ->extends('layouts.site', ['title' => 'Carnet d’Ordres — Bientôt'])
                ->section('content');
        }

        $stocks = $markets->stocks();
        $stock = $markets->stockBySymbol($this->symbol);
        $book = $stocks->sortByDesc('volume')->take(8)->values();
        $partners = Partner::sgi()->active()->orderBy('nom')->get();
        $requiredDocs = $this->sgiRequiredDocuments();

        $myIntents = Auth::check()
            ? StockOrderIntent::with(['stock', 'partner'])->where('user_id', Auth::id())->latest()->limit(8)->get()
            : collect();

        return view('livewire.pages.marches.carnet-ordres', compact('stocks', 'stock', 'book', 'myIntents', 'partners', 'requiredDocs'))
            ->extends('layouts.site', ['title' => 'Carnet d’Ordres Direct — Africaine des Finances'])
            ->section('content');
    }
}
