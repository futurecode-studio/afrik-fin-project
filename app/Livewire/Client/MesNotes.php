<?php

namespace App\Livewire\Client;

use App\Models\LessonNote;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class MesNotes extends Component
{
    use WithSweetAlert;
    public string $q = '';

    public function deleteNote(int $id): void
    {
        LessonNote::where('user_id', Auth::id())->where('id', $id)->delete();
        $this->swalSuccess('Note supprimée.');
    }

    public function render()
    {
        $notes = LessonNote::with(['lesson.module.formation'])
            ->where('user_id', Auth::id())
            ->when($this->q !== '', function ($q) {
                $term = '%'.$this->q.'%';
                $q->where('body', 'like', $term);
            })
            ->latest()
            ->get()
            ->groupBy(fn ($n) => optional($n->created_at)->format('Y-m-d'));

        return view('livewire.client.mes-notes', compact('notes'))
            ->extends('layouts.client', ['title' => 'Mes Notes'])
            ->section('content');
    }
}
