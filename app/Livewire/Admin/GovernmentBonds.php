<?php

namespace App\Livewire\Admin;

use App\Models\GovernmentBond;
use Livewire\Component;
use Livewire\WithPagination;

class GovernmentBonds extends Component
{
    use WithPagination;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $bondId = null;

    public $name = '';
    public $issuer = '';
    public $country = '';
    public $isin_code = '';
    public $nominal_value = '';
    public $currency = 'FCFA';
    public $interest_rate = '';
    public $interest_type = 'fixed';
    public $payment_frequency = 'semi_annual';
    public $issue_date = '';
    public $maturity_date = '';
    public $maturity_years = '';
    public $current_price = '';
    public $yield_to_maturity = '';
    public $rating = '';
    public $description = '';
    public $risk_level = 'medium';
    public $minimum_investment = '';
    public $is_active = true;
    public $display_order = 0;

    public $search = '';
    public $filterStatus = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'issuer' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'isin_code' => 'nullable|string|max:255|unique:government_bonds,isin_code',
        'nominal_value' => 'required|numeric|min:0',
        'currency' => 'required|string|max:10',
        'interest_rate' => 'required|numeric|min:0|max:100',
        'interest_type' => 'required|string',
        'payment_frequency' => 'required|string',
        'issue_date' => 'required|date',
        'maturity_date' => 'required|date|after:issue_date',
        'maturity_years' => 'required|integer|min:1',
        'current_price' => 'nullable|numeric|min:0',
        'yield_to_maturity' => 'nullable|numeric|min:0|max:100',
        'rating' => 'nullable|string|max:50',
        'description' => 'nullable|string',
        'risk_level' => 'required|string',
        'minimum_investment' => 'nullable|numeric|min:0',
        'is_active' => 'boolean',
        'display_order' => 'integer|min:0',
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->bondId = null;
        $this->name = '';
        $this->issuer = '';
        $this->country = '';
        $this->isin_code = '';
        $this->nominal_value = '';
        $this->currency = 'FCFA';
        $this->interest_rate = '';
        $this->interest_type = 'fixed';
        $this->payment_frequency = 'semi_annual';
        $this->issue_date = '';
        $this->maturity_date = '';
        $this->maturity_years = '';
        $this->current_price = '';
        $this->yield_to_maturity = '';
        $this->rating = '';
        $this->description = '';
        $this->risk_level = 'medium';
        $this->minimum_investment = '';
        $this->is_active = true;
        $this->display_order = 0;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $bond = GovernmentBond::findOrFail($this->bondId);
            $bond->update($this->getBondData());
            session()->flash('success', 'Obligation mise à jour avec succès.');
        } else {
            GovernmentBond::create($this->getBondData());
            session()->flash('success', 'Obligation créée avec succès.');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $bond = GovernmentBond::findOrFail($id);
        
        $this->bondId = $bond->id;
        $this->name = $bond->name;
        $this->issuer = $bond->issuer;
        $this->country = $bond->country;
        $this->isin_code = $bond->isin_code;
        $this->nominal_value = $bond->nominal_value;
        $this->currency = $bond->currency;
        $this->interest_rate = $bond->interest_rate;
        $this->interest_type = $bond->interest_type;
        $this->payment_frequency = $bond->payment_frequency;
        $this->issue_date = $bond->issue_date->format('Y-m-d');
        $this->maturity_date = $bond->maturity_date->format('Y-m-d');
        $this->maturity_years = $bond->maturity_years;
        $this->current_price = $bond->current_price;
        $this->yield_to_maturity = $bond->yield_to_maturity;
        $this->rating = $bond->rating;
        $this->description = $bond->description;
        $this->risk_level = $bond->risk_level;
        $this->minimum_investment = $bond->minimum_investment;
        $this->is_active = $bond->is_active;
        $this->display_order = $bond->display_order;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $this->bondId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        GovernmentBond::findOrFail($this->bondId)->delete();
        session()->flash('success', 'Obligation supprimée avec succès.');
        $this->showDeleteModal = false;
        $this->bondId = null;
    }

    public function toggleStatus($id)
    {
        $bond = GovernmentBond::findOrFail($id);
        $bond->update(['is_active' => !$bond->is_active]);
        session()->flash('success', 'Statut mis à jour avec succès.');
    }

    private function getBondData()
    {
        return [
            'name' => $this->name,
            'issuer' => $this->issuer,
            'country' => $this->country,
            'isin_code' => $this->isin_code,
            'nominal_value' => $this->nominal_value,
            'currency' => $this->currency,
            'interest_rate' => $this->interest_rate,
            'interest_type' => $this->interest_type,
            'payment_frequency' => $this->payment_frequency,
            'issue_date' => $this->issue_date,
            'maturity_date' => $this->maturity_date,
            'maturity_years' => $this->maturity_years,
            'current_price' => $this->current_price,
            'yield_to_maturity' => $this->yield_to_maturity,
            'rating' => $this->rating,
            'description' => $this->description,
            'risk_level' => $this->risk_level,
            'minimum_investment' => $this->minimum_investment,
            'is_active' => $this->is_active,
            'display_order' => $this->display_order,
        ];
    }

    public function render()
    {
        $query = GovernmentBond::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('issuer', 'like', '%' . $this->search . '%')
                  ->orWhere('country', 'like', '%' . $this->search . '%')
                  ->orWhere('isin_code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus);
        }

        $bonds = $query->orderBy('display_order')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.government-bonds', [
            'bonds' => $bonds,
        ])->extends('layouts.admin')->section('content');
    }
}
