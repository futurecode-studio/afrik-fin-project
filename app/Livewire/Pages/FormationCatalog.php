<?php

namespace App\Livewire\Pages;

use App\Models\Formation;
use Livewire\Component;

class FormationCatalog extends Component
{
    public Formation $formation;

    public function mount(string $slug): void
    {
        $this->formation = Formation::query()
            ->where('slug', $slug)
            ->publie()
            ->with(['activeCatalogItems'])
            ->firstOrFail();
    }

    public function render()
    {
        $items = $this->formation->activeCatalogItems;

        return view('livewire.pages.formation-catalog', [
            'items' => $items,
        ])
            ->extends('layouts.site', ['title' => 'Catalogue — '.$this->formation->titre])
            ->section('content');
    }
}
