<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\UserQuizAttempt;
use Livewire\Component;
use Livewire\WithPagination;

class SuiviApprenants extends Component
{
    use WithPagination;

    public string $q = '';

    public string $formationId = '';

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $formations = Formation::orderBy('titre')->get(['id', 'titre']);

        $query = Enrollment::with(['user', 'formation'])
            ->whereIn('status', ['active', 'completed'])
            ->latest('updated_at');

        if ($this->formationId !== '') {
            $query->where('formation_id', $this->formationId);
        }
        if ($this->q !== '') {
            $term = '%'.$this->q.'%';
            $query->whereHas('user', fn ($u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term));
        }

        $learners = $query->paginate(15);
        $activeCount = Enrollment::where('status', 'active')->count();

        return view('livewire.admin.suivi-apprenants', compact('learners', 'formations', 'activeCount'))
            ->extends('layouts.admin', ['title' => 'Suivi des Apprenants'])
            ->section('content');
    }
}
