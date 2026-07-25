<?php

namespace App\Livewire\Client;

use App\Models\Enrollment;
use App\Models\Formation as FormationModel;
use App\Models\FormationFavorite;
use App\Models\LessonExerciseSubmission;
use App\Models\LessonNote;
use App\Models\LessonProgress;
use App\Models\ModuleLesson;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Concerns\WithSweetAlert;

class Formation extends Component
{
    use WithSweetAlert;
    use WithFileUploads;

    public FormationModel $formation;

    public ?Enrollment $enrollment = null;

    public $currentModule = null;

    public $currentLesson = null;

    #[Url(as: 'lecon', except: null)]
    public ?int $lessonId = null;

    public string $sidebarTab = 'notes'; // notes, resources, transcript

    public string $noteBody = '';

    public ?int $noteVideoSeconds = null;

    public string $exerciseAnswer = '';

    public $exerciseFile = null;

    public bool $sidebarCollapsed = false;

    public function mount($slug)
    {
        $this->formation = FormationModel::where('slug', $slug)
            ->with([
                'modules.lessons' => fn ($q) => $q->active()->ordered(),
                'modules.quiz',
            ])
            ->firstOrFail();

        $this->enrollment = Auth::user()->enrollments()
            ->where('formation_id', $this->formation->id)
            ->whereIn('status', ['active', 'completed'])
            ->first();

        if (! $this->enrollment) {
            return redirect()->route('formations')->with('error', 'Vous n\'êtes pas inscrit à cette formation.');
        }

        if ($this->lessonId) {
            $this->loadLessonById($this->lessonId);
        } elseif ($this->formation->modules->count() > 0) {
            $found = false;
            foreach ($this->formation->modules as $module) {
                if (! $this->isModuleUnlocked($module)) {
                    continue;
                }
                foreach ($module->lessons as $lesson) {
                    if ($this->isLessonUnlocked($module, $lesson) && ! $this->enrollment->hasCompletedLesson($lesson->id)) {
                        $this->currentModule = $module;
                        $this->currentLesson = $lesson;
                        $this->lessonId = $lesson->id;
                        $found = true;
                        break 2;
                    }
                }
            }
            if (! $found) {
                foreach ($this->formation->modules as $module) {
                    if (! $this->isModuleUnlocked($module)) {
                        continue;
                    }
                    $lesson = $module->lessons->first(fn ($l) => $this->isLessonUnlocked($module, $l));
                    if ($lesson) {
                        $this->currentModule = $module;
                        $this->currentLesson = $lesson;
                        $this->lessonId = $lesson->id;
                        $found = true;
                        break;
                    }
                }
            }
            if (! $found) {
                $this->currentModule = $this->formation->modules->first();
                $this->currentLesson = $this->currentModule->lessons->first();
                $this->lessonId = $this->currentLesson?->id;
            }
        }
    }

    public function selectLesson($moduleId, $lessonId): void
    {
        $this->currentModule = $this->formation->modules->find($moduleId);
        if (! $this->currentModule) {
            return;
        }

        $lesson = $this->currentModule->lessons->find($lessonId);
        if (! $lesson) {
            return;
        }

        if (! $this->isLessonUnlocked($this->currentModule, $lesson)) {
            $this->swalError('Cette leçon est verrouillée. Terminez d’abord le contenu précédent.');

            return;
        }

        $this->currentLesson = $lesson;
        $this->lessonId = $lesson->id;
        $this->exerciseAnswer = '';
        $this->exerciseFile = null;
        $this->noteVideoSeconds = null;
        $this->resetErrorBag();

        UserActivityLog::record(
            Auth::id(),
            UserActivityLog::LESSON_VIEW,
            $this->currentLesson->titre,
            $this->formation->id,
            $this->enrollment?->id,
            ['module_lesson_id' => $this->currentLesson->id]
        );
    }

    public function isModuleUnlocked($module): bool
    {
        $ordered = $this->formation->modules->values();
        $idx = $ordered->search(fn ($m) => $m->id === $module->id);
        if ($idx === false || $idx === 0) {
            return true;
        }

        $prev = $ordered[$idx - 1];
        foreach ($prev->lessons as $lesson) {
            if (! $this->enrollment->hasCompletedLesson($lesson->id)) {
                return false;
            }
        }

        if ($prev->quiz && ! $prev->quiz->isPassedByUser(Auth::id())) {
            return false;
        }

        return true;
    }

    public function isLessonUnlocked($module, $lesson): bool
    {
        if (! $this->isModuleUnlocked($module)) {
            return false;
        }

        foreach ($module->lessons as $l) {
            if ($l->id === $lesson->id) {
                return true;
            }
            if (! $this->enrollment->hasCompletedLesson($l->id)) {
                return false;
            }
        }

        return true;
    }

    public function saveVideoProgress(int $position, ?int $duration = null): void
    {
        if (! $this->currentLesson || $position < 0) {
            return;
        }

        LessonProgress::upsertPosition(
            Auth::id(),
            $this->currentLesson->id,
            $position,
            $this->enrollment?->id,
            $duration
        );
    }

    public function previousLesson(): void
    {
        $flat = $this->flatLessons();
        $idx = $flat->search(fn ($l) => $l->id === $this->currentLesson?->id);
        if ($idx !== false && $idx > 0) {
            $prev = $flat[$idx - 1];
            $this->selectLesson($prev->formation_module_id, $prev->id);
        }
    }

    public function nextLesson(): void
    {
        $flat = $this->flatLessons();
        $idx = $flat->search(fn ($l) => $l->id === $this->currentLesson?->id);
        if ($idx !== false && $idx < $flat->count() - 1) {
            $next = $flat[$idx + 1];
            $this->selectLesson($next->formation_module_id, $next->id);
        }
    }

    public function completeLesson(): void
    {
        if (! $this->currentLesson) {
            return;
        }

        $totalLessons = $this->flatLessons()->count();
        if ($totalLessons === 0) {
            return;
        }

        $alreadyCompleted = $this->enrollment->hasCompletedLesson($this->currentLesson->id);
        $this->enrollment->markLessonCompleted($this->currentLesson->id, $totalLessons);
        $this->enrollment->refresh();

        if (! $alreadyCompleted) {
            UserActivityLog::record(
                Auth::id(),
                UserActivityLog::LESSON_COMPLETE,
                $this->currentLesson->titre,
                $this->formation->id,
                $this->enrollment->id,
                ['module_lesson_id' => $this->currentLesson->id, 'progress' => $this->enrollment->progress]
            );

            if ($this->enrollment->isCompleted()) {
                $this->swalSuccess('Félicitations ! Formation terminée — certificat disponible.');
            } else {
                $this->swalSuccess('Leçon terminée. Progression : '.$this->enrollment->progress.'%');
            }
        }

        $this->nextLesson();
    }

    public function saveNote(): void
    {
        if (! $this->currentLesson) {
            return;
        }
        $this->validate(['noteBody' => 'required|string|min:2|max:5000']);
        LessonNote::create([
            'user_id' => Auth::id(),
            'module_lesson_id' => $this->currentLesson->id,
            'body' => $this->noteBody,
            'video_seconds' => $this->noteVideoSeconds,
        ]);
        $this->noteBody = '';
        $this->noteVideoSeconds = null;
        $this->swalSuccess('Note enregistrée.');
    }

    public function captureNoteTimestamp(int $seconds): void
    {
        $this->noteVideoSeconds = max(0, $seconds);
    }

    public function toggleFavorite(): void
    {
        if (! $this->currentLesson) {
            return;
        }

        $existing = FormationFavorite::where('user_id', Auth::id())
            ->where('module_lesson_id', $this->currentLesson->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->swalSuccess('Retiré des favoris.');

            return;
        }

        FormationFavorite::create([
            'user_id' => Auth::id(),
            'module_lesson_id' => $this->currentLesson->id,
            'label' => $this->currentLesson->titre,
        ]);
        $this->swalSuccess('Ajouté aux favoris.');
    }

    public function submitExercise(): void
    {
        if (! $this->currentLesson?->isExercise()) {
            return;
        }

        $this->validate([
            'exerciseAnswer' => 'nullable|string|max:20000',
            'exerciseFile' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt',
        ]);

        if (blank($this->exerciseAnswer) && ! $this->exerciseFile) {
            $this->addError('exerciseAnswer', 'Ajoutez une réponse texte ou un fichier.');

            return;
        }

        $path = null;
        $name = null;
        if ($this->exerciseFile) {
            $name = $this->exerciseFile->getClientOriginalName();
            $path = $this->exerciseFile->store('exercises/'.Auth::id(), 'public');
        }

        LessonExerciseSubmission::create([
            'user_id' => Auth::id(),
            'enrollment_id' => $this->enrollment->id,
            'module_lesson_id' => $this->currentLesson->id,
            'answer_text' => $this->exerciseAnswer ?: null,
            'file_path' => $path,
            'file_name' => $name,
            'status' => 'submitted',
            'submitted_at' => now(),
            'max_score' => 20,
        ]);

        UserActivityLog::record(
            Auth::id(),
            UserActivityLog::EXERCISE_SUBMIT,
            $this->currentLesson->titre,
            $this->formation->id,
            $this->enrollment->id,
            ['module_lesson_id' => $this->currentLesson->id]
        );

        $this->exerciseAnswer = '';
        $this->exerciseFile = null;
        $this->swalSuccess('Exercice envoyé — en attente de correction.');
    }

    private function loadLessonById(int $id): void
    {
        foreach ($this->formation->modules as $module) {
            $lesson = $module->lessons->firstWhere('id', $id);
            if ($lesson) {
                if ($this->isLessonUnlocked($module, $lesson)) {
                    $this->currentModule = $module;
                    $this->currentLesson = $lesson;

                    return;
                }
                break;
            }
        }

        // Fallback: first unlocked incomplete lesson
        foreach ($this->formation->modules as $module) {
            if (! $this->isModuleUnlocked($module)) {
                continue;
            }
            foreach ($module->lessons as $lesson) {
                if ($this->isLessonUnlocked($module, $lesson) && ! $this->enrollment->hasCompletedLesson($lesson->id)) {
                    $this->currentModule = $module;
                    $this->currentLesson = $lesson;
                    $this->lessonId = $lesson->id;

                    return;
                }
            }
        }

        $this->currentModule = $this->formation->modules->first();
        $this->currentLesson = $this->currentModule?->lessons->first();
        $this->lessonId = $this->currentLesson?->id;
    }

    private function flatLessons()
    {
        return $this->formation->modules->flatMap(fn ($m) => $m->lessons)->values();
    }

    public function render()
    {
        $notes = collect();
        $submission = null;
        if ($this->currentLesson) {
            $notes = LessonNote::where('user_id', Auth::id())
                ->where('module_lesson_id', $this->currentLesson->id)
                ->latest()
                ->limit(10)
                ->get();
            $submission = LessonExerciseSubmission::where('user_id', Auth::id())
                ->where('module_lesson_id', $this->currentLesson->id)
                ->latest()
                ->first();
        }

        $completedCount = count($this->enrollment->completed_lessons ?? []);
        $totalCount = $this->flatLessons()->count();
        $isFavorite = $this->currentLesson
            ? FormationFavorite::where('user_id', Auth::id())
                ->where('module_lesson_id', $this->currentLesson->id)
                ->exists()
            : false;

        $videoProgress = $this->currentLesson
            ? LessonProgress::where('user_id', Auth::id())
                ->where('module_lesson_id', $this->currentLesson->id)
                ->first()
            : null;

        return view('livewire.client.formation', [
            'notes' => $notes,
            'submission' => $submission,
            'completedCount' => $completedCount,
            'totalCount' => $totalCount,
            'isFavorite' => $isFavorite,
            'videoProgress' => $videoProgress,
        ])
            ->extends('layouts.course', ['title' => $this->formation->titre, 'formation' => $this->formation])
            ->section('content');
    }
}
