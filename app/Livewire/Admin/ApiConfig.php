<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ApiConfig extends Component
{
    public function render()
    {
        return view('livewire.admin.api-config')
            ->extends('layouts.admin', ['title' => 'Configuration API'])
            ->section('content');
    }
}
