<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Formations extends Component
{
    public function render()
    {
        return view('livewire.admin.formations')
            ->extends('layouts.admin', ['title' => 'Gestion des Formations'])
            ->section('content');
    }
}
