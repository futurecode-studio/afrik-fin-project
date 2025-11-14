<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Statistics extends Component
{
    public function render()
    {
        return view('livewire.admin.statistics')
            ->extends('layouts.admin', ['title' => 'Statistiques'])
            ->section('content');
    }
}
