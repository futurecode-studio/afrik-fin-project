<?php

namespace App\Livewire\Pages;

use App\Models\Partner;
use App\Models\SgiAccountRequest;
use App\Models\SgiRequiredDocument;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class MiseEnRelation extends Component
{
    #[Url(as: 'partner', except: '')]
    public string $partner = '';

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public bool $submitted = false;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->name = Auth::user()->name ?? '';
            $this->email = Auth::user()->email ?? '';
            $this->phone = Auth::user()->phone ?? '';
        }
    }

    public function submit(): void
    {
        $this->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:30',
            'partner' => 'nullable|exists:partners,id',
            'message' => 'nullable|string|max:1200',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L’email est obligatoire.',
            'phone.required' => 'Le téléphone est obligatoire.',
        ]);

        $selectedPartner = $this->selectedPartner();

        SgiAccountRequest::create([
            'user_id' => Auth::id(),
            'name' => trim($this->name),
            'email' => trim($this->email),
            'phone' => trim($this->phone),
            'message' => trim($this->message),
            'source' => 'public',
            'status' => 'pending',
            'admin_notes' => $selectedPartner
                ? 'Partenaire souhaité : '.$selectedPartner->nom
                : null,
        ]);

        $this->submitted = true;
    }

    public function selectedPartner(): ?Partner
    {
        if ($this->partner === '') {
            return null;
        }

        return Partner::active()->whereKey($this->partner)->first();
    }

    public function render()
    {
        return view('livewire.pages.mise-en-relation', [
            'partners' => Partner::active()->whereIn('type', ['SGI', 'SGO'])->orderBy('type')->orderBy('nom')->get(),
            'selectedPartner' => $this->selectedPartner(),
            'requiredDocs' => SgiRequiredDocument::active()->get(),
        ])
            ->extends('layouts.site', ['title' => 'Mise en relation SGI / SGO'])
            ->section('content');
    }
}
