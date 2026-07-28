<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class CarteMarche extends Component
{
    #[Url(as: 'metric', except: 'market_cap')]
    public string $metric = 'market_cap';

    public function render(MarketsDataService $markets)
    {
        $metric = in_array($this->metric, ['market_cap', 'volume', 'variation'], true)
            ? $this->metric
            : 'market_cap';

        $treemap = $markets->marketTreemap($metric);
        $map = $markets->marketMap($metric === 'variation' ? 'variation' : ($metric === 'volume' ? 'volume' : 'market_cap'));

        return view('livewire.pages.marches.carte-marche', [
            'treemap' => $treemap,
            'sectors' => $map['sectors'] ?? [],
            'mapTotal' => (float) ($map['total'] ?? 0),
            'metric' => $metric,
        ])
            ->extends('layouts.site', ['title' => 'Heatmap BRVM — Africaine des Finances'])
            ->section('content');
    }
}
