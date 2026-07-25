<?php

namespace App\Livewire\Admin;

use App\Models\MarketIpo;
use App\Models\StockOrderIntent;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class MarketIpos extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public $showModal = false;
    public $ipoId = null;
    public $company_name = '';
    public $symbol = '';
    public $sector = '';
    public $status = 'annonce';
    public $description = '';
    public $offer_price_min = '';
    public $offer_price_max = '';
    public $subscription_start = '';
    public $subscription_end = '';
    public $is_published = true;

    public function openModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->ipoId = $id;
        if ($id) {
            $ipo = MarketIpo::findOrFail($id);
            $this->company_name = $ipo->company_name;
            $this->symbol = $ipo->symbol ?? '';
            $this->sector = $ipo->sector ?? '';
            $this->status = $ipo->status;
            $this->description = $ipo->description ?? '';
            $this->offer_price_min = $ipo->offer_price_min ?? '';
            $this->offer_price_max = $ipo->offer_price_max ?? '';
            $this->subscription_start = optional($ipo->subscription_start)->format('Y-m-d');
            $this->subscription_end = optional($ipo->subscription_end)->format('Y-m-d');
            $this->is_published = $ipo->is_published;
        } else {
            $this->reset(['company_name', 'symbol', 'sector', 'description', 'offer_price_min', 'offer_price_max', 'subscription_start', 'subscription_end']);
            $this->status = 'annonce';
            $this->is_published = true;
        }
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'company_name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:20',
            'sector' => 'nullable|string|max:120',
            'status' => 'required|in:annonce,souscription,cloture,cote',
            'description' => 'nullable|string',
            'offer_price_min' => 'nullable|numeric|min:0',
            'offer_price_max' => 'nullable|numeric|min:0',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        MarketIpo::updateOrCreate(['id' => $this->ipoId], $data);
        $this->swalSuccess('Introduction enregistrée.');
        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        MarketIpo::whereKey($id)->delete();
        $this->swalSuccess('Introduction supprimée.');
    }

    public function render()
    {
        return view('livewire.admin.market-ipos', [
            'ipos' => MarketIpo::latest()->paginate(15),
            'orders' => StockOrderIntent::with('stock')->latest()->paginate(10, pageName: 'orders'),
        ])
            ->extends('layouts.admin', ['title' => 'Introductions & Ordres'])
            ->section('content');
    }
}
