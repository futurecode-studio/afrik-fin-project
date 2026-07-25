<?php

namespace App\Livewire\Client;

use App\Livewire\Client\Concerns\EnsuresFormationAccess;
use App\Models\Formation;
use App\Models\ModuleQuiz;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizResult extends Component
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
        abort_unless($attempt->user_id === Auth::id() && $attempt->module_quiz_id === $this->quiz->id, 404);
        $this->attempt = $attempt;
    }

    public function render()
    {
        $this->quiz->load(['questions.answers']);

        return view('livewire.client.quiz-result', [
            'quiz' => $this->quiz,
            'attempt' => $this->attempt,
            'formation' => $this->formation,
        ])
            ->extends('layouts.client', ['title' => 'Résultat du Quiz'])
            ->section('content');
    }
}
