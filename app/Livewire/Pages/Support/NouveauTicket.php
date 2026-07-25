<?php

namespace App\Livewire\Pages\Support;

use App\Models\Contact as ContactModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Concerns\WithSweetAlert;

class NouveauTicket extends Component
{
    use WithSweetAlert;
    use WithFileUploads;

    public string $subject = '';
    public string $category = '';
    public string $priority = 'normale';
    public string $description = '';
    public $attachment = null;

    protected function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'category' => 'required|in:technique,facturation,formation,securite,autre',
            'priority' => 'required|in:basse,normale,haute',
            'description' => 'required|string|min:20|max:5000',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,webp',
        ];
    }

    public function submit()
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }

        $this->validate();
        $user = Auth::user();
        $parts = preg_split('/\s+/', trim($user->name), 2);

        $message = "[Catégorie: {$this->category}] [Priorité: {$this->priority}]\n\n".$this->description;
        if ($this->attachment) {
            $path = $this->attachment->store('support-tickets', 'public');
            $message .= "\n\nPièce jointe: storage/{$path}";
        }

        ContactModel::create([
            'first_name' => $parts[0] ?? $user->name,
            'last_name' => $parts[1] ?? '-',
            'email' => $user->email,
            'phone' => $user->phone ?? '-',
            'subject' => $this->subject,
            'message' => $message,
            'status' => 'new',
        ]);

        $this->swalSuccess('Votre ticket a été créé. Notre équipe vous répondra rapidement.');
        $this->reset(['subject', 'category', 'priority', 'description', 'attachment']);
        $this->priority = 'normale';
    }

    public function render()
    {
        $recent = Auth::check()
            ? ContactModel::query()->where('email', Auth::user()->email)->latest()->limit(5)->get()
            : collect();

        return view('livewire.pages.support.nouveau-ticket', compact('recent'))
            ->extends('layouts.site', ['title' => 'Nouveau Ticket Support — Africaine des Finances'])
            ->section('content');
    }
}
