<?php

namespace App\Livewire\Client;

use App\Livewire\Client\Concerns\EnsuresFormationAccess;
use App\Models\Formation;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormationCompleted extends Component
{
    use EnsuresFormationAccess;

    public Formation $formation;

    public $enrollment;

    public function mount(string $slug): void
    {
        $this->formation = Formation::where('slug', $slug)
            ->with(['modules.quiz', 'modules.lessons'])
            ->firstOrFail();
        $this->enrollment = $this->enrollmentFor($this->formation);
    }

    public function render()
    {
        $moduleSummaries = $this->formation->modules->map(function ($module) {
            $quiz = $module->quiz;
            $best = null;
            if ($quiz) {
                $best = UserQuizAttempt::where('user_id', Auth::id())
                    ->where('module_quiz_id', $quiz->id)
                    ->whereNotNull('completed_at')
                    ->orderByDesc('score')
                    ->first();
            }

            return [
                'title' => $module->titre,
                'lessons' => $module->lessons->count(),
                'quiz_score' => $best?->score,
                'quiz_passed' => $best?->is_passed,
            ];
        });

        return view('livewire.client.formation-completed', [
            'moduleSummaries' => $moduleSummaries,
        ])
            ->extends('layouts.client', ['title' => 'Formation terminée'])
            ->section('content');
    }
}
