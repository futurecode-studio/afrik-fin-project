<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\UserQuizAttempt;
use Livewire\Component;

class AnalyseEngagement extends Component
{
    public string $formationId = '';

    public function render()
    {
        $formations = Formation::orderBy('titre')->get(['id', 'titre']);

        $enrollments = Enrollment::query()
            ->when($this->formationId !== '', fn ($q) => $q->where('formation_id', $this->formationId))
            ->whereIn('status', ['active', 'completed', 'pending'])
            ->get();

        $total = $enrollments->count();
        $completed = $enrollments->filter(fn ($e) => (int) $e->progress >= 100 || $e->status === 'completed')->count();
        $active = $enrollments->where('status', 'active')->count();
        $avgProgress = $total ? round($enrollments->avg('progress'), 1) : 0;
        $completionRate = $total ? round(($completed / $total) * 100, 1) : 0;

        $buckets = [
            '0-25' => $enrollments->filter(fn ($e) => $e->progress < 25)->count(),
            '25-50' => $enrollments->filter(fn ($e) => $e->progress >= 25 && $e->progress < 50)->count(),
            '50-75' => $enrollments->filter(fn ($e) => $e->progress >= 50 && $e->progress < 75)->count(),
            '75-99' => $enrollments->filter(fn ($e) => $e->progress >= 75 && $e->progress < 100)->count(),
            '100' => $completed,
        ];

        $friction = collect($buckets)->except('100')->sortDesc()->keys()->first() ?? '0-25';

        $recent = Enrollment::with(['user', 'formation'])
            ->when($this->formationId !== '', fn ($q) => $q->where('formation_id', $this->formationId))
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return view('livewire.admin.analyse-engagement', compact(
            'formations', 'total', 'completed', 'active', 'avgProgress', 'completionRate', 'buckets', 'friction', 'recent'
        ))
            ->extends('layouts.admin', ['title' => 'Analyse de l\'Engagement'])
            ->section('content');
    }
}
