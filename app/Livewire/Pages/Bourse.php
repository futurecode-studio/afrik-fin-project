<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Bourse extends Component
{
    public function render()
    {
        return view('livewire.pages.bourse')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
