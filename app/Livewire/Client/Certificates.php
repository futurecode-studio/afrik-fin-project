<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Certificates extends Component
{
    public $certificates;

    public function mount()
    {
        $this->certificates = Auth::user()->enrollments()
            ->with('formation')
            ->whereNotNull('certificate_number')
            ->orderBy('certificate_issued_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.client.certificates')
            ->extends('layouts.client', ['title' => 'Mes Certificats'])
            ->section('content');
    }
}
