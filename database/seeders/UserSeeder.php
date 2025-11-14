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
        User::create([
            'name' => 'Administrateur Afri-Fin',
            'email' => 'admin@afrifin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
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

        // Utilisateur simple
        User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
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
    }
}
