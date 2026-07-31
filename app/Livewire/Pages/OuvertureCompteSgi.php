<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\SgiAccountRequest;
use App\Models\SgiRequiredDocument;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OuvertureCompteSgi extends Component
{
    use WithSweetAlert;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public bool $submitted = false;

    public function mount(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name ?? '';
            $this->email = $user->email ?? '';
            $this->phone = $user->phone ?? '';
        }
    }

    public function submit(): void
    {
        $this->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:40',
            'message' => 'nullable|string|max:2000',
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L’email est requis.',
            'email.email' => 'Adresse email invalide.',
            'phone.required' => 'Le téléphone est requis.',
            'phone.min' => 'Le numéro de téléphone semble trop court.',
        ]);

        $existing = SgiAccountRequest::query()
            ->whereIn('status', ['pending', 'contacted', 'in_progress'])
            ->where(function ($q) {
                $q->where('email', $this->email);
                if (Auth::check()) {
                    $q->orWhere('user_id', Auth::id());
                }
            })
            ->latest()
            ->first();

        if ($existing) {
            $this->submitted = true;
            $this->swalInfo('Votre demande est déjà enregistrée. Une chargée de clientèle vous recontactera.');

            return;
        }

        SgiAccountRequest::create([
            'user_id' => Auth::id(),
            'name' => trim($this->name),
            'email' => trim($this->email),
            'phone' => trim($this->phone),
            'message' => trim($this->message) !== '' ? trim($this->message) : null,
            'source' => 'public',
            'status' => 'pending',
        ]);

        $this->submitted = true;
        $this->swalSuccess('Demande enregistrée. Une chargée de clientèle vous contactera prochainement.');
    }

    public function render()
    {
        return view('livewire.pages.ouverture-compte-sgi', [
            'documents' => SgiRequiredDocument::active()->get(),
        ])
            ->extends('layouts.site', ['title' => 'Ouvrir un compte titre — Africaine des Finances'])
            ->section('content');
    }
}
