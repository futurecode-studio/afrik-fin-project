<?php

namespace App\Livewire\Client;

use App\Models\Article;
use App\Models\PortfolioHolding;
use App\Models\StockWatchlist;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ActualitesPortefeuille extends Component
{
    public string $filter = 'portefeuille'; // portefeuille, watchlist, all

    public function render(MarketsDataService $markets)
    {
        $holdings = PortfolioHolding::with('stock')->where('user_id', Auth::id())->get();
        $watch = StockWatchlist::with('stock')->where('user_id', Auth::id())->get();

        $symbols = collect();
        if ($this->filter === 'portefeuille') {
            $symbols = $holdings->pluck('stock.symbol')->filter();
        } elseif ($this->filter === 'watchlist') {
            $symbols = $watch->pluck('stock.symbol')->filter();
        } else {
            $symbols = $holdings->pluck('stock.symbol')->merge($watch->pluck('stock.symbol'))->filter()->unique();
        }

        $sectors = $holdings->pluck('stock.sector')->merge($watch->pluck('stock.sector'))->filter()->unique();

        $articles = Article::published()
            ->with('user')
            ->latest('published_at')
            ->limit(40)
            ->get()
            ->filter(function (Article $a) use ($symbols, $sectors) {
                if ($symbols->isEmpty() && $sectors->isEmpty()) {
                    return true;
                }
                $hay = mb_strtolower(($a->titre ?? '').' '.($a->extrait ?? '').' '.($a->categorie ?? ''));
                foreach ($symbols as $sym) {
                    if ($sym && str_contains($hay, mb_strtolower($sym))) {
                        return true;
                    }
                }
                foreach ($sectors as $sec) {
                    if ($sec && str_contains($hay, mb_strtolower($sec))) {
                        return true;
                    }
                }

                // fallback: same category keywords
                return in_array(mb_strtolower((string) $a->categorie), ['bourse', 'marche', 'marchés', 'analyse', 'finance'], true);
            })
            ->take(12)
            ->values();

        $tickers = $markets->topVolume(6);
        $totalValue = $holdings->sum(function ($h) {
            $price = $h->stock?->current_price ?? $h->avg_cost ?? 0;

            return (float) $h->quantity * (float) $price;
        });

        return view('livewire.client.actualites-portefeuille', compact('articles', 'holdings', 'tickers', 'totalValue'))
            ->extends('layouts.client', ['title' => 'Actualités de mon Portefeuille'])
            ->section('content');
    }
}
