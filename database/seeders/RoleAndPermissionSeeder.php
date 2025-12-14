<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            'manage-users',
            'manage-roles',
            'manage-permissions',
            'view-dashboard',
            'edit-profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $clientRole = Role::firstOrCreate(['name' => 'client']);

        // Assigner les permissions aux rôles
        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo(['view-dashboard', 'edit-profile']);
        $clientRole->givePermissionTo(['view-dashboard', 'edit-profile']);

        $this->command->info('Rôles et permissions créés avec succès!');
    }
}
