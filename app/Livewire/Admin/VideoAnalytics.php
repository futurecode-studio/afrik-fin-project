<?php

namespace App\Livewire\Admin;

use App\Models\LessonProgress;
use App\Models\ModuleLesson;
use Livewire\Component;

class VideoAnalytics extends Component
{
    public string $formationId = '';

    public function render()
    {
        $rows = LessonProgress::with(['lesson.module.formation', 'user'])
            ->when($this->formationId !== '', function ($q) {
                $q->whereHas('lesson.module', fn ($m) => $m->where('formation_id', $this->formationId));
            })
            ->latest('last_watched_at')
            ->limit(100)
            ->get();

        $byLesson = LessonProgress::query()
            ->selectRaw('module_lesson_id, COUNT(*) as viewers, AVG(watched_seconds) as avg_watched, AVG(video_position) as avg_position, AVG(duration_seconds) as avg_duration')
            ->when($this->formationId !== '', function ($q) {
                $q->whereHas('lesson.module', fn ($m) => $m->where('formation_id', $this->formationId));
            })
            ->groupBy('module_lesson_id')
            ->orderByDesc('viewers')
            ->limit(40)
            ->get()
            ->map(function ($row) {
                $lesson = ModuleLesson::with('module.formation')->find($row->module_lesson_id);
                $completion = $row->avg_duration > 0
                    ? round(min(100, ($row->avg_watched / $row->avg_duration) * 100), 1)
                    : 0;

                return [
                    'lesson' => $lesson,
                    'viewers' => (int) $row->viewers,
                    'avg_watched' => (int) round($row->avg_watched),
                    'completion' => $completion,
                ];
            });

        $formations = \App\Models\Formation::orderBy('titre')->get(['id', 'titre']);

        return view('livewire.admin.video-analytics', compact('rows', 'byLesson', 'formations'))
            ->extends('layouts.admin', ['title' => 'Suivi vidéos'])
            ->section('content');
    }
}
