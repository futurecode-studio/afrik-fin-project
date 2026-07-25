<?php

namespace App\Livewire\Pages;

use App\Models\SiteService;
use Livewire\Component;

class ServiceDetail extends Component
{
    public SiteService $service;

    public function mount(string $slug)
    {
        // Routes dédiées historiques
        if (in_array($slug, ['formations', 'bourse', 'conseil'], true)) {
            $map = [
                'formations' => 'services-formation',
                'bourse' => 'services-bourse',
                'conseil' => 'conseil-financier',
            ];
            if ($slug === 'formations') {
                $this->redirect(route('services-formation'), navigate: true);

                return;
            }
            if ($slug === 'bourse') {
                $this->redirect(route('services-bourse'), navigate: true);

                return;
            }
            $slug = $map[$slug] ?? $slug;
        }

        $this->service = SiteService::active()->where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        $related = SiteService::active()
            ->where('id', '!=', $this->service->id)
            ->take(3)
            ->get();

        return view('livewire.pages.service-detail', compact('related'))
            ->extends('layouts.site', ['title' => $this->service->title.' — Africaine des Finances'])
            ->section('content');
    }
}
