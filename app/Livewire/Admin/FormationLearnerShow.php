<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\LessonExerciseSubmission;
use App\Models\LessonNote;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\UserQuizAttempt;
use Livewire\Component;
use Livewire\WithPagination;

class FormationLearnerShow extends Component
{
    use WithPagination;

    public Formation $formation;

    public User $learner;

    public Enrollment $enrollment;

    public string $logFilter = '';

    protected $queryString = [
        'logFilter' => ['except' => '', 'as' => 'log'],
    ];

    public function mount(Formation $formation, User $user): void
    {
        $this->formation = $formation->load(['modules.lessons' => fn ($q) => $q->ordered()]);
        $this->learner = $user;
        $this->enrollment = Enrollment::where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->firstOrFail();
    }

    public function updatingLogFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $completedIds = collect($this->enrollment->completed_lessons ?? []);
        $allLessons = $this->formation->modules->flatMap->lessons;
        $totalLessons = $allLessons->count();
        $doneCount = $completedIds->count();

        $modulesProgress = $this->formation->modules->map(function ($module) use ($completedIds) {
            $lessons = $module->lessons;
            $done = $lessons->filter(fn ($l) => $completedIds->contains($l->id))->count();
            $total = $lessons->count();

            return [
                'module' => $module,
                'done' => $done,
                'total' => $total,
                'pct' => $total ? round(($done / $total) * 100) : 0,
                'lessons' => $lessons->map(fn ($l) => [
                    'lesson' => $l,
                    'done' => $completedIds->contains($l->id),
                ]),
            ];
        });

        $quizIds = \App\Models\ModuleQuiz::whereIn('formation_module_id', $this->formation->modules->pluck('id'))->pluck('id');
        $attempts = $quizIds->isEmpty()
            ? collect()
            : UserQuizAttempt::with('quiz')
                ->where('user_id', $this->learner->id)
                ->whereIn('module_quiz_id', $quizIds)
                ->whereNotNull('completed_at')
                ->latest('completed_at')
                ->get();

        $exercises = LessonExerciseSubmission::with('lesson')
            ->where('user_id', $this->learner->id)
            ->where('enrollment_id', $this->enrollment->id)
            ->latest()
            ->limit(20)
            ->get();

        $notesCount = LessonNote::where('user_id', $this->learner->id)
            ->whereIn('module_lesson_id', $allLessons->pluck('id'))
            ->count();

        $logs = UserActivityLog::where('user_id', $this->learner->id)
            ->where(function ($q) {
                $q->where('formation_id', $this->formation->id)
                    ->orWhereNull('formation_id')
                    ->orWhereIn('action', [UserActivityLog::LOGIN, UserActivityLog::LOGOUT]);
            })
            ->when($this->logFilter !== '', fn ($q) => $q->where('action', $this->logFilter))
            ->latest('created_at')
            ->paginate(25);

        $loginCount = UserActivityLog::where('user_id', $this->learner->id)
            ->where('action', UserActivityLog::LOGIN)
            ->count();

        $lastLogin = $this->learner->last_login_at
            ?? UserActivityLog::where('user_id', $this->learner->id)
                ->where('action', UserActivityLog::LOGIN)
                ->latest('created_at')
                ->value('created_at');

        return view('livewire.admin.formation-learner-show', [
            'modulesProgress' => $modulesProgress,
            'attempts' => $attempts,
            'exercises' => $exercises,
            'notesCount' => $notesCount,
            'logs' => $logs,
            'loginCount' => $loginCount,
            'lastLogin' => $lastLogin,
            'totalLessons' => $totalLessons,
            'doneCount' => $doneCount,
            'avgQuiz' => $attempts->count() ? round($attempts->avg('score'), 1) : null,
        ])
            ->extends('layouts.admin', ['title' => $this->learner->name.' — '.$this->formation->titre])
            ->section('content');
    }
}
