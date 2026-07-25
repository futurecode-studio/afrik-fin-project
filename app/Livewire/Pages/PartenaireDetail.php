<?php

namespace App\Livewire\Pages;

use App\Models\Partner;
use Livewire\Component;

class PartenaireDetail extends Component
{
    public Partner $partner;

    public function mount($id)
    {
        $this->partner = Partner::query()->where('is_active', true)->findOrFail($id);
    }

    public function render()
    {
        $related = Partner::active()
            ->where('type', $this->partner->type)
            ->where('id', '!=', $this->partner->id)
            ->limit(4)
            ->get();

        return view('livewire.pages.partenaire-detail', [
            'related' => $related,
        ])
            ->extends('layouts.site', ['title' => $this->partner->nom.' — Fiche partenaire'])
            ->section('content');
    }
}
