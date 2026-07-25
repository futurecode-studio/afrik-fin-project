<?php

namespace App\Livewire\Admin;

use App\Models\InstructorQuestion;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class QuestionsFormateur extends Component
{
    use WithSweetAlert;
    public string $status = 'open';

    public string $q = '';

    public ?int $replyingId = null;

    public string $answer = '';

    public function openReply(int $id): void
    {
        $item = InstructorQuestion::findOrFail($id);
        $this->replyingId = $item->id;
        $this->answer = (string) ($item->answer ?? '');
    }

    public function closeReply(): void
    {
        $this->replyingId = null;
        $this->answer = '';
    }

    public function sendReply(): void
    {
        $this->validate(['answer' => 'required|string|min:5|max:10000']);
        $item = InstructorQuestion::findOrFail($this->replyingId);
        $item->update([
            'answer' => $this->answer,
            'status' => 'answered',
            'answered_at' => now(),
        ]);
        $this->swalSuccess('Réponse envoyée.');
        $this->closeReply();
    }

    public function render()
    {
        $items = InstructorQuestion::with(['user', 'formation'])
            ->when($this->status === 'open', fn ($q) => $q->whereIn('status', ['pending', 'open']))
            ->when($this->status === 'answered', fn ($q) => $q->where('status', 'answered'))
            ->when($this->q !== '', function ($q) {
                $term = '%'.$this->q.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('subject', 'like', $term)
                        ->orWhere('body', 'like', $term)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $term));
                });
            })
            ->latest()
            ->limit(60)
            ->get();

        $openCount = InstructorQuestion::whereIn('status', ['pending', 'open'])->count();

        return view('livewire.admin.questions-formateur', compact('items', 'openCount'))
            ->extends('layouts.admin', ['title' => 'Questions formateur'])
            ->section('content');
    }
}
