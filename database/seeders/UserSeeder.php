<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrateur
        $admin1 = User::create([
            'name' => 'Administrateur Afri-Fin',
            'email' => 'contact@africainedesfinances.com',
            'email_verified_at' => now(),
            'password' => Hash::make('contact@africainedesfinances.com'),
            'role' => 'admin',
            'phone' => '+229 97 12 34 56',
            'address' => 'Zone administrative',
            'city' => 'Cotonou',
            'country' => 'Bénin',
            'postal_code' => '01BP1234',
            'date_of_birth' => '1985-06-15',
            'gender' => 'male',
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $admin1->assignRole('admin');

        // Utilisateur admin
        $admin2 = User::create([
            'name' => 'codi anselme',
            'email' => 'codianselme@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('codianselme@gmail.com'),
            'role' => 'admin',
            'phone' => '+229 96 45 67 89',
            'address' => 'Quartier Akpakpa',
            'city' => 'Cotonou',
            'country' => 'Bénin',
            'postal_code' => '01BP5678',
            'date_of_birth' => '1990-03-22',
            'gender' => 'male',
            'is_active' => true,
            'last_login_at' => now()->subDays(2),
        ]);
        $admin2->assignRole('admin');
    }
}
