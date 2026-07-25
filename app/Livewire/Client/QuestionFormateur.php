<?php

namespace App\Livewire\Client;

use App\Models\Formation;
use App\Models\InstructorQuestion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class QuestionFormateur extends Component
{
    use WithSweetAlert;
    public string $formationId = '';

    public string $subject = '';

    public string $body = '';

    public function mount(): void
    {
        $slug = request()->query('formation');
        if ($slug) {
            $formation = Formation::where('slug', $slug)->first();
            if ($formation && Auth::user()->enrollments()
                ->where('formation_id', $formation->id)
                ->whereIn('status', ['active', 'completed'])
                ->exists()) {
                $this->formationId = (string) $formation->id;

                return;
            }
        }

        $first = Auth::user()->enrollments()
            ->whereIn('status', ['active', 'completed'])
            ->with('formation')
            ->first();
        $this->formationId = (string) ($first?->formation_id ?? '');
    }

    public function submit(): void
    {
        $this->validate([
            'formationId' => 'required|exists:formations,id',
            'subject' => 'required|string|min:3|max:200',
            'body' => 'required|string|min:10|max:5000',
        ]);

        abort_unless(
            Auth::user()->enrollments()->where('formation_id', $this->formationId)->whereIn('status', ['active', 'completed'])->exists(),
            403
        );

        InstructorQuestion::create([
            'user_id' => Auth::id(),
            'formation_id' => $this->formationId,
            'subject' => $this->subject,
            'body' => $this->body,
            'status' => 'open',
        ]);

        $this->reset('subject', 'body');
        $this->swalSuccess('Question envoyée au formateur.');
    }

    public function render()
    {
        $formations = Formation::whereIn(
            'id',
            Auth::user()->enrollments()->whereIn('status', ['active', 'completed'])->pluck('formation_id')
        )->orderBy('titre')->get();

        $mine = InstructorQuestion::with('formation')
            ->where('user_id', Auth::id())
            ->latest()
            ->limit(20)
            ->get();

        return view('livewire.client.question-formateur', compact('formations', 'mine'))
            ->extends('layouts.client', ['title' => 'Poser une question'])
            ->section('content');
    }
}
