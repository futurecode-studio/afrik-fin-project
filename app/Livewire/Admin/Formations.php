<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Formation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Livewire\Concerns\WithSweetAlert;

class Formations extends Component
{
    use WithSweetAlert;
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $formationId;

    // Champs du formulaire
    public $titre;
    public $slug;
    public $description_courte;
    public $description_complete;
    public $image;      // nouveau fichier uploadé
    public $image_url;  // chemin/URL déjà stocké en base
    public $niveau = 'debutant';
    public $duree;
    public $prix = 0;
    public $price_label;
    public $is_free = false;
    public $statut = 'brouillon';

    protected $paginationTheme = 'tailwind';

    public function rules()
    {
        $rules = [
            'titre' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:formations,slug,' . ($this->formationId ?? 'NULL'),
            'description_courte' => 'nullable|string|max:500',
            'description_complete' => 'required|string',
            'image' => $this->editMode ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'image_url' => 'nullable|string|max:255',
            'niveau' => 'required|in:debutant,intermediaire,avance',
            'duree' => 'nullable|string|max:100',
            'prix' => 'required|numeric|min:0',
            'price_label' => 'nullable|string|max:120',
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
            'description_courte.max' => 'La description courte ne doit pas dépasser 500 caractères',
            'description_complete.required' => 'La description complète est obligatoire',
            'image_url.max' => 'L\'URL de l\'image ne doit pas dépasser 255 caractères',
            'niveau.required' => 'Le niveau est obligatoire',
            'niveau.in' => 'Le niveau doit être débutant, intermédiaire ou avancé',
            'duree.max' => 'La durée ne doit pas dépasser 100 caractères',
            'prix.required' => 'Le prix est obligatoire',
            'prix.numeric' => 'Le prix doit être un nombre',
            'prix.min' => 'Le prix doit être supérieur ou égal à 0',
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
        $formation = Formation::findOrFail($id);
        
        $this->formationId = $formation->id;
        $this->titre = $formation->titre;
        $this->slug = $formation->slug;
        $this->description_courte = $formation->description_courte;
        $this->description_complete = $formation->description_complete;
        $this->image_url = $formation->image_url;
        $this->niveau = $formation->niveau;
        $this->duree = $formation->duree;
        $this->prix = $formation->prix;
        $this->price_label = $formation->price_label;
        $this->is_free = $formation->is_free;
        $this->statut = $formation->statut;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Gestion de l'image uploadée (nouvelle image éventuelle) 
        if ($this->image) {
            $path = $this->image->store('formations', 'public');
            $this->image_url = asset('storage/'.$path);
        }

        // Construire les données de la formation avec l'URL finale de l'image
        $formationData = [
            'titre'                => $this->titre,
            'slug'                 => $this->slug ?: Str::slug($this->titre),
            'description_courte'   => $this->description_courte,
            'description_complete' => $this->description_complete,
            'image_url'            => $this->image_url,
            'niveau'               => $this->niveau,
            'duree'                => $this->duree,
            'prix'                 => $this->is_free ? 0 : $this->prix,
            'price_label'          => $this->price_label ?: null,
            'is_free'              => $this->is_free,
            'statut'               => $this->statut,
            'user_id'              => Auth::id(),
        ];

        // Si on publie la formation et qu'elle n'a pas encore de date de publication
        if ($this->statut === 'publie') {
            $formationData['published_at'] = now();
        }

        if ($this->editMode) {
            $formation = Formation::findOrFail($this->formationId);
            // Conserver la date de publication si elle existe déjà
            if ($formation->published_at) {
                unset($formationData['published_at']);
            }
            $formation->update($formationData);
            $this->swalSuccess('Formation modifiée avec succès');
        } else {
            Formation::create($formationData);
            $this->swalSuccess('Formation créée avec succès');
        }

        // Fermer la modale et réinitialiser
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->resetPage();
        
        // Envoyer l'événement pour fermer proprement la modale
        $this->dispatch('formation-saved');
    }

    public function confirmDelete($id)
    {
        $this->formationId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $formation = Formation::findOrFail($this->formationId);
        $formation->delete();
        
        $this->swalSuccess('Formation supprimée avec succès');
        $this->showDeleteModal = false;
        $this->formationId = null;
        
        $this->dispatch('formation-saved');
    }

    public function restore($id)
    {
        $formation = Formation::withTrashed()->findOrFail($id);
        $formation->restore();
        
        $this->swalSuccess('Formation restaurée avec succès');
        
        $this->dispatch('formation-saved');
    }

    private function resetForm()
    {
        $this->formationId = null;
        $this->titre = '';
        $this->slug = '';
        $this->description_courte = '';
        $this->description_complete = '';
        $this->image = null;
        $this->image_url = '';
        $this->niveau = 'debutant';
        $this->duree = '';
        $this->prix = 0;
        $this->price_label = '';
        $this->is_free = false;
        $this->statut = 'brouillon';
    }

    public function render()
    {
        $formations = Formation::query()
            ->with('user')
            ->withCount('modules')
            ->when($this->search, function ($query) {
                $query->where('titre', 'like', '%' . $this->search . '%')
                    ->orWhere('niveau', 'like', '%' . $this->search . '%')
                    ->orWhere('description_courte', 'like', '%' . $this->search . '%');
            })
            ->withTrashed()
            ->latest()
            ->paginate(10);

        return view('livewire.admin.formations', [
            'formations' => $formations
        ])
            ->extends('layouts.admin', ['title' => 'Gestion des Formations'])
            ->section('content');
    }
}
