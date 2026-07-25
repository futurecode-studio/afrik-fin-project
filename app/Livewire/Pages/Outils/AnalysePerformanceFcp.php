<?php

namespace App\Livewire\Pages\Outils;

use App\Services\MutualFundsApiService;
use Livewire\Attributes\Url;
use Livewire\Component;

class AnalysePerformanceFcp extends Component
{
    #[Url(as: 'id', except: '')]
    public string $fundId = '';

    public function render(MutualFundsApiService $funds)
    {
        $all = collect($funds->getMutualFunds());
        if ($this->fundId === '' && $all->isNotEmpty()) {
            $this->fundId = (string) ($all->first()['id'] ?? '');
        }

        $fund = $all->firstWhere('id', $this->fundId)
            ?? $all->first();

        return view('livewire.pages.outils.analyse-performance-fcp', [
            'funds' => $all,
            'fund' => $fund,
        ])
            ->extends('layouts.site', ['title' => 'Analyse Performance FCP — Africaine des Finances'])
            ->section('content');
    }
}
