<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.pages.dashboard')
            ->extends('layouts.admin', ['title' => 'Dashboard'])
            ->section('content');
    }
}
