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
        $map = $markets->marketMap($this->metric);

        return view('livewire.pages.marches.carte-marche', [
            'map' => $map,
        ])
            ->extends('layouts.site', ['title' => 'Carte du Marché — Africaine des Finances'])
            ->section('content');
    }
}
