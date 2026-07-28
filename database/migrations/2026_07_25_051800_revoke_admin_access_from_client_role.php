<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $client = Role::findByName('client');
        if ($client && Permission::where('name', 'dashboard.view')->exists()) {
            $client->revokePermissionTo('dashboard.view');
        }

        // Aucune permission admin pour le rôle client
        $client?->syncPermissions([]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $client = Role::findByName('client');
        $perm = Permission::findByName('dashboard.view');
        if ($client && $perm) {
            $client->givePermissionTo($perm);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
