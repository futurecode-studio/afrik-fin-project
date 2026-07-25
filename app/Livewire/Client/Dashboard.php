<?php

namespace App\Livewire\Client;

use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $enrollments;

    public $activeEnrollments;

    public $completedEnrollments;

    public $totalProgress;

    public int $eventsCount = 0;

    public int $watchlistCount = 0;

    public function mount()
    {
        $user = Auth::user();

        if (! $user->hasCompletedInterests()) {
            $this->redirect(route('client.interests'), navigate: true);

            return;
        }

        $this->enrollments = $user->enrollments()
            ->with('formation')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->activeEnrollments = $this->enrollments->where('status', 'active');
        $this->completedEnrollments = $this->enrollments->where('status', 'completed');

        $this->totalProgress = $this->activeEnrollments->count() > 0
            ? round($this->activeEnrollments->avg('progress'))
            : 0;

        $this->eventsCount = EventRegistration::where('user_id', $user->id)
            ->whereIn('status', ['registered', 'confirmed', 'checked_in'])
            ->count();

        $this->watchlistCount = $user->watchlistItems()->count();
    }

    public function render()
    {
        return view('livewire.client.dashboard')
            ->extends('layouts.client', ['title' => 'Tableau de bord'])
            ->section('content');
    }
}
