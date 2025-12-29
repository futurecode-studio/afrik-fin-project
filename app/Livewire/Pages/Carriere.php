<?php

namespace App\Livewire\Pages;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Carriere extends Component
{
    use WithFileUploads;

    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $position_applied = '';
    public $city = '';
    public $country = 'Bénin';
    public $cover_letter = '';
    public $cv;
    public $linkedin_url = '';
    public $portfolio_url = '';
    public $years_of_experience = 0;
    public $education_level = '';
    public $current_company = '';
    public $expected_salary = '';
    public $availability = 'immediate';

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'position_applied' => 'required|string|max:255',
        'city' => 'nullable|string|max:100',
        'country' => 'required|string|max:100',
        'cover_letter' => 'nullable|string|max:2000',
        'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
        'linkedin_url' => 'nullable|url|max:255',
        'portfolio_url' => 'nullable|url|max:255',
        'years_of_experience' => 'required|integer|min:0',
        'education_level' => 'nullable|string|max:255',
        'current_company' => 'nullable|string|max:255',
        'expected_salary' => 'nullable|numeric|min:0',
        'availability' => 'required|string',
    ];

    public function mount()
    {
        if (Auth::check()) {
            $this->first_name = explode(' ', Auth::user()->name)[0] ?? '';
            $this->last_name = explode(' ', Auth::user()->name)[1] ?? '';
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone ?? '';
            $this->city = Auth::user()->city ?? '';
            $this->country = Auth::user()->country ?? 'Bénin';
        }
    }

    public function submit()
    {
        $this->validate();

        $cvPath = $this->cv->store('cvs', 'public');

        JobApplication::create([
            'user_id' => Auth::id(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position_applied' => $this->position_applied,
            'city' => $this->city,
            'country' => $this->country,
            'cover_letter' => $this->cover_letter,
            'cv_path' => $cvPath,
            'linkedin_url' => $this->linkedin_url,
            'portfolio_url' => $this->portfolio_url,
            'years_of_experience' => $this->years_of_experience,
            'education_level' => $this->education_level,
            'current_company' => $this->current_company,
            'expected_salary' => $this->expected_salary,
            'availability' => $this->availability,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Votre candidature a été envoyée avec succès ! Nous vous contacterons bientôt.');

        $this->reset(['position_applied', 'cover_letter', 'cv', 'linkedin_url', 'portfolio_url', 
                      'years_of_experience', 'education_level', 'current_company', 'expected_salary']);
    }

    public function render()
    {
        return view('livewire.pages.carriere')
            ->extends('layouts.site', ['title' => 'Carrière'])
            ->section('content');
    }
}
