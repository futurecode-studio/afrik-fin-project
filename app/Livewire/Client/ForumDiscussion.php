<?php

namespace App\Livewire\Client;

use App\Models\Formation;
use App\Models\FormationForumPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class ForumDiscussion extends Component
{
    use WithSweetAlert;
    public Formation $formation;

    public string $title = '';

    public string $body = '';

    public string $replyBody = '';

    public ?int $replyTo = null;

    public function mount(string $slug): void
    {
        $this->formation = Formation::where('slug', $slug)->firstOrFail();
        abort_unless(
            Auth::user()->enrollments()->where('formation_id', $this->formation->id)->whereIn('status', ['active', 'completed'])->exists(),
            403
        );
    }

    public function post(): void
    {
        $this->validate([
            'title' => 'required|string|min:3|max:200',
            'body' => 'required|string|min:5|max:5000',
        ]);

        FormationForumPost::create([
            'formation_id' => $this->formation->id,
            'user_id' => Auth::id(),
            'title' => $this->title,
            'body' => $this->body,
        ]);

        $this->reset('title', 'body');
        $this->swalSuccess('Question publiée.');
    }

    public function setReply(int $id): void
    {
        $this->replyTo = $id;
        $this->replyBody = '';
    }

    public function reply(): void
    {
        $this->validate(['replyBody' => 'required|string|min:2|max:5000']);
        abort_unless($this->replyTo, 400);

        FormationForumPost::create([
            'formation_id' => $this->formation->id,
            'user_id' => Auth::id(),
            'parent_id' => $this->replyTo,
            'body' => $this->replyBody,
        ]);

        $this->reset('replyBody', 'replyTo');
        $this->swalSuccess('Réponse ajoutée.');
    }

    public function render()
    {
        $threads = FormationForumPost::with(['user', 'replies.user'])
            ->where('formation_id', $this->formation->id)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('livewire.client.forum-discussion', compact('threads'))
            ->extends('layouts.client', ['title' => 'Forum — '.$this->formation->titre])
            ->section('content');
    }
}
