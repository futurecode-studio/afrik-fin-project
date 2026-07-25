<?php

namespace App\Livewire\Client;

use App\Models\UserInterest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class Interests extends Component
{
    use WithSweetAlert;
    /** @var array<int, string> */
    public array $selected = [];

    public function mount(): void
    {
        $this->selected = Auth::user()->interests()->pluck('interest_key')->all();
    }

    public function toggle(string $key): void
    {
        if (! array_key_exists($key, UserInterest::catalog())) {
            return;
        }

        if (in_array($key, $this->selected, true)) {
            $this->selected = array_values(array_filter($this->selected, fn ($k) => $k !== $key));
        } else {
            $this->selected[] = $key;
        }
    }

    public function save(): void
    {
        $user = Auth::user();
        $valid = array_keys(UserInterest::catalog());
        $keys = array_values(array_intersect($this->selected, $valid));

        $user->interests()->whereNotIn('interest_key', $keys)->delete();

        foreach ($keys as $key) {
            $user->interests()->firstOrCreate(['interest_key' => $key]);
        }

        $user->update(['interests_completed_at' => now()]);

        $this->swalSuccess('Vos intérêts ont été enregistrés.');
        $this->redirect(route('client.dashboard'), navigate: true);
    }

    public function skip(): void
    {
        Auth::user()->update(['interests_completed_at' => now()]);
        $this->redirect(route('client.dashboard'), navigate: true);
    }

    public function render()
    {
        $catalog = UserInterest::catalog();
        $grouped = collect($catalog)
            ->map(fn ($meta, $key) => array_merge($meta, ['key' => $key]))
            ->groupBy('group');

        return view('livewire.client.interests', [
            'grouped' => $grouped,
            'catalog' => $catalog,
        ])
            ->extends('layouts.client', ['title' => 'Vos Intérêts'])
            ->section('content');
    }
}
