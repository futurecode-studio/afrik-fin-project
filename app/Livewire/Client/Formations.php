<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Formations extends Component
{
    public string $tab = 'en_cours';

    public string $q = '';

    public function render()
    {
        $all = Auth::user()->enrollments()
            ->with(['formation.modules.lessons', 'formation.user'])
            ->whereIn('status', ['active', 'completed'])
            ->orderByDesc('updated_at')
            ->get();

        if ($this->q !== '') {
            $term = mb_strtolower($this->q);
            $all = $all->filter(function ($e) use ($term) {
                $f = $e->formation;

                return str_contains(mb_strtolower($f->titre ?? ''), $term)
                    || str_contains(mb_strtolower($f->categorie ?? ''), $term)
                    || str_contains(mb_strtolower($f->user->name ?? ''), $term);
            });
        }

        $filtered = match ($this->tab) {
            'a_commencer' => $all->filter(fn ($e) => (int) $e->progress === 0 && $e->status === 'active'),
            'terminees' => $all->filter(fn ($e) => $e->status === 'completed' || (int) $e->progress >= 100),
            'certificats' => $all->filter(fn ($e) => $e->hasCertificate()),
            default => $all->filter(fn ($e) => $e->status === 'active' && (int) $e->progress > 0 && (int) $e->progress < 100),
        };

        if ($this->tab === 'en_cours' && $filtered->isEmpty() && $this->q === '') {
            $filtered = $all->filter(fn ($e) => $e->status === 'active');
        }

        $enrollments = $filtered->values()->map(function ($enrollment) {
            $f = $enrollment->formation;
            $lessons = $f->modules->flatMap->lessons;
            $total = $lessons->count();
            $done = count($enrollment->completed_lessons ?? []);
            $estMin = $lessons->sum(fn ($l) => max(1, (int) ($l->duree_estimee ?: 15)));
            $remaining = max(0, (int) round($estMin * (1 - ((int) $enrollment->progress / 100))));
            $cta = match (true) {
                $enrollment->status === 'completed' || (int) $enrollment->progress >= 100 => 'Revoir',
                (int) $enrollment->progress === 0 => 'Commencer',
                default => 'Continuer',
            };

            return [
                'enrollment' => $enrollment,
                'formation' => $f,
                'total' => $total,
                'done' => $done,
                'remaining' => $remaining,
                'cta' => $cta,
            ];
        });

        $continue = Auth::user()->enrollments()
            ->with('formation')
            ->where('status', 'active')
            ->orderByDesc('updated_at')
            ->first();

        return view('livewire.client.formations', [
            'rows' => $enrollments,
            'allCount' => Auth::user()->enrollments()->whereIn('status', ['active', 'completed'])->count(),
            'continue' => $continue,
        ])
            ->extends('layouts.client', ['title' => 'Mes Formations'])
            ->section('content');
    }
}
