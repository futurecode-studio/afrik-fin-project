<?php

namespace App\Livewire\Admin;

use App\Models\LessonExerciseSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Concerns\WithSweetAlert;

class ExerciseCorrections extends Component
{
    use WithSweetAlert;
    use WithFileUploads;

    public string $status = 'submitted';

    public string $q = '';

    public ?int $editingId = null;

    public string $score = '';

    public string $maxScore = '20';

    public string $feedback = '';

    public $annotatedFile = null;

    public function open(int $id): void
    {
        $sub = LessonExerciseSubmission::with(['user', 'lesson.module.formation'])->findOrFail($id);
        $this->editingId = $sub->id;
        $this->score = (string) ($sub->score ?? '');
        $this->maxScore = (string) ($sub->max_score ?: 20);
        $this->feedback = (string) ($sub->feedback ?? '');
        $this->annotatedFile = null;
    }

    public function close(): void
    {
        $this->editingId = null;
        $this->reset(['score', 'feedback', 'annotatedFile']);
        $this->maxScore = '20';
    }

    public function correct(): void
    {
        $this->validate([
            'score' => 'required|numeric|min:0',
            'maxScore' => 'required|numeric|min:1',
            'feedback' => 'nullable|string|max:10000',
            'annotatedFile' => 'nullable|file|max:10240|mimes:pdf,doc,docx',
        ]);

        $sub = LessonExerciseSubmission::findOrFail($this->editingId);
        $path = $sub->annotated_file_path;
        if ($this->annotatedFile) {
            $path = $this->annotatedFile->store('exercises/annotated/'.Auth::id(), 'public');
        }

        $sub->update([
            'score' => $this->score,
            'max_score' => $this->maxScore,
            'feedback' => $this->feedback ?: null,
            'annotated_file_path' => $path,
            'status' => 'corrected',
            'corrected_at' => now(),
        ]);

        $this->swalSuccess('Exercice corrigé.');
        $this->close();
    }

    public function render()
    {
        $subs = LessonExerciseSubmission::with(['user', 'lesson.module.formation'])
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->q !== '', function ($q) {
                $term = '%'.$this->q.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->whereHas('user', fn ($u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term))
                        ->orWhereHas('lesson', fn ($l) => $l->where('titre', 'like', $term));
                });
            })
            ->latest('submitted_at')
            ->limit(80)
            ->get();

        $pending = LessonExerciseSubmission::where('status', 'submitted')->count();

        return view('livewire.admin.exercise-corrections', compact('subs', 'pending'))
            ->extends('layouts.admin', ['title' => 'Correction des exercices'])
            ->section('content');
    }
}
