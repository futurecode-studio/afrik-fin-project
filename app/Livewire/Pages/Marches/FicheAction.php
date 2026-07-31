<?php

namespace App\Livewire\Pages\Marches;

use App\Models\StockWatchlist;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class FicheAction extends Component
{
    use WithSweetAlert;
    public string $symbol;

    public function mount(string $symbol): void
    {
        $this->symbol = strtoupper($symbol);
    }

    public function toggleWatchlist(MarketsDataService $markets): void
    {
        if (! Auth::check() || ! Auth::user()->isClient()) {
            $this->redirect(route('connexion'), navigate: true);

            return;
        }

        $stock = $markets->stockBySymbol($this->symbol);
        if (! $stock) {
            return;
        }

        $existing = StockWatchlist::where('user_id', Auth::id())->where('stock_id', $stock->id)->first();
        if ($existing) {
            $existing->delete();
            $this->swalSuccess('Retiré de votre liste de suivi.');
        } else {
            StockWatchlist::create([
                'user_id' => Auth::id(),
                'stock_id' => $stock->id,
                'position' => (int) StockWatchlist::where('user_id', Auth::id())->max('position') + 1,
            ]);
            $this->swalSuccess('Ajouté à votre liste de suivi.');
        }
    }

    public function render(MarketsDataService $markets)
    {
        $stock = $markets->stockBySymbol($this->symbol);

        abort_unless($stock, 404);

        $stock = $markets->enrichStockSessionLevels($stock);

        $peers = $markets->stocks()
            ->where('sector', $stock->sector)
            ->where('symbol', '!=', $stock->symbol)
            ->take(5)
            ->values();

        $watched = Auth::check()
            ? StockWatchlist::where('user_id', Auth::id())->where('stock_id', $stock->id)->exists()
            : false;

        return view('livewire.pages.marches.fiche-action', [
            'stock' => $stock,
            'peers' => $peers,
            'gainers' => $markets->topGainers(3),
            'watched' => $watched,
        ])
            ->extends('layouts.site', ['title' => $stock->symbol.' — Fiche Action'])
            ->section('content');
    }
}
