<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'client',
            'directeur_general',
            'directrice_adjointe',
            'analyste_financier',
            'responsable_formation',
            'chargee_clientele',
            'charge_clientele',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}