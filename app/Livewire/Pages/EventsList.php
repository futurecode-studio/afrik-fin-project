<?php

namespace App\Livewire\Pages;

use App\Models\Event;
use Livewire\Component;

class EventsList extends Component
{
    public $search = '';
    public $filterCategory = '';
    public $filterCity = '';
    public $filterType = ''; // upcoming, past, featured

    protected $queryString = ['search', 'filterCategory', 'filterCity', 'filterType'];

    public function render()
    {
        $query = Event::query()->whereIn('status', ['published','ongoing']);

        if ($this->filterType === 'upcoming') {
            $query->upcoming();
        } elseif ($this->filterType === 'past') {
            $query->where('ends_at', '<', now());
        } elseif ($this->filterType === 'featured') {
            $query->featured();
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        if ($this->filterCity) {
            $query->where('city', $this->filterCity);
        }

        $events = $query->orderBy('starts_at')->get();
        $categories = Event::whereIn('status', ['published','ongoing'])->distinct()->pluck('category')->filter();
        $cities = Event::whereIn('status', ['published','ongoing'])->distinct()->pluck('city')->filter();

        return view('livewire.pages.events-list', [
            'events' => $events,
            'categories' => $categories,
            'cities' => $cities,
        ])->extends('layouts.site', ['title' => 'Événements'])->section('content');
    }
}
