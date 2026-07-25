<?php

namespace App\Livewire\Pages\Marches;

use App\Models\Article;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BibliothequeRecherche extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $q = '';

    #[Url(as: 'cat', except: '')]
    public string $categorie = '';

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function updatingCategorie(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Article::published()->with('user')->latest('published_at');
        if ($this->q !== '') {
            $term = '%'.$this->q.'%';
            $query->where(function ($q) use ($term) {
                $q->where('titre', 'like', $term)
                    ->orWhere('extrait', 'like', $term)
                    ->orWhere('contenu', 'like', $term);
            });
        }
        if ($this->categorie !== '') {
            $query->where('categorie', $this->categorie);
        }

        $articles = $query->paginate(8);
        $categories = Article::published()
            ->whereNotNull('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        return view('livewire.pages.marches.bibliotheque-recherche', compact('articles', 'categories'))
            ->extends('layouts.site', ['title' => 'Bibliothèque de Recherche — Africaine des Finances'])
            ->section('content');
    }
}
