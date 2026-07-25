<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use Livewire\Component;

class Actualites extends Component
{
    public function render()
    {
        $articles = Article::published()
            ->orderBy('published_at', 'desc')
            ->get();

        return view('livewire.pages.actualites', [
            'articles' => $articles
        ])
            ->extends('layouts.site', ['title' => 'Actualités des Marchés — Africaine des Finances'])
            ->section('content');
    }
}
