<?php

namespace App\Livewire\Admin;

use App\Models\ModuleQuiz;
use App\Models\UserQuizAttempt;
use Livewire\Component;

class QuizAnalytics extends Component
{
    public string $quizId = '';

    public function render()
    {
        $quizzes = ModuleQuiz::with('module.formation')->orderBy('titre')->get();

        $attempts = UserQuizAttempt::with(['user', 'quiz.questions.answers'])
            ->whereNotNull('completed_at')
            ->when($this->quizId !== '', fn ($q) => $q->where('module_quiz_id', $this->quizId))
            ->latest('completed_at')
            ->limit(100)
            ->get();

        $avgScore = $attempts->avg('score');
        $passRate = $attempts->count()
            ? round(($attempts->where('is_passed', true)->count() / $attempts->count()) * 100, 1)
            : 0;

        $questionStats = collect();
        $quiz = $this->quizId !== ''
            ? ModuleQuiz::with('questions.answers')->find($this->quizId)
            : ($quizzes->first() ? ModuleQuiz::with('questions.answers')->find($quizzes->first()->id) : null);

        if ($quiz) {
            $quizAttempts = $attempts->where('module_quiz_id', $quiz->id);
            foreach ($quiz->questions as $question) {
                $correct = 0;
                $total = 0;
                foreach ($quizAttempts as $attempt) {
                    $ids = $attempt->reponses[$question->id] ?? $attempt->reponses[(string) $question->id] ?? [];
                    if (! is_array($ids)) {
                        $ids = [$ids];
                    }
                    $total++;
                    if ($question->checkAnswers(array_map('intval', $ids))) {
                        $correct++;
                    }
                }
                $questionStats->push([
                    'question' => $question->question,
                    'total' => $total,
                    'correct' => $correct,
                    'rate' => $total ? round(($correct / $total) * 100, 1) : 0,
                ]);
            }
            $questionStats = $questionStats->sortBy('rate')->values();
        }

        return view('livewire.admin.quiz-analytics', [
            'quizzes' => $quizzes,
            'attempts' => $attempts,
            'avgScore' => $avgScore ? round($avgScore, 1) : 0,
            'passRate' => $passRate,
            'questionStats' => $questionStats,
            'selectedQuiz' => $quiz,
        ])
            ->extends('layouts.admin', ['title' => 'Résultats quiz & examens'])
            ->section('content');
    }
}
