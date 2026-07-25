<?php

namespace App\Livewire\Pages\Marches;

use App\Models\StockPrice;
use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class AnalyseGraphiquePro extends Component
{
    #[Url(as: 'symbol', except: '')]
    public string $symbol = '';

    public string $indicator = 'rsi';
    public string $range = '90';

    public function mount(MarketsDataService $markets, ?string $symbol = null): void
    {
        if ($symbol) {
            $this->symbol = strtoupper($symbol);
        } elseif ($this->symbol === '') {
            $this->symbol = $markets->topVolume(1)->first()?->symbol ?? 'SNTS';
        }
    }

    public function render(MarketsDataService $markets)
    {
        $stocks = $markets->stocks();
        $stock = $markets->stockBySymbol($this->symbol);
        $history = collect();
        if ($stock) {
            $history = StockPrice::where('stock_id', $stock->id)
                ->orderByDesc('recorded_at')
                ->limit((int) $this->range)
                ->get()
                ->sortBy('recorded_at')
                ->values()
                ->map(function ($row) {
                    $row->value = (float) $row->price;

                    return $row;
                });
        }
        $bars = $markets->chartBarsFromHistory($history, (int) min(24, max(8, (int) $this->range / 4)));
        $favorites = $markets->topVolume(6);
        $book = $markets->topVolume(8);

        // Simple RSI proxy from last closes
        $closes = $history->pluck('price')->filter()->map(fn ($v) => (float) $v)->values();
        $rsi = $this->approxRsi($closes);

        return view('livewire.pages.marches.analyse-graphique-pro', compact(
            'stocks', 'stock', 'bars', 'favorites', 'book', 'rsi', 'history'
        ))
            ->extends('layouts.site', ['title' => 'Analyse Graphique Pro — Africaine des Finances'])
            ->section('content');
    }

    private function approxRsi($closes): ?float
    {
        if ($closes->count() < 15) {
            return null;
        }
        $gains = 0.0;
        $losses = 0.0;
        for ($i = $closes->count() - 14; $i < $closes->count(); $i++) {
            $diff = $closes[$i] - $closes[$i - 1];
            if ($diff >= 0) {
                $gains += $diff;
            } else {
                $losses += abs($diff);
            }
        }
        if ($losses == 0.0) {
            return 100.0;
        }
        $rs = $gains / $losses;

        return round(100 - (100 / (1 + $rs)), 1);
    }
}
