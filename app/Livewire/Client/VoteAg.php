<?php

namespace App\Livewire\Client;

use App\Models\AgMeeting;
use App\Models\AgVote;
use App\Models\PortfolioHolding;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class VoteAg extends Component
{
    use WithSweetAlert;
    /** @var array<int, string> */
    public array $choices = [];

    public ?int $meetingId = null;

    public function mount(): void
    {
        $meeting = $this->resolveMeeting();
        if (! $meeting) {
            return;
        }
        $this->meetingId = $meeting->id;
        $existing = AgVote::where('user_id', Auth::id())
            ->whereIn('ag_resolution_id', $meeting->resolutions->pluck('id'))
            ->pluck('choice', 'ag_resolution_id');
        foreach ($meeting->resolutions as $res) {
            $this->choices[$res->id] = $existing[$res->id] ?? 'abstention';
        }
    }

    public function setChoice(int $resolutionId, string $choice): void
    {
        if (! in_array($choice, ['pour', 'contre', 'abstention'], true)) {
            return;
        }
        $this->choices[$resolutionId] = $choice;
    }

    public function submit(): void
    {
        $meeting = $this->resolveMeeting();
        if (! $meeting) {
            return;
        }

        DB::transaction(function () use ($meeting) {
            foreach ($meeting->resolutions as $res) {
                $choice = $this->choices[$res->id] ?? 'abstention';
                AgVote::updateOrCreate(
                    ['user_id' => Auth::id(), 'ag_resolution_id' => $res->id],
                    ['choice' => $choice]
                );
            }
        });

        $this->swalSuccess('Votes enregistrés.');
    }

    private function resolveMeeting(): ?AgMeeting
    {
        if ($this->meetingId) {
            return AgMeeting::with(['resolutions', 'stock'])->find($this->meetingId);
        }

        $meeting = AgMeeting::with(['resolutions', 'stock'])
            ->where('is_published', true)
            ->orderByDesc('closes_at')
            ->first();

        if ($meeting) {
            return $meeting;
        }

        $holding = PortfolioHolding::with('stock')->where('user_id', Auth::id())->whereNotNull('stock_id')->first();
        $stock = $holding?->stock ?? Stock::where('is_active', true)->orderByDesc('volume')->first();
        if (! $stock) {
            return null;
        }

        $meeting = AgMeeting::create([
            'stock_id' => $stock->id,
            'company_name' => $stock->company_name,
            'title' => 'Assemblée Générale Ordinaire '.$stock->symbol,
            'closes_at' => now()->addDays(14)->setTime(18, 0),
            'location' => 'Siège social',
            'quorum_percent' => 52,
            'report_url' => null,
            'is_published' => true,
        ]);

        $defaults = [
            'Approbation des comptes de l’exercice',
            'Affectation du résultat et fixation du dividende',
            'Renouvellement du mandat d’un administrateur',
        ];
        foreach ($defaults as $i => $title) {
            $meeting->resolutions()->create([
                'number' => $i + 1,
                'title' => $title,
                'kind' => 'ordinaire',
                'sort_order' => $i,
            ]);
        }

        return $meeting->fresh(['resolutions', 'stock']);
    }

    public function render()
    {
        $meeting = $this->resolveMeeting();
        $shares = PortfolioHolding::where('user_id', Auth::id())
            ->when($meeting?->stock_id, fn ($q) => $q->where('stock_id', $meeting->stock_id))
            ->sum('quantity');
        $value = 0;
        if ($meeting?->stock) {
            $value = (float) $shares * (float) $meeting->stock->current_price;
        }

        return view('livewire.client.vote-ag', compact('meeting', 'shares', 'value'))
            ->extends('layouts.client', ['title' => 'Vote en Assemblée Générale'])
            ->section('content');
    }
}
