<?php

namespace App\Livewire\Pages\Investir;

use App\Services\MarketsDataService;
use App\Services\MutualFundsApiService;
use Livewire\Component;

class InvestirHub extends Component
{
    public function render(MarketsDataService $markets, MutualFundsApiService $funds)
    {
        $stocks = $markets->stocks();
        $opcvm = cache()->remember('investir.hub.funds_count', 300, function () use ($funds) {
            try {
                return count($funds->getMutualFunds());
            } catch (\Throwable) {
                return 0;
            }
        });

        return view('livewire.pages.investir.hub', [
            'stockCount' => $stocks->count(),
            'bondCount' => $markets->bonds()->count(),
            'fundCount' => $opcvm,
            'gainers' => $markets->topGainers(3),
            'index' => $markets->indexLatest('BRVM Composite'),
        ])
            ->extends('layouts.site', ['title' => 'Investir — Africaine des Finances'])
            ->section('content');
    }
}
