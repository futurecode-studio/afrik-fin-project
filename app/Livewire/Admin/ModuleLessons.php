<?php

namespace App\Livewire\Admin;

use App\Models\Formation;
use App\Models\FormationModule;
use App\Models\ModuleLesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class ModuleLessons extends Component
{
    use WithSweetAlert;
    use WithFileUploads;
    use WithPagination;

    public Formation $formation;

    public FormationModule $module;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public bool $editMode = false;

    public ?int $lessonId = null;

    public string $formTab = 'infos'; // infos, medias, contenu

    public string $titre = '';

    public string $slug = '';

    public string $description = '';

    public string $contenu = '';

    public string $transcript = '';

    public string $video_url = '';

    public string $audio_url = '';

    public string $pdf_url = '';

    public string $consigne = '';

    public string $duree_estimee = '';

    public int $ordre = 0;

    public string $type = 'texte';

    public bool $is_active = true;

    public $videoFile = null;

    public $audioFile = null;

    public $pdfFile = null;

    protected $paginationTheme = 'tailwind';

    public function mount(Formation $formation, FormationModule $module): void
    {
        abort_unless((int) $module->formation_id === (int) $formation->id, 404);
        $this->formation = $formation;
        $this->module = $module->loadCount('lessons');
    }

    public function rules(): array
    {
        $rules = [
            'titre' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'contenu' => 'nullable|string',
            'transcript' => 'nullable|string',
            'consigne' => 'nullable|string',
            'video_url' => 'nullable|string|max:1000',
            'audio_url' => 'nullable|string|max:1000',
            'pdf_url' => 'nullable|string|max:1000',
            'duree_estimee' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
            'type' => 'required|in:texte,video,audio,pdf,mixte,exercice',
            'is_active' => 'boolean',
            'videoFile' => 'nullable|file|max:512000|mimetypes:video/mp4,video/webm,video/quicktime',
            'audioFile' => 'nullable|file|max:102400|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/ogg',
            'pdfFile' => 'nullable|file|max:51200|mimes:pdf',
        ];

        if (in_array($this->type, ['video', 'mixte'], true) && blank($this->video_url) && ! $this->videoFile) {
            // vidéo recommandée mais pas bloquante à la création brouillon
        }

        if ($this->type === 'exercice') {
            $rules['consigne'] = 'required|string|min:5';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'consigne.required' => 'La consigne est obligatoire pour un exercice.',
            'type.in' => 'Type de leçon invalide.',
            'videoFile.max' => 'Vidéo trop lourde (max ~500 Mo).',
            'audioFile.max' => 'Audio trop lourd (max ~100 Mo).',
            'pdfFile.max' => 'PDF trop lourd (max ~50 Mo).',
        ];
    }

    public function updatedTitre(): void
    {
        if (! $this->editMode || blank($this->slug)) {
            $this->slug = Str::slug($this->titre);
        }
    }

    public function updatedType(): void
    {
        $this->formTab = match ($this->type) {
            'exercice' => 'contenu',
            'texte' => 'contenu',
            default => 'medias',
        };
        $this->resetValidation();
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->editMode = false;
        $this->ordre = (int) $this->module->lessons()->max('ordre') + 1;
        $this->formTab = 'infos';
        $this->showModal = true;
    }

    public function closeModal(): void
{
        $this->showModal = false;
    }

    public function edit(int $id): void
    {
        $lesson = $this->module->lessons()->whereKey($id)->firstOrFail();

        $this->lessonId = $lesson->id;
        $this->titre = (string) $lesson->titre;
        $this->slug = (string) ($lesson->slug ?? '');
        $this->description = (string) ($lesson->description ?? '');
        $this->contenu = (string) ($lesson->contenu ?? '');
        $this->transcript = (string) ($lesson->transcript ?? '');
        $this->consigne = (string) ($lesson->consigne ?? '');
        $this->video_url = (string) ($lesson->video_url ?? '');
        $this->audio_url = (string) ($lesson->audio_url ?? '');
        $this->pdf_url = (string) ($lesson->pdf_url ?? '');
        $this->duree_estimee = (string) ($lesson->duree_estimee ?? '');
        $this->ordre = (int) $lesson->ordre;
        $this->type = (string) ($lesson->type ?: 'texte');
        $this->is_active = (bool) $lesson->is_active;
        $this->videoFile = null;
        $this->audioFile = null;
        $this->pdfFile = null;
        $this->formTab = 'infos';
        $this->editMode = true;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        $videoUrl = trim($this->video_url) ?: null;
        $audioUrl = trim($this->audio_url) ?: null;
        $pdfUrl = trim($this->pdf_url) ?: null;

        if ($this->videoFile) {
            $path = $this->videoFile->store('lessons/videos/'.$this->module->id, 'public');
            $videoUrl = Storage::disk('public')->url($path);
        }
        if ($this->audioFile) {
            $path = $this->audioFile->store('lessons/audio/'.$this->module->id, 'public');
            $audioUrl = Storage::disk('public')->url($path);
        }
        if ($this->pdfFile) {
            $path = $this->pdfFile->store('lessons/pdf/'.$this->module->id, 'public');
            $pdfUrl = Storage::disk('public')->url($path);
        }

        $lessonData = [
            'formation_module_id' => $this->module->id,
            'titre' => trim($this->titre),
            'slug' => $this->slug ?: Str::slug($this->titre),
            'description' => $this->description ?: null,
            'contenu' => $this->contenu ?: null,
            'transcript' => $this->transcript ?: null,
            'consigne' => $this->consigne ?: null,
            'video_url' => $videoUrl,
            'audio_url' => $audioUrl,
            'pdf_url' => $pdfUrl,
            'duree_estimee' => $this->normalizeDuration($this->duree_estimee),
            'ordre' => $this->ordre ?? 0,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ];

        if ($this->editMode && $this->lessonId) {
            $lesson = $this->module->lessons()->whereKey($this->lessonId)->firstOrFail();
            $lesson->update($lessonData);
            $this->swalSuccess('Leçon mise à jour.');
        } else {
            ModuleLesson::create($lessonData);
            $this->swalSuccess('Leçon créée.');
        }

        $this->module->loadCount('lessons');
        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        $this->lessonId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $lesson = $this->module->lessons()->whereKey($this->lessonId)->firstOrFail();
        $lesson->delete();
        $this->module->loadCount('lessons');
        $this->swalSuccess('Leçon supprimée.');
        $this->showDeleteModal = false;
        $this->lessonId = null;
    }

    public function updateOrder(int $lessonId, string $direction): void
    {
        $lesson = $this->module->lessons()->whereKey($lessonId)->firstOrFail();
        $currentOrder = (int) $lesson->ordre;

        if ($direction === 'up') {
            $swap = $this->module->lessons()
                ->where('ordre', '<', $currentOrder)
                ->orderByDesc('ordre')
                ->first();
        } else {
            $swap = $this->module->lessons()
                ->where('ordre', '>', $currentOrder)
                ->orderBy('ordre')
                ->first();
        }

        if (! $swap) {
            return;
        }

        $swapOrder = (int) $swap->ordre;
        $swap->update(['ordre' => $currentOrder]);
        $lesson->update(['ordre' => $swapOrder]);
    }

    public function toggleActive(int $id): void
    {
        $lesson = $this->module->lessons()->whereKey($id)->firstOrFail();
        $lesson->update(['is_active' => ! $lesson->is_active]);
    }

    public function getYoutubePreviewProperty(): ?string
    {
        if (! $this->video_url) {
            return null;
        }
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $m)) {
            return $m[1];
        }

        return null;
    }

    private function normalizeDuration(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (preg_match('/^\d+$/', $value)) {
            return $value.' min';
        }

        return $value;
    }

    private function resetForm(): void
    {
        $this->lessonId = null;
        $this->titre = '';
        $this->slug = '';
        $this->description = '';
        $this->contenu = '';
        $this->transcript = '';
        $this->consigne = '';
        $this->video_url = '';
        $this->audio_url = '';
        $this->pdf_url = '';
        $this->duree_estimee = '';
        $this->ordre = 0;
        $this->type = 'texte';
        $this->is_active = true;
        $this->videoFile = null;
        $this->audioFile = null;
        $this->pdfFile = null;
        $this->formTab = 'infos';
    }

    public function render()
    {
        $lessons = $this->module->lessons()
            ->orderBy('ordre')
            ->paginate(20);

        $typeLabels = [
            'texte' => 'Texte',
            'video' => 'Vidéo',
            'audio' => 'Audio',
            'pdf' => 'PDF',
            'mixte' => 'Mixte',
            'exercice' => 'Exercice',
        ];

        return view('livewire.admin.module-lessons', [
            'lessons' => $lessons,
            'typeLabels' => $typeLabels,
        ])
            ->extends('layouts.admin', ['title' => 'Leçons — '.$this->module->titre])
            ->section('content');
    }
}
