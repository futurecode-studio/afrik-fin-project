<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\FormationModule;
use App\Models\ModuleLesson;
use Illuminate\Support\Str;

class ModuleLessons extends Component
{
    use WithPagination, WithFileUploads;

    public FormationModule $module;
    
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $lessonId;

    // Champs du formulaire
    public $titre;
    public $slug;
    public $description;
    public $contenu;
    public $video_url;
    public $duree_estimee;
    public $ordre;
    public $type = 'texte';
    public $is_active = true;

    protected $paginationTheme = 'tailwind';

    public function mount(FormationModule $module)
    {
        $this->module = $module;
    }

    public function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'contenu' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'duree_estimee' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
            'type' => 'required|in:texte,video,mixte',
            'is_active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'titre.required' => 'Le titre est obligatoire',
            'video_url.url' => 'L\'URL de la vidéo n\'est pas valide',
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
        $this->ordre = $this->module->lessons()->max('ordre') + 1;
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
        $lesson = ModuleLesson::findOrFail($id);
        
        $this->lessonId = $lesson->id;
        $this->titre = $lesson->titre;
        $this->slug = $lesson->slug;
        $this->description = $lesson->description;
        $this->contenu = $lesson->contenu;
        $this->video_url = $lesson->video_url;
        $this->duree_estimee = $lesson->duree_estimee;
        $this->ordre = $lesson->ordre;
        $this->type = $lesson->type;
        $this->is_active = $lesson->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $lessonData = [
            'formation_module_id' => $this->module->id,
            'titre' => $this->titre,
            'slug' => $this->slug ?: Str::slug($this->titre),
            'description' => $this->description,
            'contenu' => $this->contenu,
            'video_url' => $this->video_url,
            'duree_estimee' => $this->duree_estimee,
            'ordre' => $this->ordre ?? 0,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ];

        if ($this->editMode) {
            $lesson = ModuleLesson::findOrFail($this->lessonId);
            $lesson->update($lessonData);
            session()->flash('message', 'Leçon modifiée avec succès');
        } else {
            ModuleLesson::create($lessonData);
            session()->flash('message', 'Leçon créée avec succès');
        }

        $this->closeModal();
        $this->dispatch('lesson-saved');
    }

    public function confirmDelete($id)
    {
        $this->lessonId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $lesson = ModuleLesson::findOrFail($this->lessonId);
        $lesson->delete();
        
        session()->flash('message', 'Leçon supprimée avec succès');
        $this->showDeleteModal = false;
        $this->lessonId = null;
    }

    public function updateOrder($lessonId, $direction)
    {
        $lesson = ModuleLesson::findOrFail($lessonId);
        $currentOrder = $lesson->ordre;
        
        if ($direction === 'up' && $currentOrder > 0) {
            $previousLesson = $this->module->lessons()
                ->where('ordre', '<', $currentOrder)
                ->orderBy('ordre', 'desc')
                ->first();
            
            if ($previousLesson) {
                $previousLesson->update(['ordre' => $currentOrder]);
                $lesson->update(['ordre' => $previousLesson->ordre]);
            }
        } elseif ($direction === 'down') {
            $nextLesson = $this->module->lessons()
                ->where('ordre', '>', $currentOrder)
                ->orderBy('ordre', 'asc')
                ->first();
            
            if ($nextLesson) {
                $nextLesson->update(['ordre' => $currentOrder]);
                $lesson->update(['ordre' => $nextLesson->ordre]);
            }
        }
    }

    private function resetForm()
    {
        $this->lessonId = null;
        $this->titre = '';
        $this->slug = '';
        $this->description = '';
        $this->contenu = '';
        $this->video_url = '';
        $this->duree_estimee = '';
        $this->ordre = 0;
        $this->type = 'texte';
        $this->is_active = true;
    }

    public function render()
    {
        $lessons = $this->module->lessons()
            ->orderBy('ordre')
            ->paginate(10);

        return view('livewire.admin.module-lessons', [
            'lessons' => $lessons
        ])
            ->extends('layouts.admin', ['title' => 'Leçons - ' . $this->module->titre])
            ->section('content');
    }
}
