<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class Profile extends Component
{
    use WithSweetAlert;

    public $name;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $country;

    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->city = $user->city;
        $this->country = $user->country;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        Auth::user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
        ]);

        $this->swalSuccess('Profil mis à jour avec succès.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        $this->swalSuccess('Mot de passe mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.client.profile')
            ->extends('layouts.client', ['title' => 'Mon Profil'])
            ->section('content');
    }
}
