<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Livewire\Concerns\WithSweetAlert;

class Articles extends Component
{
    use WithSweetAlert;
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
    public $is_featured = false;

    protected $paginationTheme = 'tailwind';

    public function rules()
    {
        $imageRequired = !$this->editMode && empty($this->image_url) ? 'required' : 'nullable';

        $rules = [
            'titre' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . ($this->articleId ?? 'NULL'),
            'extrait' => 'nullable|string|max:5000',
            'contenu' => 'required|string',
            'image' => $imageRequired . '|image|max:2048',
            'image_url' => 'nullable|string|max:500',
            'categorie' => 'nullable|string|max:100',
            'statut' => 'required|in:brouillon,publie,archive',
            'is_featured' => 'boolean',
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
            'extrait.max' => 'L\'extrait est trop long',
            'contenu.required' => 'Le contenu est obligatoire',
            'image_url.max' => 'L\'URL de l\'image est trop longue',
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
        $this->is_featured = (bool) $article->is_featured;
        
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
            'is_featured' => (bool) $this->is_featured,
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
            $this->swalSuccess('Article modifié avec succès');
        } else {
            Article::create($articleData);
            $this->swalSuccess('Article créé avec succès');
        }

        // Fermer la modale et réinitialiser
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->resetPage();

        cache()->forget('nav.headlines.v1');
        cache()->forget('home.page.data.v5');
        cache()->forget('home.page.data.v6');
        
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
        
        $this->swalSuccess('Article supprimé avec succès');
        $this->showDeleteModal = false;
        $this->articleId = null;
        
        $this->dispatch('article-saved');
    }

    public function restore($id)
    {
        $article = Article::withTrashed()->findOrFail($id);
        $article->restore();
        
        $this->swalSuccess('Article restauré avec succès');
        
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
        $this->is_featured = false;
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
