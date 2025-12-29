<?php

namespace App\Livewire\Pages;

use App\Models\InvestmentAppointment;
use App\Models\GovernmentBond;
use App\Mail\InvestmentAppointmentNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class InvestirObligations extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $company = '';
    public $message = '';

    public $bonds = [];

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

        $this->loadBonds();
    }

    public function loadBonds()
    {
        $this->bonds = GovernmentBond::active()->ordered()->get();
    }

    public function submit()
    {
        $this->validate();

        $appointment = InvestmentAppointment::create([
            'user_id' => Auth::id(),
            'investment_type' => 'obligations',
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
        return view('livewire.pages.investir-obligations')
            ->extends('layouts.site', ['title' => 'Investir sur les Obligations d\'États'])
            ->section('content');
    }
}
