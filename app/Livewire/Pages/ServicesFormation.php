<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ServicesFormation extends Component
{
    public function render()
    {
        return view('livewire.pages.services-formation')
            ->extends('layouts.site', ['title' => 'Services Formation'])
            ->section('content');
    }
}
