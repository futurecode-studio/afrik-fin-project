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
        Schema::create('market_index_history', function (Blueprint $table) {
            $table->id();
            $table->string('index_name', 100); // 'BRVM Composite', 'BRVM 30', etc.
            $table->date('snapshot_date');
            $table->decimal('value', 15, 4);
            $table->decimal('variation_percent', 8, 4)->nullable();
            $table->string('source', 50)->nullable(); // 'richbourse', 'brvm', 'manual'
            $table->timestamps();

            // Un seul snapshot par indice par jour
            $table->unique(['index_name', 'snapshot_date']);
            $table->index(['index_name', 'snapshot_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_index_history');
    }
};
