<?php

namespace App\Livewire\Client;

use App\Models\Formation as FormationModel;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormationProgress extends Component
{
    public FormationModel $formation;

    public $enrollment;

    public function mount(string $slug): void
    {
        $this->formation = FormationModel::where('slug', $slug)
            ->with([
                'modules.lessons' => fn ($q) => $q->active()->ordered(),
                'modules.quiz',
            ])
            ->firstOrFail();

        $this->enrollment = Auth::user()->enrollments()
            ->where('formation_id', $this->formation->id)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();
    }

    public function isModuleUnlocked($module, $modules): bool
    {
        $ordered = $modules->values();
        $idx = $ordered->search(fn ($m) => $m->id === $module->id);
        if ($idx === false || $idx === 0) {
            return true;
        }

        $prev = $ordered[$idx - 1];
        $completed = collect($this->enrollment->completed_lessons ?? []);
        foreach ($prev->lessons as $lesson) {
            if (! $completed->contains($lesson->id)) {
                return false;
            }
        }

        if ($prev->quiz && ! $prev->quiz->isPassedByUser(Auth::id())) {
            return false;
        }

        return true;
    }

    public function isLessonUnlocked($module, $lesson, $modules): bool
    {
        if (! $this->isModuleUnlocked($module, $modules)) {
            return false;
        }

        $completed = collect($this->enrollment->completed_lessons ?? []);
        foreach ($module->lessons as $l) {
            if ($l->id === $lesson->id) {
                return true;
            }
            if (! $completed->contains($l->id)) {
                return false;
            }
        }

        return true;
    }

    public function render()
    {
        $modules = $this->formation->modules;
        $lessons = $modules->flatMap(fn ($m) => $m->lessons);
        $completed = collect($this->enrollment->completed_lessons ?? []);
        $resumeLesson = null;
        $resumeModule = null;

        foreach ($modules as $module) {
            if (! $this->isModuleUnlocked($module, $modules)) {
                continue;
            }
            foreach ($module->lessons as $lesson) {
                if (! $completed->contains($lesson->id) && $this->isLessonUnlocked($module, $lesson, $modules)) {
                    $resumeLesson = $lesson;
                    $resumeModule = $module;
                    break 2;
                }
            }
        }
        if (! $resumeLesson && $lessons->isNotEmpty()) {
            $resumeLesson = $lessons->last();
            $resumeModule = $resumeLesson->module ?? $modules->firstWhere('id', $resumeLesson->formation_module_id);
        }

        $estimatedMinutes = $lessons->sum(fn ($l) => (int) ($l->duree_estimee ?? 15));
        $doneMinutes = (int) round($estimatedMinutes * ((int) $this->enrollment->progress / 100));

        $quizResults = UserQuizAttempt::with('quiz')
            ->where('user_id', Auth::id())
            ->whereNotNull('completed_at')
            ->whereHas('quiz', fn ($q) => $q->whereIn('formation_module_id', $modules->pluck('id')))
            ->latest('completed_at')
            ->get()
            ->unique('module_quiz_id')
            ->values();

        return view('livewire.client.formation-progress', [
            'lessons' => $lessons,
            'completed' => $completed,
            'resumeLesson' => $resumeLesson,
            'resumeModule' => $resumeModule,
            'estimatedMinutes' => $estimatedMinutes,
            'doneMinutes' => $doneMinutes,
            'quizResults' => $quizResults,
            'modules' => $modules,
        ])
            ->extends('layouts.client', ['title' => 'Progression — '.$this->formation->titre])
            ->section('content');
    }
}
