<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            ArticleCompleteSeeder::class,
            FormationCompleteSeeder::class,
            StockSeeder::class,
            TransactionSeeder::class,
            // GovernmentBondSeeder retiré volontairement : les obligations
            // sont désormais 100% dynamiques via la commande `php artisan umoa:sync`
            // (source : UMOA-Titres). Conserver le seeder en base statique produisait
            // des données fictives présentées comme réelles.
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
