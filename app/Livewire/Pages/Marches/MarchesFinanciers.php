<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Component;

class MarchesFinanciers extends Component
{
    public function render(MarketsDataService $markets)
    {
        $composite = $markets->indexLatest('BRVM Composite');
        $brvm30 = $markets->indexLatest('BRVM 30');
        $history = $markets->indexHistory('BRVM Composite', 30);

        return view('livewire.pages.marches.marches-financiers', [
            'stocks' => $markets->stocks(),
            'gainers' => $markets->topGainers(5),
            'losers' => $markets->topLosers(5),
            'byVolume' => $markets->topVolume(5),
            'composite' => $composite,
            'brvm30' => $brvm30,
            'chartBars' => $markets->chartBarsFromHistory($history),
            'totalVolume' => $markets->totalVolume(),
            'totalMarketCap' => $markets->totalMarketCap(),
        ])
            ->extends('layouts.site', ['title' => 'Marchés Financiers'])
            ->section('content');
    }
}
