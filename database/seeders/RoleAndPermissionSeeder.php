<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create all permissions for each module
        $modules = [
            'users', 'articles', 'formations', 'partners', 'team', 'roles',
            'stock-data', 'government-bonds', 'transactions', 'appointments',
            'newsletters', 'contacts', 'statistics', 'dashboard',
            'events', 'event_registrations', 'event_orders', 'event_products', 'event_checkin'
        ];

        $actions = ['view', 'create', 'edit', 'delete'];
        
        $allPermissions = [];
        
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $perm = "{$module}.{$action}";
                Permission::firstOrCreate(['name' => $perm]);
                $allPermissions[] = $perm;
            }
            Permission::firstOrCreate(['name' => "{$module}.*"]);
            $allPermissions[] = "{$module}.*";
        }

        // Alias métier pour l’émargement QR
        Permission::firstOrCreate(['name' => 'event_checkin.manage']);
        $allPermissions[] = 'event_checkin.manage';

        // Create all roles
        $roles = [
            'super_admin' => '*',
            'admin' => $allPermissions,
            'responsable_comptable' => [
                'dashboard.view', 'transactions.view', 'transactions.create', 'transactions.edit',
                'government-bonds.view', 'government-bonds.create', 'government-bonds.edit'
            ],
            'directeur_general' => $allPermissions,
            'directrice_adroite' => [
                'dashboard.view', 'dashboard.*', 'articles.*', 'formations.*',
                'partners.view', 'partners.create', 'team.view', 'team.create', 'team.edit',
                'stock-data.view', 'appointments.*', 'newsletters.*', 'statistics.view',
                'events.*', 'event_registrations.*', 'event_orders.view', 'event_products.view'
            ],
            'analyste_financier' => [
                'dashboard.view', 'stock-data.view', 'government-bonds.view', 'transactions.view', 'statistics.view'
            ],
            'responsable_formation' => [
                'dashboard.view', 'formations.*', 'newsletters.*',
                'events.view', 'events.create', 'events.edit',
                'event_registrations.view', 'event_registrations.edit',
                'event_checkin.*'
            ],
            'chargee_clientele' => [
                'dashboard.view', 'partners.view', 'team.view', 'appointments.view', 'appointments.create',
                'events.view',
                'event_registrations.view', 'event_registrations.create',
                'event_checkin.*',
                'event_orders.view'
            ],
            'charge_clientele' => [
                'dashboard.view', 'partners.view', 'team.view', 'appointments.view', 'appointments.create',
                'events.view',
                'event_registrations.view', 'event_registrations.create',
                'event_checkin.*',
                'event_orders.view'
            ],
            'client' => ['dashboard.view'],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            if ($permissions === '*') {
                $role->givePermissionTo(Permission::all());
            } else {
                $role->givePermissionTo($permissions);
            }
        }

        $this->command->info('Rôles et permissions créés avec succès!');
    }
}