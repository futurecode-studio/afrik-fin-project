<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Newsletters extends Component
{
    public function render()
    {
        return view('livewire.admin.newsletters')
            ->extends('layouts.admin', ['title' => 'Newsletters'])
            ->section('content');
    }
}
