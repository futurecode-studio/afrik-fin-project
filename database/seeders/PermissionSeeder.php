<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Modules permissions
        $modules = [
            'dashboard',
            'users',
            'articles',
            'formations',
            'partners',
            'team',
            'stock-data',
            'government-bonds',
            'transactions',
            'appointments',
            'newsletters',
            'contacts',
            'statistics',
        ];

        // Action permissions
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
            }
            // Full access
            Permission::firstOrCreate(['name' => "{$module}.*"]);
        }

        // Create roles
        $roles = [
            'super_admin' => '*', // Full access
            'admin' => ['dashboard.view', 'dashboard.*', 'users.view', 'users.*', 'articles.*', 'formations.*', 'partners.*', 'team.*', 'stock-data.view', 'government-bonds.*', 'transactions.view', 'appointments.*', 'newsletters.*', 'contacts.*', 'statistics.view'],
            'responsable_comptable' => ['dashboard.view', 'transactions.view', 'transactions.create', 'transactions.edit', 'government-bonds.view', 'government-bonds.create', 'government-bonds.edit'],
            'directeur_general' => '*',
            'directrice_adjointe' => ['dashboard.view', 'dashboard.*', 'articles.*', 'formations.*', 'partners.view', 'partners.create', 'team.view', 'team.create', 'team.edit', 'stock-data.view', 'appointments.*', 'newsletters.*', 'statistics.view'],
            'analyste_financier' => ['dashboard.view', 'stock-data.view', 'government-bonds.view', 'transactions.view', 'statistics.view'],
            'responsable_formation' => ['dashboard.view', 'formations.*', 'newsletters.*'],
            'chargee_clientele' => ['dashboard.view', 'partners.view', 'team.view', 'appointments.view', 'appointments.create'],
            'charge_clientele' => ['dashboard.view', 'partners.view', 'team.view', 'appointments.view', 'appointments.create'],
            'client' => [],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            if ($permissions === '*') {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($permissions);
            }
        }
    }
}