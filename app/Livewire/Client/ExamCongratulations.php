<?php

namespace App\Livewire\Client;

use App\Livewire\Client\Concerns\EnsuresFormationAccess;
use App\Models\Formation;
use App\Models\ModuleQuiz;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExamCongratulations extends Component
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
        abort_unless(
            $attempt->user_id === Auth::id()
            && $attempt->module_quiz_id === $this->quiz->id
            && $attempt->is_passed,
            404
        );
        $this->attempt = $attempt;
        $this->enrollment->refresh();
    }

    public function render()
    {
        $mention = match (true) {
            $this->attempt->score >= 90 => 'Mention Très Bien',
            $this->attempt->score >= 80 => 'Mention Bien',
            $this->attempt->score >= 70 => 'Mention Assez Bien',
            default => 'Réussi',
        };

        return view('livewire.client.exam-congratulations', [
            'mention' => $mention,
            'quiz' => $this->quiz,
            'attempt' => $this->attempt,
            'formation' => $this->formation,
            'enrollment' => $this->enrollment,
        ])
            ->extends('layouts.client', ['title' => 'Félicitations'])
            ->section('content');
    }
}
