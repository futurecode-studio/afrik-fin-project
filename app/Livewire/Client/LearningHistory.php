<?php

namespace App\Livewire\Client;

use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LearningHistory extends Component
{
    public string $kind = 'all'; // all, quiz, examen, formation, certificat

    public string $period = 'all'; // all, 30, 90, 365

    public function render()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()
            ->with('formation')
            ->whereIn('status', ['active', 'completed'])
            ->orderByDesc('updated_at')
            ->get();

        $attempts = UserQuizAttempt::with(['quiz.module.formation'])
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->limit(80)
            ->get();

        $events = collect();

        foreach ($attempts as $attempt) {
            $formation = $attempt->quiz?->module?->formation;
            $events->push([
                'at' => $attempt->completed_at,
                'kind' => $attempt->quiz?->is_final ? 'examen' : 'quiz',
                'title' => $attempt->quiz?->titre ?? 'Quiz',
                'subtitle' => $formation?->titre,
                'score' => $attempt->score,
                'passed' => $attempt->is_passed,
                'pts' => $attempt->points_obtenus,
                'url' => $formation && $attempt->quiz
                    ? route('client.quiz.result', [$formation->slug, $attempt->quiz->id, $attempt->id])
                    : null,
            ]);
        }

        foreach ($enrollments->filter(fn ($e) => $e->hasCertificate()) as $e) {
            $events->push([
                'at' => $e->certificate_issued_at ?? $e->completed_at,
                'kind' => 'certificat',
                'title' => 'Certificat délivré',
                'subtitle' => $e->formation?->titre,
                'score' => null,
                'passed' => true,
                'pts' => 500,
                'url' => route('client.certificate.show', $e->id),
            ]);
        }

        foreach ($enrollments->filter(fn ($e) => $e->isCompleted()) as $e) {
            $events->push([
                'at' => $e->completed_at,
                'kind' => 'formation',
                'title' => 'Formation terminée',
                'subtitle' => $e->formation?->titre,
                'score' => (int) $e->progress,
                'passed' => true,
                'pts' => 250,
                'url' => route('client.formation.completed', $e->formation->slug),
            ]);
        }

        $timeline = $events->filter(fn ($e) => $e['at']);

        if ($this->kind !== 'all') {
            $timeline = $timeline->where('kind', $this->kind);
        }

        if ($this->period !== 'all') {
            $since = now()->subDays((int) $this->period);
            $timeline = $timeline->filter(fn ($e) => $e['at'] >= $since);
        }

        $timeline = $timeline->sortByDesc('at')->values();

        return view('livewire.client.learning-history', [
            'timeline' => $timeline,
            'enrollments' => $enrollments,
        ])
            ->extends('layouts.client', ['title' => 'Historique d\'apprentissage'])
            ->section('content');
    }
}
