<?php

namespace App\Livewire\Pages;

use App\Models\TeamMember;
use Livewire\Component;

class About extends Component
{
    public function render()
    {
        $members = TeamMember::active()->leadership()->orderBy('order')->get();

        return view('livewire.pages.about', [
            'members' => $members,
        ])
            ->extends('layouts.site', ['title' => 'À propos — Africaine des Finances'])
            ->section('content');
    }
}
