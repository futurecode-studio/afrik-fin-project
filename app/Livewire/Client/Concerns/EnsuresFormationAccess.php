<?php

namespace App\Livewire\Client\Concerns;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\ModuleQuiz;
use Illuminate\Support\Facades\Auth;

trait EnsuresFormationAccess
{
    protected function enrollmentFor(Formation $formation): Enrollment
    {
        $enrollment = Auth::user()->enrollments()
            ->where('formation_id', $formation->id)
            ->whereIn('status', ['active', 'completed'])
            ->first();

        abort_unless($enrollment, 403, 'Inscription requise.');

        return $enrollment;
    }

    protected function quizForFormation(Formation $formation, int $quizId): ModuleQuiz
    {
        $quiz = ModuleQuiz::with(['questions.answers', 'module.formation'])
            ->where('id', $quizId)
            ->where('is_active', true)
            ->firstOrFail();

        abort_unless($quiz->module?->formation_id === $formation->id, 404);

        return $quiz;
    }
}
