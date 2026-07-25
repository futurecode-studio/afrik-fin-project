<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\FormationReview;
use App\Models\UserActivityLog;
use App\Models\UserQuizAttempt;
use Livewire\Component;

class FormationShow extends Component
{
    public Formation $formation;

    public function mount(Formation $formation): void
    {
        $this->formation = $formation->loadCount(['modules', 'enrollments']);
    }

    public function render()
    {
        $this->formation->load(['modules' => fn ($q) => $q->withCount('lessons')->orderBy('ordre')]);

        $enrollments = Enrollment::where('formation_id', $this->formation->id)
            ->whereIn('status', ['active', 'completed', 'pending'])
            ->get();

        $active = $enrollments->whereIn('status', ['active', 'completed']);
        $completed = $active->filter(fn ($e) => (int) $e->progress >= 100 || $e->status === 'completed');
        $avgProgress = $active->count() ? round($active->avg('progress'), 1) : 0;
        $completionRate = $active->count() ? round(($completed->count() / $active->count()) * 100, 1) : 0;

        $quizIds = $this->formation->modules->pluck('id');
        $moduleQuizIds = \App\Models\ModuleQuiz::whereIn('formation_module_id', $quizIds)->pluck('id');
        $avgQuiz = $moduleQuizIds->isNotEmpty()
            ? round(UserQuizAttempt::whereIn('module_quiz_id', $moduleQuizIds)->whereNotNull('completed_at')->avg('score') ?? 0, 1)
            : null;
        $quizAttempts = $moduleQuizIds->isNotEmpty()
            ? UserQuizAttempt::whereIn('module_quiz_id', $moduleQuizIds)->whereNotNull('completed_at')->count()
            : 0;

        $rating = FormationReview::where('formation_id', $this->formation->id)->avg('rating_overall');
        $reviewsCount = FormationReview::where('formation_id', $this->formation->id)->count();

        $buckets = [
            '0-20' => $active->filter(fn ($e) => $e->progress < 20)->count(),
            '20-40' => $active->filter(fn ($e) => $e->progress >= 20 && $e->progress < 40)->count(),
            '40-60' => $active->filter(fn ($e) => $e->progress >= 40 && $e->progress < 60)->count(),
            '60-80' => $active->filter(fn ($e) => $e->progress >= 60 && $e->progress < 80)->count(),
            '80-100' => $active->filter(fn ($e) => $e->progress >= 80)->count(),
        ];

        $recentActivity = UserActivityLog::with('user')
            ->where('formation_id', $this->formation->id)
            ->latest('created_at')
            ->limit(15)
            ->get();

        // Logins of enrolled users (global logins without formation_id)
        $studentIds = $active->pluck('user_id');
        $studentLogins30d = $studentIds->isNotEmpty()
            ? UserActivityLog::whereIn('user_id', $studentIds)
                ->where('action', UserActivityLog::LOGIN)
                ->where('created_at', '>=', now()->subDays(30))
                ->count()
            : 0;

        $topLearners = Enrollment::with('user')
            ->where('formation_id', $this->formation->id)
            ->whereIn('status', ['active', 'completed'])
            ->orderByDesc('progress')
            ->limit(8)
            ->get();

        $totalLessons = $this->formation->modules->sum('lessons_count');

        return view('livewire.admin.formation-show', [
            'enrolled' => $active->count(),
            'pending' => $enrollments->where('status', 'pending')->count(),
            'completedCount' => $completed->count(),
            'avgProgress' => $avgProgress,
            'completionRate' => $completionRate,
            'avgQuiz' => $avgQuiz,
            'quizAttempts' => $quizAttempts,
            'rating' => $rating ? round($rating, 1) : null,
            'reviewsCount' => $reviewsCount,
            'buckets' => $buckets,
            'recentActivity' => $recentActivity,
            'studentLogins30d' => $studentLogins30d,
            'topLearners' => $topLearners,
            'totalLessons' => $totalLessons,
        ])
            ->extends('layouts.admin', ['title' => 'Formation — '.$this->formation->titre])
            ->section('content');
    }
}
