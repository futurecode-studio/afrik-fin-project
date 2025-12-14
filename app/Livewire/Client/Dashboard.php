<?php

namespace App\Livewire\Client;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $enrollments;
    public $activeEnrollments;
    public $completedEnrollments;
    public $totalProgress;

    public function mount()
    {
        $user = Auth::user();
        
        $this->enrollments = $user->enrollments()
            ->with('formation')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $this->activeEnrollments = $this->enrollments->where('status', 'active');
        $this->completedEnrollments = $this->enrollments->where('status', 'completed');
        
        // Calculer la progression moyenne
        $this->totalProgress = $this->activeEnrollments->count() > 0 
            ? round($this->activeEnrollments->avg('progress')) 
            : 0;
    }

    public function render()
    {
        return view('livewire.client.dashboard')
            ->extends('layouts.client', ['title' => 'Mon Espace'])
            ->section('content');
    }
}
