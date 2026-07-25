<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class RechercheMarches extends Component
{
    #[Url(as: 'q', except: '')]
    public string $q = '';

    public function render(MarketsDataService $markets)
    {
        $results = $markets->search($this->q);

        return view('livewire.pages.marches.recherche', [
            'stocks' => $results['stocks'],
            'bonds' => $results['bonds'],
            'popular' => $markets->topVolume(6),
            'hasQuery' => trim($this->q) !== '',
        ])
            ->extends('layouts.site', ['title' => 'Recherche Marchés'])
            ->section('content');
    }
}
