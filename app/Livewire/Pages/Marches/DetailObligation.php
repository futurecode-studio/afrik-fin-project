<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Component;

class DetailObligation extends Component
{
    public int $bondId;

    public function mount(int $id): void
    {
        $this->bondId = $id;
    }

    public function render(MarketsDataService $markets)
    {
        $bond = $markets->bondById($this->bondId);
        abort_unless($bond, 404);

        $similar = $markets->bonds()
            ->where('id', '!=', $bond->id)
            ->where('country', $bond->country)
            ->take(4)
            ->values();

        return view('livewire.pages.marches.detail-obligation', [
            'bond' => $bond,
            'similar' => $similar,
        ])
            ->extends('layouts.site', ['title' => $bond->name])
            ->section('content');
    }
}
