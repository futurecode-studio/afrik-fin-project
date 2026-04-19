<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('government_bonds', function (Blueprint $table) {
            // Traçabilité des sources (UMOA-Titres, BCEAO, saisie manuelle, etc.)
            $table->string('data_source', 50)->nullable()->after('description');
            $table->string('source_url', 500)->nullable()->after('data_source');
            $table->timestamp('last_synced_at')->nullable()->after('source_url');

            // Date d'adjudication (émission primaire sur UMOA-Titres)
            $table->date('auction_date')->nullable()->after('issue_date');

            // Code externe utilisé par UMOA-Titres (numéro d'adjudication)
            $table->string('external_code', 100)->nullable()->after('isin_code');

            $table->index('data_source');
            $table->index('maturity_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('government_bonds', function (Blueprint $table) {
            $table->dropIndex(['data_source']);
            $table->dropIndex(['maturity_date']);
            $table->dropColumn(['data_source', 'source_url', 'last_synced_at', 'auction_date', 'external_code']);
        });
    }
};
