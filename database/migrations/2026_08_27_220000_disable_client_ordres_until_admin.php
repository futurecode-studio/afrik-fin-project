<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('feature_flags')) {
            return;
        }

        DB::table('feature_flags')
            ->whereIn('key', ['client.ordres', 'marches.carnet'])
            ->update([
                'enabled' => false,
                'updated_at' => now(),
            ]);

        DB::table('feature_flags')
            ->where('key', 'client.ordres')
            ->update([
                'label' => 'Souscriptions (espace client)',
                'description' => 'Souscriptions directes et intentions relayées vers une SGI agréée. Désactivé tant que les liens partenaires ne sont pas prêts.',
                'updated_at' => now(),
            ]);

        Cache::forget('feature_flags.map');
    }

    public function down(): void
    {
        // Pas de réactivation automatique au rollback.
    }
};
