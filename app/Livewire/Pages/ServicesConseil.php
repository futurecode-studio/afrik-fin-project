<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ServicesConseil extends Component
{
    public function render()
    {
        return view('livewire.pages.services-conseil')
            ->extends('layouts.site', ['title' => 'Services Conseil'])
            ->section('content');
    }
}
