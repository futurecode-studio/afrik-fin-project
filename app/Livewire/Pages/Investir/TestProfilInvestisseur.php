<?php

namespace App\Livewire\Pages\Investir;

use App\Services\InvestorProfileService;
use Livewire\Component;

class TestProfilInvestisseur extends Component
{
    public int $step = 0;

    /** @var array<int|string, string> */
    public array $answers = [];

    public function mount(InvestorProfileService $profiles): void
    {
        if ($profiles->get()) {
            $this->redirect(route('investir.profil'), navigate: true);
        }
    }

    public function select(string $optionId, InvestorProfileService $profiles): void
    {
        $questions = $profiles->questions();
        if (! isset($questions[$this->step])) {
            return;
        }

        $qid = $questions[$this->step]['id'];
        $this->answers[$qid] = $optionId;

        if ($this->step < count($questions) - 1) {
            $this->step++;

            return;
        }

        $profile = $profiles->resolve($this->answers);
        $profiles->save($profile);
        $this->redirect(route('investir.profil'), navigate: true);
    }

    public function back(): void
    {
        if ($this->step > 0) {
            $this->step--;
        }
    }

    public function render(InvestorProfileService $profiles)
    {
        $questions = $profiles->questions();
        $current = $questions[$this->step] ?? null;
        $total = count($questions);
        $progress = $total > 0 ? (int) round((($this->step) / $total) * 100) : 0;

        return view('livewire.pages.investir.test-profil', [
            'question' => $current,
            'total' => $total,
            'progress' => $progress,
            'stepNumber' => $this->step + 1,
        ])
            ->extends('layouts.site', ['title' => 'Découvrez votre diagnostic — Africaine des Finances'])
            ->section('content');
    }
}
