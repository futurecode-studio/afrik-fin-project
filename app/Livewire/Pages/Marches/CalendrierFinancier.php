<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class CalendrierFinancier extends Component
{
    #[Url(as: 'y', except: null)]
    public ?int $year = null;

    #[Url(as: 'm', except: null)]
    public ?int $month = null;

    public function mount(): void
    {
        $this->year = $this->year ?: (int) now()->year;
        $this->month = $this->month ?: (int) now()->month;
    }

    public function prev(): void
    {
        $d = now()->setDate($this->year, $this->month, 1)->subMonth();
        $this->year = (int) $d->year;
        $this->month = (int) $d->month;
    }

    public function next(): void
    {
        $d = now()->setDate($this->year, $this->month, 1)->addMonth();
        $this->year = (int) $d->year;
        $this->month = (int) $d->month;
    }

    public function render(MarketsDataService $markets)
    {
        $year = $this->year ?: (int) now()->year;
        $month = $this->month ?: (int) now()->month;
        $events = $markets->financialCalendar($year, $month);
        $byDay = collect($events)->groupBy('day');

        $start = now()->setDate($year, $month, 1)->startOfDay();
        $daysInMonth = $start->daysInMonth;
        $startWeekday = (int) $start->dayOfWeekIso; // 1=Mon

        return view('livewire.pages.marches.calendrier-financier', [
            'events' => $events,
            'byDay' => $byDay,
            'daysInMonth' => $daysInMonth,
            'startWeekday' => $startWeekday,
            'label' => $start->translatedFormat('F Y'),
            'year' => $year,
            'month' => $month,
        ])
            ->extends('layouts.site', ['title' => 'Calendrier Financier — Africaine des Finances'])
            ->section('content');
    }
}
