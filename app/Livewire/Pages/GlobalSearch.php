<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use App\Models\Event;
use App\Models\Formation;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $q = '';

    public function mount(): void
    {
        $this->q = (string) request('q', '');
    }

    public function updatedQ(): void
    {
        // live search via wire:model
    }

    public function render()
    {
        $term = trim($this->q);
        $formations = collect();
        $articles = collect();
        $events = collect();

        if (mb_strlen($term) >= 2) {
            $like = '%'.$term.'%';
            $formations = Formation::query()
                ->publie()
                ->where(function ($q) use ($like) {
                    $q->where('titre', 'like', $like)
                        ->orWhere('description_courte', 'like', $like)
                        ->orWhere('description_complete', 'like', $like)
                        ->orWhere('niveau', 'like', $like);
                })
                ->orderBy('titre')
                ->limit(12)
                ->get();

            $articles = Article::query()
                ->where(function ($q) use ($like) {
                    $q->where('titre', 'like', $like)
                        ->orWhere('contenu', 'like', $like);
                })
                ->latest()
                ->limit(12)
                ->get();

            $events = Event::query()
                ->whereIn('status', ['published', 'ongoing'])
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('category', 'like', $like)
                        ->orWhere('city', 'like', $like);
                })
                ->latest('starts_at')
                ->limit(8)
                ->get();
        }

        return view('livewire.pages.global-search', compact('formations', 'articles', 'events'))
            ->extends('layouts.site', ['title' => 'Recherche'])
            ->section('content');
    }
}
