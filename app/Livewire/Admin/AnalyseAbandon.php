<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\FormationModule;
use Livewire\Component;

class AnalyseAbandon extends Component
{
    public string $formationId = '';

    public function render()
    {
        $formations = Formation::orderBy('titre')->get(['id', 'titre']);

        $query = Enrollment::query()
            ->with(['formation.modules.lessons'])
            ->when($this->formationId !== '', fn ($q) => $q->where('formation_id', $this->formationId))
            ->whereIn('status', ['active', 'completed', 'pending']);

        $enrollments = $query->get();

        $moduleStats = collect();

        $formationsScope = $this->formationId !== ''
            ? Formation::where('id', $this->formationId)->with(['modules.lessons'])->get()
            : Formation::with(['modules.lessons'])->whereIn('id', $enrollments->pluck('formation_id')->unique())->get();

        foreach ($formationsScope as $formation) {
            $formationEnrollments = $enrollments->where('formation_id', $formation->id);
            $total = $formationEnrollments->count();
            if ($total === 0) {
                continue;
            }

            foreach ($formation->modules->sortBy('ordre') as $module) {
                $lessonIds = $module->lessons->pluck('id');
                if ($lessonIds->isEmpty()) {
                    continue;
                }

                $started = 0;
                $finished = 0;
                $stuck = 0;

                foreach ($formationEnrollments as $e) {
                    $done = collect($e->completed_lessons ?? []);
                    $doneInModule = $lessonIds->filter(fn ($id) => $done->contains($id))->count();
                    if ($doneInModule > 0 || $this->reachedModule($e, $formation, $module)) {
                        $started++;
                    }
                    if ($doneInModule === $lessonIds->count()) {
                        $finished++;
                    } elseif ($doneInModule > 0 && $doneInModule < $lessonIds->count()) {
                        $stuck++;
                    }
                }

                $dropRate = $started > 0 ? round((($started - $finished) / $started) * 100, 1) : 0;

                $moduleStats->push([
                    'formation' => $formation->titre,
                    'module' => $module->titre,
                    'module_id' => $module->id,
                    'started' => $started,
                    'finished' => $finished,
                    'stuck' => $stuck,
                    'drop_rate' => $dropRate,
                ]);
            }
        }

        $moduleStats = $moduleStats->sortByDesc('drop_rate')->values();

        return view('livewire.admin.analyse-abandon', [
            'formations' => $formations,
            'moduleStats' => $moduleStats,
            'totalEnrollments' => $enrollments->count(),
        ])
            ->extends('layouts.admin', ['title' => 'Analyse d\'abandon'])
            ->section('content');
    }

    private function reachedModule(Enrollment $e, Formation $formation, FormationModule $module): bool
    {
        $ordered = $formation->modules->sortBy('ordre')->values();
        $idx = $ordered->search(fn ($m) => $m->id === $module->id);
        if ($idx === false || $idx === 0) {
            return (int) $e->progress > 0 || ! empty($e->completed_lessons);
        }

        $prev = $ordered[$idx - 1];
        $done = collect($e->completed_lessons ?? []);
        foreach ($prev->lessons as $lesson) {
            if (! $done->contains($lesson->id)) {
                return false;
            }
        }

        return true;
    }
}
