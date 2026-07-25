<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\UserQuizAttempt;
use Livewire\Component;

class DetailProgressionApprenant extends Component
{
    public User $learner;

    public function mount(User $user): void
    {
        $this->learner = $user;
    }

    public function render()
    {
        $enrollments = Enrollment::with(['formation.modules.lessons', 'formation.modules.quiz'])
            ->where('user_id', $this->learner->id)
            ->whereIn('status', ['active', 'completed'])
            ->get();

        $attempts = UserQuizAttempt::with('quiz')
            ->where('user_id', $this->learner->id)
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->limit(20)
            ->get();

        $avgQuiz = $attempts->avg('score');
        $avgProgress = $enrollments->avg('progress');

        return view('livewire.admin.detail-progression-apprenant', [
            'enrollments' => $enrollments,
            'attempts' => $attempts,
            'avgQuiz' => $avgQuiz ? round($avgQuiz, 1) : null,
            'avgProgress' => $avgProgress ? round($avgProgress, 1) : 0,
        ])
            ->extends('layouts.admin', ['title' => 'Progression — '.$this->learner->name])
            ->section('content');
    }
}
