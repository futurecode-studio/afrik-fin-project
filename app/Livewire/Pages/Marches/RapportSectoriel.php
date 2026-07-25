<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class RapportSectoriel extends Component
{
    #[Url(except: '')]
    public string $sector = '';

    public function render(MarketsDataService $markets)
    {
        $report = collect($markets->sectorReport());
        $selected = $this->sector !== ''
            ? $report->firstWhere('name', $this->sector)
            : $report->first();

        return view('livewire.pages.marches.rapport-sectoriel', [
            'report' => $report,
            'selected' => $selected,
        ])
            ->extends('layouts.site', ['title' => 'Rapport d’Analyse Sectorielle — Africaine des Finances'])
            ->section('content');
    }
}
