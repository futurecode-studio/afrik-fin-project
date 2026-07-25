<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Formation;
use App\Models\FormationModule;
use Illuminate\Support\Str;
use App\Livewire\Concerns\WithSweetAlert;

class FormationModules extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public Formation $formation;
    
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $moduleId;

    // Champs du formulaire
    public $titre;
    public $slug;
    public $description;
    public $ordre;
    public $duree_estimee;
    public $is_active = true;

    protected $paginationTheme = 'tailwind';

    public function mount(Formation $formation)
    {
        $this->formation = $formation;
    }

    public function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
            'duree_estimee' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'titre.required' => 'Le titre est obligatoire',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères',
        ];
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
        // Définir l'ordre par défaut au prochain numéro
        $this->ordre = $this->formation->modules()->max('ordre') + 1;
        $this->showModal = true;
    }

    public function closeModal()
{
        $this->showModal = false;
    }

    public function edit($id)
    {
        $module = FormationModule::findOrFail($id);
        
        $this->moduleId = $module->id;
        $this->titre = $module->titre;
        $this->slug = $module->slug;
        $this->description = $module->description;
        $this->ordre = $module->ordre;
        $this->duree_estimee = $module->duree_estimee;
        $this->is_active = $module->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $moduleData = [
            'formation_id' => $this->formation->id,
            'titre' => $this->titre,
            'slug' => $this->slug ?: Str::slug($this->titre),
            'description' => $this->description,
            'ordre' => $this->ordre ?? 0,
            'duree_estimee' => $this->duree_estimee,
            'is_active' => $this->is_active,
        ];

        if ($this->editMode) {
            $module = FormationModule::findOrFail($this->moduleId);
            $module->update($moduleData);
            $this->swalSuccess('Module modifié avec succès');
        } else {
            FormationModule::create($moduleData);
            $this->swalSuccess('Module créé avec succès');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->moduleId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $module = FormationModule::findOrFail($this->moduleId);
        $module->delete();
        
        $this->swalSuccess('Module supprimé avec succès');
        $this->showDeleteModal = false;
        $this->moduleId = null;
    }

    public function updateOrder($moduleId, $direction)
    {
        $module = FormationModule::findOrFail($moduleId);
        $currentOrder = $module->ordre;
        
        if ($direction === 'up' && $currentOrder > 0) {
            // Trouver le module précédent
            $previousModule = $this->formation->modules()
                ->where('ordre', '<', $currentOrder)
                ->orderBy('ordre', 'desc')
                ->first();
            
            if ($previousModule) {
                $previousModule->update(['ordre' => $currentOrder]);
                $module->update(['ordre' => $previousModule->ordre]);
            }
        } elseif ($direction === 'down') {
            // Trouver le module suivant
            $nextModule = $this->formation->modules()
                ->where('ordre', '>', $currentOrder)
                ->orderBy('ordre', 'asc')
                ->first();
            
            if ($nextModule) {
                $nextModule->update(['ordre' => $currentOrder]);
                $module->update(['ordre' => $nextModule->ordre]);
            }
        }
    }

    private function resetForm()
    {
        $this->moduleId = null;
        $this->titre = '';
        $this->slug = '';
        $this->description = '';
        $this->ordre = 0;
        $this->duree_estimee = '';
        $this->is_active = true;
    }

    public function render()
    {
        $modules = $this->formation->modules()
            ->withCount('lessons')
            ->with('quiz')
            ->orderBy('ordre')
            ->paginate(10);

        return view('livewire.admin.formation-modules', [
            'modules' => $modules
        ])
            ->extends('layouts.admin', ['title' => 'Modules - ' . $this->formation->titre])
            ->section('content');
    }
}
