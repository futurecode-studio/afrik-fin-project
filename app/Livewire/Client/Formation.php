<?php

namespace App\Livewire\Client;

use App\Models\Formation as FormationModel;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Formation extends Component
{
    public FormationModel $formation;
    public ?Enrollment $enrollment = null;
    public $currentModule = null;
    public $currentLesson = null;

    public function mount($slug)
    {
        $this->formation = FormationModel::where('slug', $slug)
            ->with(['modules.lessons'])
            ->firstOrFail();

        $this->enrollment = Auth::user()->enrollments()
            ->where('formation_id', $this->formation->id)
            ->whereIn('status', ['active', 'completed'])
            ->first();

        if (!$this->enrollment) {
            return redirect()->route('formations')->with('error', 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Sélectionner le premier module et la première leçon par défaut
        if ($this->formation->modules->count() > 0) {
            $this->currentModule = $this->formation->modules->first();
            if ($this->currentModule->lessons->count() > 0) {
                $this->currentLesson = $this->currentModule->lessons->first();
            }
        }
    }

    public function selectLesson($moduleId, $lessonId)
    {
        $this->currentModule = $this->formation->modules->find($moduleId);
        if ($this->currentModule) {
            $this->currentLesson = $this->currentModule->lessons->find($lessonId);
        }
    }

    public function completeLesson()
    {
        // Logique pour marquer une leçon comme terminée
        // À implémenter selon les besoins
        
        // Mettre à jour la progression
        $totalLessons = $this->formation->modules->sum(fn($m) => $m->lessons->count());
        if ($totalLessons > 0) {
            // Simuler la progression (à améliorer avec un vrai suivi)
            $newProgress = min(100, $this->enrollment->progress + (100 / $totalLessons));
            $this->enrollment->update(['progress' => round($newProgress)]);
            
            // Si la progression atteint 100%, marquer comme terminé
            if ($newProgress >= 100) {
                $this->enrollment->complete();
                session()->flash('success', 'Félicitations ! Vous avez terminé la formation. Votre certificat est disponible.');
            }
        }
    }

    public function render()
    {
        return view('livewire.client.formation')
            ->extends('layouts.client', ['title' => $this->formation->titre])
            ->section('content');
    }
}
