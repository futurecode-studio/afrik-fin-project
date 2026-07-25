<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\UserActivityLog;
use App\Models\UserQuizAttempt;
use Livewire\Component;
use Livewire\WithPagination;

class FormationLearners extends Component
{
    use WithPagination;

    public Formation $formation;

    public string $q = '';

    public string $status = '';

    public string $sort = 'progress_desc';

    protected $queryString = [
        'q' => ['except' => ''],
        'status' => ['except' => ''],
        'sort' => ['except' => 'progress_desc'],
    ];

    public function mount(Formation $formation): void
    {
        $this->formation = $formation;
    }

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $quizIds = \App\Models\ModuleQuiz::whereIn(
            'formation_module_id',
            $this->formation->modules()->pluck('id')
        )->pluck('id');

        $query = Enrollment::with('user')
            ->where('formation_id', $this->formation->id)
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->status === '', fn ($q) => $q->whereIn('status', ['active', 'completed', 'pending']))
            ->when($this->q !== '', function ($q) {
                $term = '%'.$this->q.'%';
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term));
            });

        $query = match ($this->sort) {
            'progress_asc' => $query->orderBy('progress'),
            'name' => $query->join('users', 'enrollments.user_id', '=', 'users.id')
                ->orderBy('users.name')
                ->select('enrollments.*'),
            'recent' => $query->orderByDesc('enrolled_at'),
            'login' => $query->join('users', 'enrollments.user_id', '=', 'users.id')
                ->orderByDesc('users.last_login_at')
                ->select('enrollments.*'),
            default => $query->orderByDesc('progress'),
        };

        $learners = $query->paginate(20);

        $userIds = $learners->pluck('user_id');
        $loginCounts = $userIds->isEmpty()
            ? collect()
            : UserActivityLog::whereIn('user_id', $userIds)
                ->where('action', UserActivityLog::LOGIN)
                ->selectRaw('user_id, count(*) as c')
                ->groupBy('user_id')
                ->pluck('c', 'user_id');

        $quizAvgs = $userIds->isEmpty() || $quizIds->isEmpty()
            ? collect()
            : UserQuizAttempt::whereIn('user_id', $userIds)
                ->whereIn('module_quiz_id', $quizIds)
                ->whereNotNull('completed_at')
                ->selectRaw('user_id, avg(score) as avg_score')
                ->groupBy('user_id')
                ->pluck('avg_score', 'user_id');

        $stats = [
            'total' => Enrollment::where('formation_id', $this->formation->id)->whereIn('status', ['active', 'completed'])->count(),
            'completed' => Enrollment::where('formation_id', $this->formation->id)
                ->where(fn ($q) => $q->where('status', 'completed')->orWhere('progress', '>=', 100))
                ->count(),
            'avg' => round(Enrollment::where('formation_id', $this->formation->id)
                ->whereIn('status', ['active', 'completed'])
                ->avg('progress') ?? 0, 1),
        ];

        return view('livewire.admin.formation-learners', [
            'learners' => $learners,
            'loginCounts' => $loginCounts,
            'quizAvgs' => $quizAvgs,
            'stats' => $stats,
        ])
            ->extends('layouts.admin', ['title' => 'Apprenants — '.$this->formation->titre])
            ->section('content');
    }
}
