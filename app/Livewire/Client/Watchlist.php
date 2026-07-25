<?php

namespace App\Livewire\Client;

use App\Models\Stock;
use App\Models\StockWatchlist;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class Watchlist extends Component
{
    use WithSweetAlert;
    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function add(int $stockId): void
    {
        $exists = Stock::whereKey($stockId)->where('is_active', true)->exists();
        if (! $exists) {
            return;
        }

        StockWatchlist::firstOrCreate(
            ['user_id' => Auth::id(), 'stock_id' => $stockId],
            ['position' => (int) StockWatchlist::where('user_id', Auth::id())->max('position') + 1]
        );

        $this->swalSuccess('Titre ajouté à votre liste de suivi.');
    }

    public function remove(int $stockId): void
    {
        StockWatchlist::where('user_id', Auth::id())->where('stock_id', $stockId)->delete();
        $this->swalSuccess('Titre retiré de la liste.');
    }

    public function render(MarketsDataService $markets)
    {
        $items = Auth::user()->watchlistItems()->with('stock')->get();
        $watchedIds = $items->pluck('stock_id')->all();

        $suggestions = $markets->stocks()
            ->when($this->search !== '', function ($col) {
                $n = mb_strtolower($this->search);

                return $col->filter(fn ($s) =>
                    str_contains(mb_strtolower($s->symbol), $n)
                    || str_contains(mb_strtolower((string) $s->company_name), $n)
                );
            })
            ->reject(fn ($s) => in_array($s->id, $watchedIds, true))
            ->take(12)
            ->values();

        return view('livewire.client.watchlist', [
            'items' => $items,
            'suggestions' => $suggestions,
        ])
            ->extends('layouts.client', ['title' => 'Ma Liste de Suivi'])
            ->section('content');
    }
}
