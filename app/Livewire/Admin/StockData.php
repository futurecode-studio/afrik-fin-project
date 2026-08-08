<?php

namespace App\Livewire\Admin;

use App\Models\Stock;
use App\Services\BrvmSharesCatalog;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class StockData extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $stockId = null;

    // Propriétés du formulaire
    public $symbol = '';
    public $company_name = '';
    public $current_price = '';
    public $previous_price = '';
    public $volume = 0;
    public $market_cap = '';
    public $shares_outstanding = '';
    public $sector = '';
    public $high_price = '';
    public $low_price = '';
    public $is_active = true;

    protected $rules = [
        'symbol' => 'required|string|max:10|unique:stocks,symbol',
        'company_name' => 'required|string|max:255',
        'current_price' => 'required|numeric|min:0',
        'previous_price' => 'nullable|numeric|min:0',
        'volume' => 'nullable|integer|min:0',
        'market_cap' => 'nullable|numeric|min:0',
        'shares_outstanding' => 'nullable|integer|min:0',
        'sector' => 'nullable|string|max:100',
        'high_price' => 'nullable|numeric|min:0',
        'low_price' => 'nullable|numeric|min:0',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'symbol.required' => 'Le symbole est requis.',
        'symbol.unique' => 'Ce symbole existe déjà.',
        'company_name.required' => 'Le nom de l\'entreprise est requis.',
        'current_price.required' => 'Le prix actuel est requis.',
        'current_price.numeric' => 'Le prix doit être un nombre.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function editStock($id)
    {
        $stock = Stock::findOrFail($id);
        $this->stockId = $stock->id;
        $this->symbol = $stock->symbol;
        $this->company_name = $stock->company_name;
        $this->current_price = $stock->current_price;
        $this->previous_price = $stock->previous_price;
        $this->volume = $stock->volume;
        $this->market_cap = $stock->market_cap;
        $this->shares_outstanding = $stock->shares_outstanding;
        $this->sector = $stock->sector;
        $this->high_price = $stock->high_price;
        $this->low_price = $stock->low_price;
        $this->is_active = $stock->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function saveStock()
    {
        if ($this->editMode) {
            $this->rules['symbol'] = 'required|string|max:10|unique:stocks,symbol,' . $this->stockId;
        }

        $this->validate();

        try {
            $shares = $this->shares_outstanding !== '' ? (int) $this->shares_outstanding : null;
            $marketCap = $this->market_cap !== '' ? $this->market_cap : null;
            if ($shares && (float) $this->current_price > 0) {
                $computed = app(BrvmSharesCatalog::class)
                    ->marketCapMillions($shares, (float) $this->current_price);
                if ($computed !== null) {
                    $marketCap = $computed;
                }
            }

            $data = [
                'symbol' => strtoupper($this->symbol),
                'company_name' => $this->company_name,
                'current_price' => $this->current_price,
                'previous_price' => $this->previous_price,
                'volume' => $this->volume ?? 0,
                'market_cap' => $marketCap,
                'shares_outstanding' => $shares,
                'sector' => $this->sector,
                'high_price' => $this->high_price,
                'low_price' => $this->low_price,
                'is_active' => $this->is_active,
                'last_updated' => now(),
            ];

            if ($this->editMode) {
                $stock = Stock::findOrFail($this->stockId);
                $stock->update($data);
                $stock->calculateVariation();
                $this->swalSuccess('Le titre a été modifié avec succès.');
            } else {
                $stock = Stock::create($data);
                $stock->calculateVariation();
                $this->swalSuccess('Le titre a été ajouté avec succès.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            $this->swalError('Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->stockId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteStock()
    {
        try {
            Stock::findOrFail($this->stockId)->delete();
            $this->swalSuccess('Le titre a été supprimé avec succès.');
            $this->showDeleteModal = false;
            $this->stockId = null;
        } catch (\Exception $e) {
            $this->swalError('Une erreur est survenue lors de la suppression.');
        }
    }

    public function toggleActive($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->is_active = !$stock->is_active;
            $stock->save();
            $this->swalSuccess('Le statut a été modifié avec succès.');
        } catch (\Exception $e) {
            $this->swalError('Une erreur est survenue.');
        }
    }

    public function closeModal()
{
        $this->showModal = false;
    }

    public function closeDeleteModal()
{
        $this->showDeleteModal = false;
    }

    private function resetForm()
    {
        $this->reset([
            'stockId',
            'symbol',
            'company_name',
            'current_price',
            'previous_price',
            'volume',
            'market_cap',
            'shares_outstanding',
            'sector',
            'high_price',
            'low_price',
            'is_active',
        ]);
    }

    public function render()
    {
        $stocks = Stock::where(function($query) {
            $query->where('symbol', 'like', '%' . $this->search . '%')
                  ->orWhere('company_name', 'like', '%' . $this->search . '%');
        })
        ->orderBy('symbol')
        ->paginate(10);

        return view('livewire.admin.stock-data', [
            'stocks' => $stocks,
        ])
            ->extends('layouts.admin', ['title' => 'Données Boursières'])
            ->section('content');
    }
}

