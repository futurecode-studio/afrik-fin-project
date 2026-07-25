<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ServicesConseil extends Component
{
    public function mount()
    {
        return $this->redirect(route('service-detail', 'conseil-financier'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.services-conseil')
            ->extends('layouts.site', ['title' => 'Conseil'])
            ->section('content');
    }
}
