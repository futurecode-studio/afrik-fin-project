<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('partners')) {
            return;
        }

        DB::table('partners')
            ->whereIn('nom', ['Apicassur', 'Africa Bourse'])
            ->update([
                'nom' => 'Africabourse',
                'type' => 'SGI',
                'website' => 'https://africabourse.com',
                'logo' => 'assets/images/africa-bourse.png',
                'updated_at' => now(),
            ]);

        DB::table('partners')
            ->whereIn('nom', ['ASCOT', 'AASCOT'])
            ->update([
                'is_active' => false,
                'is_featured' => false,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Pas de retour arrière automatique.
    }
};
