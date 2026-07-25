<?php

namespace App\Livewire\Client;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CertificateShow extends Component
{
    public Enrollment $enrollment;

    public function mount(Enrollment $enrollment): void
    {
        abort_unless($enrollment->user_id === Auth::id() && $enrollment->certificate_number, 404);
        $this->enrollment = $enrollment->load('formation.user');
    }

    public function render()
    {
        return view('livewire.client.certificate-show')
            ->extends('layouts.client', ['title' => 'Votre Certificat'])
            ->section('content');
    }
}
