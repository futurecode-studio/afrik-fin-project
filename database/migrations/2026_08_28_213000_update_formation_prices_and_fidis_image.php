<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->string('price_label', 120)->nullable()->after('prix');
        });

        DB::table('formations')
            ->where('slug', 'formation-complete-finance-investissement-brvm')
            ->update(['prix' => 20000, 'duree' => null]);

        DB::table('formations')
            ->where('slug', 'certificat-professionnel-technique-bancaire-financiere')
            ->update([
                'prix' => 0,
                'price_label' => 'Voir catalogue',
                'image_url' => 'assets/images/formations/fidis-certificat-bancaire.png',
            ]);
    }

    public function down(): void
    {
        DB::table('formations')
            ->where('slug', 'formation-complete-finance-investissement-brvm')
            ->update(['prix' => 75000, 'duree' => '8 semaines']);

        DB::table('formations')
            ->where('slug', 'certificat-professionnel-technique-bancaire-financiere')
            ->update([
                'prix' => 20000,
                'price_label' => null,
                'image_url' => 'assets/images/formations/fidis-certificat-bancaire.png',
            ]);

        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn('price_label');
        });
    }
};
