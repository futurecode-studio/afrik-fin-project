<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Profile extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $country;
    public $postal_code;
    public $date_of_birth;
    public $gender;
    
    public $current_password;
    public $password;
    public $password_confirmation;
    
    public $showPasswordSection = false;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->city = $user->city;
        $this->country = $user->country;
        $this->postal_code = $user->postal_code;
        $this->date_of_birth = $user->date_of_birth?->format('Y-m-d');
        $this->gender = $user->gender;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::id())],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'phone.max' => 'Le téléphone ne doit pas dépasser 20 caractères',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui',
            'gender.in' => 'Le genre sélectionné n\'est pas valide',
        ];
    }

    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
        ]);

        session()->flash('message', 'Profil mis à jour avec succès');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire',
            'password.required' => 'Le nouveau mot de passe est obligatoire',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Le mot de passe actuel est incorrect');
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->showPasswordSection = false;

        session()->flash('message', 'Mot de passe modifié avec succès');
    }

    public function togglePasswordSection()
    {
        $this->showPasswordSection = !$this->showPasswordSection;
        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.profile')
            ->extends('layouts.admin', ['title' => 'Mon Profil'])
            ->section('content');
    }
}
