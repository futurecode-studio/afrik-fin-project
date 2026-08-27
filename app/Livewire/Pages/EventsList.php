<?php

namespace App\Livewire\Pages;

use App\Models\Event;
use App\Support\Countries;
use Livewire\Component;

class EventsList extends Component
{
    public string $search = '';

    public string $filterCategory = '';

    public string $filterCountry = '';

    public string $filterType = 'upcoming'; // upcoming, past, featured, all

    public string $filterFormat = ''; // physical, online, hybrid

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterCountry' => ['except' => ''],
        'filterType' => ['except' => 'upcoming'],
        'filterFormat' => ['except' => ''],
    ];

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterCategory = '';
        $this->filterCountry = '';
        $this->filterType = 'upcoming';
        $this->filterFormat = '';
    }

    public function render()
    {
        $base = Event::query();

        if ($this->filterType === 'past') {
            $base->where(function ($q) {
                $q->whereIn('status', ['published', 'ongoing', 'completed'])
                    ->where(function ($q2) {
                        $q2->where(function ($q3) {
                            $q3->whereNotNull('ends_at')->where('ends_at', '<', now());
                        })->orWhere(function ($q3) {
                            $q3->whereNull('ends_at')->where('starts_at', '<', now());
                        })->orWhere('status', 'completed');
                    });
            });
        } else {
            $base->whereIn('status', ['published', 'ongoing']);
        }

        $featured = Event::query()
            ->whereIn('status', ['published', 'ongoing'])
            ->where('is_featured', true)
            ->where('starts_at', '>=', now()->subDay())
            ->orderBy('starts_at')
            ->first();

        $query = clone $base;

        if ($this->filterType === 'upcoming') {
            $query->upcoming();
        } elseif ($this->filterType === 'past') {
            // déjà filtré dans $base
        } elseif ($this->filterType === 'featured') {
            $query->featured();
        } elseif ($this->filterType === 'all') {
            // published + ongoing, no date filter
        } else {
            $query->upcoming();
        }

        if ($this->search !== '') {
            $like = '%'.$this->search.'%';
            $query->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('city', 'like', $like)
                    ->orWhere('country', 'like', $like)
                    ->orWhere('location_name', 'like', $like);
            });
        }

        if ($this->filterCategory !== '') {
            $query->where('category', $this->filterCategory);
        }

        if ($this->filterCountry !== '') {
            $query->where('country', $this->filterCountry);
        }

        if ($this->filterFormat !== '') {
            $query->where('event_type', $this->filterFormat);
        }

        $events = $query
            ->orderBy($this->filterType === 'past' ? 'starts_at' : 'starts_at', $this->filterType === 'past' ? 'desc' : 'asc')
            ->get();

        $categories = Event::query()
            ->whereIn('status', ['published', 'ongoing'])
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values();

        $countries = Event::query()
            ->whereIn('status', ['published', 'ongoing'])
            ->whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country')
            ->filter()
            ->values();

        $stats = [
            'upcoming' => Event::upcoming()->count(),
            'featured' => Event::featured()->count(),
            'countries' => $countries->count(),
        ];

        $pastWithGallery = Event::query()
            ->with(['galleries' => fn ($q) => $q->orderBy('display_order')->limit(6)])
            ->where(function ($q) {
                $q->whereIn('status', ['completed', 'archived'])
                    ->orWhere(function ($q2) {
                        $q2->whereIn('status', ['published', 'ongoing'])
                            ->where(function ($q3) {
                                $q3->where(function ($q4) {
                                    $q4->whereNotNull('ends_at')->where('ends_at', '<', now());
                                })->orWhere(function ($q4) {
                                    $q4->whereNull('ends_at')->where('starts_at', '<', now());
                                });
                            });
                    });
            })
            ->has('galleries')
            ->orderByDesc('starts_at')
            ->take(3)
            ->get();

        return view('livewire.pages.events-list', [
            'events' => $events,
            'featured' => $featured,
            'pastWithGallery' => $pastWithGallery,
            'categories' => $categories,
            'countryOptions' => Countries::frenchOptions(),
            'stats' => $stats,
        ])->extends('layouts.site', ['title' => 'Nos événements — Africaine des Finances'])
            ->section('content');
    }
}
