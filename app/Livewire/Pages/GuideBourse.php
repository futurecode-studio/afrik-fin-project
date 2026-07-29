<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class GuideBourse extends Component
{
    public function render()
    {
        return view('livewire.pages.guide-bourse')
            ->extends('layouts.site', [
                'title' => 'Guide Complet de la Bourse BRVM — Africaine des Finances',
            ])
            ->section('content');
    }
}
