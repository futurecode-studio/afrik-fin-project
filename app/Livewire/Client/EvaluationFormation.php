<?php

namespace App\Livewire\Client;

use App\Models\Formation;
use App\Models\FormationReview;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class EvaluationFormation extends Component
{
    use WithSweetAlert;
    public Formation $formation;

    public int $rating_overall = 5;

    public int $rating_content = 5;

    public int $rating_instructor = 5;

    public int $rating_difficulty = 3;

    public int $rating_materials = 5;

    public string $comment = '';

    public function mount(string $slug): void
    {
        $this->formation = Formation::where('slug', $slug)->firstOrFail();
        abort_unless(
            Auth::user()->enrollments()->where('formation_id', $this->formation->id)->whereIn('status', ['active', 'completed'])->exists(),
            403
        );

        $existing = FormationReview::where('user_id', Auth::id())->where('formation_id', $this->formation->id)->first();
        if ($existing) {
            $this->rating_overall = $existing->rating_overall;
            $this->rating_content = $existing->rating_content ?? 5;
            $this->rating_instructor = $existing->rating_instructor ?? 5;
            $this->rating_difficulty = $existing->rating_difficulty ?? 3;
            $this->rating_materials = $existing->rating_materials ?? 5;
            $this->comment = $existing->comment ?? '';
        }
    }

    public function submit(): void
    {
        $this->validate([
            'rating_overall' => 'required|integer|min:1|max:5',
            'rating_content' => 'required|integer|min:1|max:5',
            'rating_instructor' => 'required|integer|min:1|max:5',
            'rating_difficulty' => 'required|integer|min:1|max:5',
            'rating_materials' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:3000',
        ]);

        FormationReview::updateOrCreate(
            ['user_id' => Auth::id(), 'formation_id' => $this->formation->id],
            [
                'rating_overall' => $this->rating_overall,
                'rating_content' => $this->rating_content,
                'rating_instructor' => $this->rating_instructor,
                'rating_difficulty' => $this->rating_difficulty,
                'rating_materials' => $this->rating_materials,
                'comment' => $this->comment ?: null,
            ]
        );

        $this->swalSuccess('Merci pour votre évaluation.');
    }

    public function render()
    {
        return view('livewire.client.evaluation-formation')
            ->extends('layouts.client', ['title' => 'Évaluation — '.$this->formation->titre])
            ->section('content');
    }
}
