<?php

namespace App\Livewire\Pages\Marches;

use App\Models\Stock;
use App\Models\StockOrderIntent;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

/**
 * Carnet d'ordres "intention" — n'exécute pas sur le marché.
 * Les demandes sont relayées vers une SGI via mise en relation.
 */
class CarnetOrdres extends Component
{
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

    public function submit(MarketsDataService $markets): void
    {
        $this->validate([
            'symbol' => 'required|string',
            'side' => 'required|in:buy,sell',
            'order_type' => 'required|in:limit,market',
            'quantity' => 'required|numeric|min:1',
            'limit_price' => 'nullable|numeric|min:0',
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

        StockOrderIntent::create([
            'user_id' => Auth::id(),
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

        $this->swalSuccess('Intention d’ordre enregistrée. Une SGI agréée vous recontactera pour l’exécution — Africaine des Finances n’exécute pas les ordres.');
        $this->reset('notes');
    }

    public function render(MarketsDataService $markets)
    {
        $stocks = $markets->stocks();
        $stock = $markets->stockBySymbol($this->symbol);
        $book = $stocks->sortByDesc('volume')->take(8)->values();

        $myIntents = Auth::check()
            ? StockOrderIntent::with('stock')->where('user_id', Auth::id())->latest()->limit(8)->get()
            : collect();

        return view('livewire.pages.marches.carnet-ordres', compact('stocks', 'stock', 'book', 'myIntents'))
            ->extends('layouts.site', ['title' => 'Carnet d’Ordres Direct — Africaine des Finances'])
            ->section('content');
    }
}
