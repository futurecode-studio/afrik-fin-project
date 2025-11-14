<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class StockData extends Component
{
    public function render()
    {
        return view('livewire.admin.stock-data')
            ->extends('layouts.admin', ['title' => 'Données Boursières'])
            ->section('content');
    }
}
