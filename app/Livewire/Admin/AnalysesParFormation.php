<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\FormationReview;
use App\Models\UserQuizAttempt;
use Livewire\Component;

class AnalysesParFormation extends Component
{
    public function render()
    {
        $rows = Formation::withCount([
            'enrollments as enrollments_count' => fn ($q) => $q->whereIn('status', ['active', 'completed']),
        ])->orderBy('titre')->get()->map(function (Formation $f) {
            $ens = Enrollment::where('formation_id', $f->id)->whereIn('status', ['active', 'completed'])->get();
            $completed = $ens->filter(fn ($e) => (int) $e->progress >= 100 || $e->status === 'completed')->count();
            $avg = $ens->count() ? round($ens->avg('progress'), 1) : 0;
            $quizIds = $f->modules()->with('quiz')->get()->pluck('quiz.id')->filter();
            $avgQuiz = $quizIds->isNotEmpty()
                ? round(UserQuizAttempt::whereIn('module_quiz_id', $quizIds)->whereNotNull('completed_at')->avg('score') ?? 0, 1)
                : null;
            $rating = FormationReview::where('formation_id', $f->id)->avg('rating_overall');

            return [
                'formation' => $f,
                'enrolled' => $ens->count(),
                'completed' => $completed,
                'avg' => $avg,
                'completion' => $ens->count() ? round(($completed / $ens->count()) * 100, 1) : 0,
                'avg_quiz' => $avgQuiz,
                'rating' => $rating ? round($rating, 1) : null,
            ];
        });

        return view('livewire.admin.analyses-par-formation', compact('rows'))
            ->extends('layouts.admin', ['title' => 'Analyses par Formation'])
            ->section('content');
    }
}
