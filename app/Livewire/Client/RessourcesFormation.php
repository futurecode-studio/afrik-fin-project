<?php

namespace App\Livewire\Client;

use App\Models\Formation;
use App\Models\FormationResource;
use App\Models\ModuleLesson;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RessourcesFormation extends Component
{
    public Formation $formation;

    public string $moduleFilter = '';

    public function mount(string $slug): void
    {
        $this->formation = Formation::where('slug', $slug)
            ->with(['modules' => fn ($q) => $q->orderBy('ordre')])
            ->firstOrFail();
        abort_unless(
            Auth::user()->enrollments()->where('formation_id', $this->formation->id)->whereIn('status', ['active', 'completed'])->exists(),
            403
        );
    }

    public function render()
    {
        $resources = FormationResource::published()
            ->where('formation_id', $this->formation->id)
            ->when($this->moduleFilter !== '', fn ($q) => $q->where('formation_module_id', $this->moduleFilter))
            ->orderBy('sort_order')
            ->get();

        // Fallback: lesson PDFs / media from curriculum
        $lessonResources = ModuleLesson::whereIn('formation_module_id', $this->formation->modules->pluck('id'))
            ->where(function ($q) {
                $q->whereNotNull('pdf_url')->orWhereNotNull('audio_url')->orWhereNotNull('video_url');
            })
            ->with('module')
            ->get()
            ->map(fn ($l) => [
                'title' => $l->titre,
                'type' => $l->pdf_url ? 'pdf' : ($l->audio_url ? 'audio' : 'video'),
                'url' => $l->pdf_url ?: $l->audio_url ?: $l->video_url,
                'module' => $l->module?->titre,
            ]);

        return view('livewire.client.ressources-formation', compact('resources', 'lessonResources'))
            ->extends('layouts.client', ['title' => 'Ressources — '.$this->formation->titre])
            ->section('content');
    }
}
