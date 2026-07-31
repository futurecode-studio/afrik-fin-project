<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Codi Anselme',
                'email' => 'codianselme@gmail.com',
                'password' => 'codianselme@gmail.com',
                'phone' => '+229 01 00 00 00 00',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Administrateur Principal',
                'email' => 'contact@africainedesfinances.com',
                'password' => 'contact@africainedesfinances.com',
                'phone' => '+229 01 44 21 82 09',
                'role' => 'admin',
            ],
            [
                'name' => 'Dr. Kofi Annan',
                'email' => 'k.annan@africainedesfinances.com',
                'password' => 'k.annan@africainedesfinances.com',
                'phone' => '+229 01 44 21 82 09',
                'role' => 'directeur_general',
            ],
            [
                'name' => 'Marie Doumbouya',
                'email' => 'm.doumbouya@africainedesfinances.com',
                'password' => 'm.doumbouya@africainedesfinances.com',
                'phone' => '+229 01 44 21 78 90',
                'role' => 'directrice_adroite',
            ],
            [
                'name' => 'Jules Bakari',
                'email' => 'j.bakari@africainedesfinances.com',
                'password' => 'j.bakari@africainedesfinances.com',
                'phone' => '+229 01 44 21 78 91',
                'role' => 'analyste_financier',
            ],
            [
                'name' => 'Fatou Diallo',
                'email' => 'f.diallo@africainedesfinances.com',
                'password' => 'f.diallo@africainedesfinances.com',
                'phone' => '+229 01 44 21 78 92',
                'role' => 'responsable_formation',
            ],
            [
                'name' => 'Georgia Gnancadja',
                'email' => 'g.gnancadja@africainedesfinances.com',
                'password' => 'g.gnancadja@africainedesfinances.com',
                'phone' => '+229 01 44 21 78 95',
                'role' => 'chargee_clientele',
            ],
            [
                'name' => 'Morel Agonsanou',
                'email' => 'm.agonsanou@africainedesfinances.com',
                'password' => 'm.agonsanou@africainedesfinances.com',
                'phone' => '+229 01 44 21 78 96',
                'role' => 'charge_clientele',
            ],
            [
                'name' => 'Ognondoun Cyrille',
                'email' => 'o.cyrille@africainedesfinances.com',
                'password' => 'o.cyrille@africainedesfinances.com',
                'phone' => '+229 01 44 21 78 97',
                'role' => 'charge_clientele',
            ],
            [
                'name' => 'Micheline Gloria Mountondji',
                'email' => 'm.mountondji@africainedesfinances.com',
                'password' => 'm.mountondji@africainedesfinances.com',
                'phone' => '+229 01 44 21 78 98',
                'role' => 'chargee_clientele',
            ],
            [
                'name' => 'Client Test',
                'email' => 'client@test.com',
                'password' => 'client@test.com',
                'phone' => '+229 01 02 03 04',
                'role' => 'client',
            ],
        ];

        foreach ($users as $userData) {
            $password = $userData['password'];
            $role = $userData['role'];
            
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'phone' => $userData['phone'],
                    'password' => Hash::make($password),
                    'is_active' => true,
                ]
            );
            
            $user->syncRoles($role);
        }
    }
}