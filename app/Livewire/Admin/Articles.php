<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Articles extends Component
{
    public function render()
    {
        return view('livewire.admin.articles')
            ->extends('layouts.admin', ['title' => 'Articles & Actualités'])
            ->section('content');
    }
}
