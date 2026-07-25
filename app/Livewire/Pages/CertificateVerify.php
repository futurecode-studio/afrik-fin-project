<?php

namespace App\Livewire\Pages;

use App\Services\CertificateService;
use Livewire\Component;

class CertificateVerify extends Component
{
    public string $number = '';

    public ?array $result = null;

    public function mount(?string $number = null): void
    {
        if ($number) {
            $this->number = $number;
            $this->check();
        }
    }

    public function check(): void
    {
        $this->validate(['number' => 'required|string|min:5|max:80']);
        $this->result = app(CertificateService::class)->verifyCertificate(trim($this->number));
    }

    public function render()
    {
        return view('livewire.pages.certificate-verify')
            ->extends('layouts.site', ['title' => 'Vérifier un certificat'])
            ->section('content');
    }
}
