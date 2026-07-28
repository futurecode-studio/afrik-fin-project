<?php

namespace App\Livewire\Client;

use Livewire\Component;

class VoteAg extends Component
{
    public function render()
    {
        // Toujours « bientôt » tant que le proxy voting SGI n’est pas branché ;
        // le flag admin prépare l’activation future.
        return view('livewire.client.vote-ag')
            ->extends('layouts.client', ['title' => 'Vote en Assemblée Générale'])
            ->section('content');
    }
}
