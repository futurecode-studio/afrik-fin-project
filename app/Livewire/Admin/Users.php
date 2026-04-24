<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    
    // Propriétés du formulaire
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'admin';
    public $phone;
    public $address;
    public $city;
    public $country = 'Bénin';
    public $postal_code;
    public $date_of_birth;
    public $gender;
    public $is_active = true;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => $this->editMode ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'role' => 'required|in:admin,client',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Le nom est obligatoire',
        'email.required' => 'L\'email est obligatoire',
        'email.email' => 'L\'email doit être valide',
        'email.unique' => 'Cet email est déjà utilisé',
        'password.required' => 'Le mot de passe est obligatoire',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        'password.confirmed' => 'Les mots de passe ne correspondent pas',
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

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->city = $user->city;
        $this->country = $user->country;
        $this->postal_code = $user->postal_code;
        $this->date_of_birth = $user->date_of_birth?->format('Y-m-d');
        $this->gender = $user->gender;
        $this->is_active = $user->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->editMode) {
            $user = User::findOrFail($this->userId);
            $user->update($userData);
            session()->flash('message', 'Utilisateur modifié avec succès');
        } else {
            User::create($userData);
            session()->flash('message', 'Utilisateur créé avec succès');
            $this->resetPage(); // Retour à la première page
        }

        $this->closeModal();
        $this->dispatch('user-saved'); // Événement pour actualisation
    }

    public function confirmDelete($id)
    {
        $this->userId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $user = User::findOrFail($this->userId);
        $user->delete();
        $this->dispatch('user-saved'); // Événement pour actualisation
        
        session()->flash('message', 'Utilisateur supprimé avec succès');
        $this->showDeleteModal = false;
        $this->userId = null;
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        
        session()->flash('message', 'Utilisateur restauré avec succès');
        $this->dispatch('user-saved'); // Événement pour actualisation
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'admin';
        $this->phone = '';
        $this->address = '';
        $this->city = '';
        $this->country = 'Bénin';
        $this->postal_code = '';
        $this->date_of_birth = '';
        $this->gender = '';
        $this->is_active = true;
    }

    public function render()
    {
        $users = User::query()
            ->with('roles') // Eager load Spatie roles
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->withTrashed()
            ->latest()
            ->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users
        ])
            ->extends('layouts.admin', ['title' => 'Utilisateurs'])
            ->section('content');
    }
}
