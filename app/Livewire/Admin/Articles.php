<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Articles extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $articleId;

    // Champs du formulaire
    public $titre;
    public $slug;
    public $extrait;
    public $contenu;
    // public $image_url;
    public $image;      // nouveau fichier uploadé
    public $image_url;  // chemin/URL déjà stocké en base
    public $categorie;
    public $statut = 'brouillon';

    protected $paginationTheme = 'tailwind';

    public function rules()
    {
        $imageRequired = !$this->editMode && empty($this->image_url) ? 'required' : 'nullable';

        $rules = [
            'titre' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . ($this->articleId ?? 'NULL'),
            'extrait' => 'nullable|string|max:500',
            'contenu' => 'required|string',
            'image' => $imageRequired . '|image|max:2048',
            'image_url' => 'nullable|url|max:255',
            'categorie' => 'nullable|string|max:100',
            'statut' => 'required|in:brouillon,publie,archive',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'titre.required' => 'Le titre est obligatoire',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'slug.unique' => 'Ce slug est déjà utilisé',
            'slug.max' => 'Le slug ne doit pas dépasser 255 caractères',
            'extrait.max' => 'L\'extrait ne doit pas dépasser 500 caractères',
            'contenu.required' => 'Le contenu est obligatoire',
            'image_url.url' => 'L\'URL de l\'image doit être valide',
            'image_url.max' => 'L\'URL de l\'image ne doit pas dépasser 255 caractères',
            'categorie.max' => 'La catégorie ne doit pas dépasser 100 caractères',
            'statut.required' => 'Le statut est obligatoire',
            'statut.in' => 'Le statut doit être brouillon, publié ou archivé',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedTitre()
    {
        if (!$this->editMode || empty($this->slug)) {
            $this->slug = Str::slug($this->titre);
        }
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        
        $this->articleId = $article->id;
        $this->titre = $article->titre;
        $this->slug = $article->slug;
        $this->extrait = $article->extrait;
        $this->contenu = $article->contenu;
        $this->image_url = $article->image_url;
        $this->categorie = $article->categorie;
        $this->statut = $article->statut;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Gestion de l'image uploadée (nouvelle image éventuelle) 
        if ($this->image) {
            $path = $this->image->store('articles', 'public');
            $this->image_url = asset('storage/'.$path);
        }

        // Construire les données de l'article avec l'URL finale de l'image
        $articleData = [
            'titre'     => $this->titre,
            'slug'      => $this->slug ?: Str::slug($this->titre),
            'extrait'   => $this->extrait,
            'contenu'   => $this->contenu,
            'image_url' => $this->image_url,
            'categorie' => $this->categorie,
            'statut'    => $this->statut,
            'user_id'   => Auth::id(),
        ];

        // Si on publie l'article et qu'il n'a pas encore de date de publication
        if ($this->statut === 'publie') {
            $articleData['published_at'] = now();
        }

        if ($this->editMode) {
            $article = Article::findOrFail($this->articleId);
            // Conserver la date de publication si elle existe déjà
            if ($article->published_at) {
                unset($articleData['published_at']);
            }
            $article->update($articleData);
            session()->flash('message', 'Article modifié avec succès');
        } else {
            Article::create($articleData);
            session()->flash('message', 'Article créé avec succès');
        }

        // Fermer la modale et réinitialiser
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->resetPage();
        
        // Envoyer l'événement pour fermer proprement la modale
        $this->dispatch('article-saved');
    }

    public function confirmDelete($id)
    {
        $this->articleId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $article = Article::findOrFail($this->articleId);
        $article->delete();
        
        session()->flash('message', 'Article supprimé avec succès');
        $this->showDeleteModal = false;
        $this->articleId = null;
        
        $this->dispatch('article-saved');
    }

    public function restore($id)
    {
        $article = Article::withTrashed()->findOrFail($id);
        $article->restore();
        
        session()->flash('message', 'Article restauré avec succès');
        
        $this->dispatch('article-saved');
    }

    private function resetForm()
    {
        $this->articleId = null;
        $this->titre = '';
        $this->slug = '';
        $this->extrait = '';
        $this->contenu = '';
        $this->image = null;
        $this->image_url = '';
        $this->categorie = '';
        $this->statut = 'brouillon';
    }

    public function render()
    {
        $articles = Article::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->where('titre', 'like', '%' . $this->search . '%')
                    ->orWhere('categorie', 'like', '%' . $this->search . '%')
                    ->orWhere('extrait', 'like', '%' . $this->search . '%');
            })
            ->withTrashed()
            ->latest()
            ->paginate(10);

        return view('livewire.admin.articles', [
            'articles' => $articles
        ])
            ->extends('layouts.admin', ['title' => 'Articles & Actualités'])
            ->section('content');
    }
}
