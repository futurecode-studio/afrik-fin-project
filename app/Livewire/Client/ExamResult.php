<?php

namespace App\Livewire\Client;

use App\Livewire\Client\Concerns\EnsuresFormationAccess;
use App\Models\Formation;
use App\Models\ModuleQuiz;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExamResult extends Component
{
    use EnsuresFormationAccess;

    public Formation $formation;

    public ModuleQuiz $quiz;

    public UserQuizAttempt $attempt;

    public $enrollment;

    public function mount(string $slug, ModuleQuiz $quiz, UserQuizAttempt $attempt): void
    {
        $this->formation = Formation::where('slug', $slug)->firstOrFail();
        $this->enrollment = $this->enrollmentFor($this->formation);
        $this->quiz = $this->quizForFormation($this->formation, $quiz->id);
        abort_unless($this->quiz->is_final, 404);
        abort_unless($attempt->user_id === Auth::id() && $attempt->module_quiz_id === $this->quiz->id, 404);
        $this->attempt = $attempt;
    }

    public function render()
    {
        $modules = $this->formation->modules()->with('lessons')->orderBy('ordre')->get();
        $completed = collect($this->enrollment->completed_lessons ?? []);

        $recommended = $modules->filter(function ($module) use ($completed) {
            $lessonIds = $module->lessons->pluck('id');
            if ($lessonIds->isEmpty()) {
                return false;
            }
            $done = $lessonIds->filter(fn ($id) => $completed->contains($id))->count();

            return $done < $lessonIds->count();
        })->values();

        if ($recommended->isEmpty()) {
            $recommended = $modules->take(3);
        }

        return view('livewire.client.exam-result', [
            'modules' => $modules,
            'recommended' => $recommended,
            'quiz' => $this->quiz,
            'attempt' => $this->attempt,
            'formation' => $this->formation,
        ])
            ->extends('layouts.client', ['title' => 'Résultat de l\'examen'])
            ->section('content');
    }
}
