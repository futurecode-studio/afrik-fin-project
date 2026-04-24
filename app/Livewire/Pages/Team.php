<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\TeamMember;

class Team extends Component
{
    public function render()
    {
        $members = TeamMember::active()->orderBy('order')->get();
        
        return view('livewire.pages.team', [
            'members' => $members,
        ])
            ->extends('layouts.site', ['title' => 'Équipe'])
            ->section('content');
    }
}