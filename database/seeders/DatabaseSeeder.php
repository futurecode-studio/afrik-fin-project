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
            TeamMemberSeeder::class,
            ArticleCompleteSeeder::class,
            FormationCompleteSeeder::class,
            StockSeeder::class,
            TransactionSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
