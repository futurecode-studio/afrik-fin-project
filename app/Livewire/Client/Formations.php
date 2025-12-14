<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Formations extends Component
{
    public $enrollments;

    public function mount()
    {
        $this->enrollments = Auth::user()->enrollments()
            ->with('formation.modules.lessons')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.client.formations')
            ->extends('layouts.client', ['title' => 'Mes Formations'])
            ->section('content');
    }
}
