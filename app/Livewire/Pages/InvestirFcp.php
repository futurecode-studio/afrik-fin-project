<?php

namespace App\Livewire\Pages;

use App\Models\InvestmentAppointment;
use App\Mail\InvestmentAppointmentNotification;
use App\Services\MutualFundsApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class InvestirFcp extends Component
{
    // Formulaire de rendez-vous
    public $name = '';
    public $email = '';
    public $phone = '';
    public $company = '';
    public $message = '';

    // Données FCP
    public $selectedCategory = 'Tous';
    public $mutualFunds = [];
    public $categories = [];
    public $lastUpdated = null;
    public $isLoading = true;
    public $error = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'company' => 'nullable|string|max:255',
        'message' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }

        $this->loadFunds();
    }

    public function loadFunds()
    {
        try {
            $this->isLoading = true;
            $this->error = null;

            $service = app(MutualFundsApiService::class);

            $allFunds = $service->getMutualFunds();
            $this->categories = $service->getCategories();
            
            if ($this->selectedCategory !== 'Tous') {
                $this->mutualFunds = $service->getFundsByCategory($this->selectedCategory);
            } else {
                $this->mutualFunds = $allFunds;
            }

            $this->lastUpdated = now()->format('d/m/Y à H:i:s');
            $this->isLoading = false;

        } catch (\Exception $e) {
            $this->error = 'Une erreur est survenue lors du chargement des données: ' . $e->getMessage();
            $this->mutualFunds = [];
            $this->isLoading = false;
        }
    }

    public function refreshFunds()
    {
        $service = app(MutualFundsApiService::class);
        $service->clearCache();
        $this->loadFunds();
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
        $this->loadFunds();
    }

    public function submit()
    {
        $this->validate();

        $appointment = InvestmentAppointment::create([
            'user_id' => Auth::id(),
            'investment_type' => 'fcp',
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'message' => $this->message,
            'status' => 'pending',
        ]);

        Mail::to($this->email)->send(new InvestmentAppointmentNotification($appointment, false));
        Mail::to(config('mail.from.address'))->send(new InvestmentAppointmentNotification($appointment, true));

        session()->flash('success', 'Votre demande de rendez-vous a été envoyée avec succès ! Nous vous contacterons bientôt.');

        $this->reset(['company', 'message']);
    }

    public function render()
    {
        return view('livewire.pages.investir-fcp')
            ->extends('layouts.site', ['title' => 'Investir sur les Fonds Communs de Placement'])
            ->section('content');
    }
}
