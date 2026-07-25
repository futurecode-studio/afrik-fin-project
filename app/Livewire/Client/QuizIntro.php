<?php

namespace App\Livewire\Client;

use App\Livewire\Client\Concerns\EnsuresFormationAccess;
use App\Models\Formation;
use App\Models\ModuleQuiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class QuizIntro extends Component
{
    use WithSweetAlert;
    use EnsuresFormationAccess;

    public Formation $formation;

    public ModuleQuiz $quiz;

    public $enrollment;

    public function mount(string $slug, ModuleQuiz $quiz): void
    {
        $this->formation = Formation::where('slug', $slug)->firstOrFail();
        $this->enrollment = $this->enrollmentFor($this->formation);
        $this->quiz = $this->quizForFormation($this->formation, $quiz->id);
    }

    public function start()
    {
        if (! $this->quiz->canAttempt(Auth::id())) {
            $this->swalError('Nombre maximum de tentatives atteint.');

            return;
        }

        return $this->redirect(route('client.quiz.take', [
            'slug' => $this->formation->slug,
            'quiz' => $this->quiz->id,
        ]), navigate: false);
    }

    public function render()
    {
        $attempts = $this->quiz->attemptsCountByUser(Auth::id());

        return view('livewire.client.quiz-intro', [
            'attempts' => $attempts,
            'remaining' => max(0, (int) $this->quiz->tentatives_max - $attempts),
            'quiz' => $this->quiz,
            'formation' => $this->formation,
        ])
            ->extends('layouts.course', ['title' => $this->quiz->titre, 'formation' => $this->formation])
            ->section('content');
    }
}
