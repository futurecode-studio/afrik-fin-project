<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Appointments extends Component
{
    public function render()
    {
        return view('livewire.admin.appointments')
            ->extends('layouts.admin', ['title' => 'Rendez-vous'])
            ->section('content');
    }
}
