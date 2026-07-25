<?php

namespace App\Livewire\Client;

use App\Models\FormationFavorite;
use App\Models\ModuleLesson;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class MesFavoris extends Component
{
    use WithSweetAlert;
    public function remove(int $id): void
    {
        FormationFavorite::where('user_id', Auth::id())->where('id', $id)->delete();
        $this->swalSuccess('Favori retiré.');
    }

    public function addCurrentLesson(int $lessonId): void
    {
        $lesson = ModuleLesson::findOrFail($lessonId);
        FormationFavorite::firstOrCreate(
            ['user_id' => Auth::id(), 'module_lesson_id' => $lesson->id],
            ['label' => $lesson->titre]
        );
        $this->swalSuccess('Ajouté aux favoris.');
    }

    public function render()
    {
        $favorites = FormationFavorite::with(['lesson.module.formation', 'article'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.client.mes-favoris', compact('favorites'))
            ->extends('layouts.client', ['title' => 'Mes Favoris'])
            ->section('content');
    }
}
