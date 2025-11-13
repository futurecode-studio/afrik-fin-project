<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Actualites extends Component
{
    public function render()
    {
        return view('livewire.pages.actualites')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
