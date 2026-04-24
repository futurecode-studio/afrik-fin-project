<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Auth;

trait HasPermissions
{
    public function can(string $permission): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }
        
        return $user->hasPermissionTo($permission);
    }

    public function canCreate(string $module): bool
    {
        return $this->can("{$module}.create");
    }

    public function canEdit(string $module): bool
    {
        return $this->can("{$module}.edit");
    }

    public function canDelete(string $module): bool
    {
        return $this->can("{$module}.delete");
    }
}