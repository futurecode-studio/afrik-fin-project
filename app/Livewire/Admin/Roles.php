<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showPermissionsModal = false;
    public $editMode = false;
    public $roleId;
    public $roleName = '';
    public $selectedPermissions = [];
    public $availablePermissions = [];

    protected $paginationTheme = 'tailwind';

    public function rules()
    {
        return [
            'roleName' => 'required|string|max:255|unique:roles,name,' . ($this->roleId ?? 'NULL'),
        ];
    }

    public function messages()
    {
        return [
            'roleName.required' => 'Le nom du rôle est obligatoire',
            'roleName.unique' => 'Ce rôle existe déjà',
        ];
    }

    public function render()
    {
        $roles = Role::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->paginate(10);

        return view('livewire.admin.roles', [
            'roles' => $roles,
        ])
            ->extends('layouts.admin', ['title' => 'Rôles et Permissions'])
            ->section('content');
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
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->roleName]);
            session()->flash('message', 'Rôle modifié avec succès');
        } else {
            Role::create(['name' => $this->roleName]);
            session()->flash('message', 'Rôle créé avec succès');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        if (in_array($role->name, ['super_admin', 'admin', 'client'])) {
            session()->flash('error', 'Ce rôle ne peut pas être supprimé');
            return;
        }
        $role->delete();
        session()->flash('message', 'Rôle supprimé avec succès');
    }

    public function managePermissions($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        
        // Get all permissions grouped by module
        $allPermissions = Permission::all()->pluck('name')->toArray();
        $grouped = [];
        foreach ($allPermissions as $perm) {
            $module = explode('.', $perm)[0];
            $grouped[$module][] = $perm;
        }
        $this->availablePermissions = $grouped;
        
        $this->showPermissionsModal = true;
    }

    public function savePermissions()
    {
        $role = Role::findOrFail($this->roleId);
        $role->syncPermissions($this->selectedPermissions);
        session()->flash('message', 'Permissions du rôle mises à jour');
        $this->showPermissionsModal = false;
    }

    public function resetForm()
    {
        $this->roleId = null;
        $this->roleName = '';
        $this->selectedPermissions = [];
    }
}