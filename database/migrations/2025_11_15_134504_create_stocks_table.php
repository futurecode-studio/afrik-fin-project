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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique(); // Symbole boursier (ex: SNTS, BOAB)
            $table->string('company_name'); // Nom de l'entreprise
            $table->decimal('current_price', 15, 2); // Prix actuel
            $table->decimal('previous_price', 15, 2)->nullable(); // Prix précédent
            $table->decimal('variation_percent', 8, 2)->default(0); // Variation en %
            $table->bigInteger('volume')->default(0); // Volume de transactions
            $table->decimal('market_cap', 20, 2)->nullable(); // Capitalisation boursière (en millions)
            $table->string('sector')->nullable(); // Secteur d'activité
            $table->decimal('high_price', 15, 2)->nullable(); // Plus haut du jour
            $table->decimal('low_price', 15, 2)->nullable(); // Plus bas du jour
            $table->boolean('is_active')->default(true); // Titre actif ou non
            $table->timestamp('last_updated')->nullable(); // Dernière mise à jour des données
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
