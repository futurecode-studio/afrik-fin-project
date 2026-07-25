<?php

namespace App\Livewire\Client;

use App\Livewire\Client\Concerns\EnsuresFormationAccess;
use App\Models\Formation;
use App\Models\ModuleQuiz;
use App\Services\QuizAttemptService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizTake extends Component
{
    use EnsuresFormationAccess;

    public Formation $formation;

    public ModuleQuiz $quiz;

    public $enrollment;

    public int $currentIndex = 0;

    /** @var array<int|string, mixed> */
    public array $answers = [];

    public string $startedAt = '';

    public function mount(string $slug, ModuleQuiz $quiz): void
    {
        $this->formation = Formation::where('slug', $slug)->firstOrFail();
        $this->enrollment = $this->enrollmentFor($this->formation);
        $this->quiz = $this->quizForFormation($this->formation, $quiz->id);

        abort_unless($this->quiz->canAttempt(Auth::id()), 403, 'Tentatives épuisées.');

        $this->startedAt = now()->toIso8601String();
        $this->quiz->load(['questions' => fn ($q) => $q->active()->ordered()->with('answers')]);
    }

    public function selectAnswer(int $questionId, int $answerId): void
    {
        $question = $this->quiz->questions->firstWhere('id', $questionId);
        if (! $question) {
            return;
        }

        if ($question->type === 'choix_multiple') {
            $current = $this->answers[$questionId] ?? [];
            if (! is_array($current)) {
                $current = [$current];
            }
            if (in_array($answerId, $current, true)) {
                $current = array_values(array_filter($current, fn ($id) => (int) $id !== $answerId));
            } else {
                $current[] = $answerId;
            }
            $this->answers[$questionId] = $current;
        } else {
            $this->answers[$questionId] = $answerId;
        }
    }

    public function next(): void
    {
        if ($this->currentIndex < $this->quiz->questions->count() - 1) {
            $this->currentIndex++;
        }
    }

    public function previous(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function skip(): void
    {
        $this->next();
    }

    public function submit(QuizAttemptService $service)
    {
        $started = $this->startedAt ? new \DateTimeImmutable($this->startedAt) : now()->subMinutes(1);
        $attempt = $service->complete($this->quiz, Auth::id(), $this->answers, $started);

        if ($this->quiz->is_final && $attempt->is_passed) {
            return $this->redirect(route('client.exam.congrats', [
                'slug' => $this->formation->slug,
                'quiz' => $this->quiz->id,
                'attempt' => $attempt->id,
            ]), navigate: false);
        }

        if ($this->quiz->is_final) {
            return $this->redirect(route('client.exam.result', [
                'slug' => $this->formation->slug,
                'quiz' => $this->quiz->id,
                'attempt' => $attempt->id,
            ]), navigate: false);
        }

        return $this->redirect(route('client.quiz.result', [
            'slug' => $this->formation->slug,
            'quiz' => $this->quiz->id,
            'attempt' => $attempt->id,
        ]), navigate: false);
    }

    public function render()
    {
        $questions = $this->quiz->questions;
        $question = $questions[$this->currentIndex] ?? null;
        $answered = collect($this->answers)->filter(fn ($v) => filled($v))->count();

        return view('livewire.client.quiz-take', [
            'questions' => $questions,
            'question' => $question,
            'total' => $questions->count(),
            'answered' => $answered,
            'quiz' => $this->quiz,
            'formation' => $this->formation,
        ])
            ->extends('layouts.course', ['title' => $this->quiz->titre, 'formation' => $this->formation])
            ->section('content');
    }
}
