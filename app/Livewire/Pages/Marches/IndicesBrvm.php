<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Component;

class IndicesBrvm extends Component
{
    public function render(MarketsDataService $markets)
    {
        $names = $markets->indexNames();
        $indices = $names->mapWithKeys(function ($name) use ($markets) {
            return [$name => [
                'latest' => $markets->indexLatest($name),
                'history' => $markets->indexHistory($name, 30),
                'bars' => $markets->chartBarsFromHistory($markets->indexHistory($name, 30)),
            ]];
        });

        // Proxies sectoriels depuis les stocks (moyenne de variation)
        $sectorStats = $markets->stocks()
            ->groupBy('sector')
            ->map(function ($group, $sector) {
                return [
                    'sector' => $sector ?: 'Autre',
                    'count' => $group->count(),
                    'avg_variation' => round((float) $group->avg('variation_percent'), 2),
                    'volume' => (int) $group->sum('volume'),
                    'top' => $group->sortByDesc('variation_percent')->first(),
                ];
            })
            ->sortByDesc('avg_variation')
            ->values();

        return view('livewire.pages.marches.indices-brvm', [
            'indices' => $indices,
            'sectorStats' => $sectorStats,
        ])
            ->extends('layouts.site', ['title' => 'Indices BRVM'])
            ->section('content');
    }
}
