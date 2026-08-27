<?php

namespace App\Livewire\Client;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\ScheduledOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()
            ->with('formation')
            ->whereIn('status', ['active', 'completed'])
            ->latest('updated_at')
            ->get();

        $activeEnrollments = $enrollments->where('status', 'active');
        $completedEnrollments = $enrollments->filter(fn ($enrollment) => $enrollment->status === 'completed' || (int) $enrollment->progress >= 100);

        $totalProgress = $enrollments->isNotEmpty()
            ? (int) round($enrollments->avg(fn ($enrollment) => (int) $enrollment->progress))
            : 0;

        $nextWebinars = Event::query()
            ->whereIn('status', ['published', 'ongoing'])
            ->where('starts_at', '>=', now())
            ->where(function ($query) {
                $query->whereIn('event_type', ['online', 'hybrid'])
                    ->orWhere('category', 'like', '%web%')
                    ->orWhere('title', 'like', '%web%');
            })
            ->orderBy('starts_at')
            ->take(3)
            ->get();

        return view('livewire.client.dashboard', [
            'enrollments' => $enrollments,
            'activeEnrollments' => $activeEnrollments,
            'completedEnrollments' => $completedEnrollments,
            'eventsCount' => EventRegistration::where('user_id', $user->id)
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->count(),
            'subscriptionsCount' => feature_enabled('client.ordres')
                ? ScheduledOrder::where('user_id', $user->id)->count()
                : 0,
            'totalProgress' => $totalProgress,
            'nextWebinars' => $nextWebinars,
        ])
            ->extends('layouts.client', ['title' => 'Tableau de bord'])
            ->section('content');
    }
}
