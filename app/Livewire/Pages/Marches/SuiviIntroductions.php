<?php

namespace App\Livewire\Pages\Marches;

use App\Models\MarketIpo;
use Livewire\Component;

class SuiviIntroductions extends Component
{
    public string $status = '';

    public function render()
    {
        $ipos = MarketIpo::published()
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->orderByDesc('subscription_start')
            ->orderByDesc('id')
            ->get();

        return view('livewire.pages.marches.suivi-introductions', compact('ipos'))
            ->extends('layouts.site', ['title' => 'Suivi d’Introduction en Bourse — Africaine des Finances'])
            ->section('content');
    }
}
