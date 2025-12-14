<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use Livewire\Component;

class ActualiteDetail extends Component
{
    public $article;
    public $relatedArticles;
    
    public function mount($slug = null)
    {
        // Chercher l'article par slug ou par ID
        $this->article = Article::where('slug', $slug)
            ->orWhere('id', $slug)
            ->published()
            ->firstOrFail();

        // Récupérer les articles liés (même catégorie, excluant l'article actuel)
        $this->relatedArticles = Article::published()
            ->where('id', '!=', $this->article->id)
            ->where('categorie', $this->article->categorie)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Si pas assez d'articles liés, compléter avec d'autres articles récents
        if ($this->relatedArticles->count() < 3) {
            $additionalArticles = Article::published()
                ->where('id', '!=', $this->article->id)
                ->whereNotIn('id', $this->relatedArticles->pluck('id'))
                ->orderBy('published_at', 'desc')
                ->limit(3 - $this->relatedArticles->count())
                ->get();
            
            $this->relatedArticles = $this->relatedArticles->merge($additionalArticles);
        }
    }
    
    public function render()
    {
        return view('livewire.pages.actualite-detail')
            ->extends('layouts.site', ['title' => $this->article->titre])
            ->section('content');
    }
}
