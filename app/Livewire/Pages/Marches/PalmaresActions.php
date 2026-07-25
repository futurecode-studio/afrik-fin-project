<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class PalmaresActions extends Component
{
    #[Url(as: 'onglet', except: 'gainers')]
    public string $tab = 'gainers'; // gainers|losers|volume

    public function render(MarketsDataService $markets)
    {
        $list = match ($this->tab) {
            'losers' => $markets->topLosers(15),
            'volume' => $markets->topVolume(15),
            default => $markets->topGainers(15),
        };

        return view('livewire.pages.marches.palmares-actions', [
            'list' => $list,
            'gainers' => $markets->topGainers(3),
            'losers' => $markets->topLosers(3),
            'byVolume' => $markets->topVolume(3),
        ])
            ->extends('layouts.site', ['title' => 'Palmarès Actions'])
            ->section('content');
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['gainers', 'losers', 'volume'], true) ? $tab : 'gainers';
    }
}
