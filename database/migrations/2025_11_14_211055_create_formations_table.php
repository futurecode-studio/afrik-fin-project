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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description_courte')->nullable();
            $table->longText('description_complete');
            $table->string('niveau')->default('debutant'); // débutant, intermédiaire, avancé
            $table->string('duree')->nullable(); // ex: "8 semaines", "3 mois"
            $table->decimal('prix', 10, 2)->default(0);
            $table->string('image_url')->nullable();
            $table->string('statut')->default('brouillon'); // brouillon, publie, archive
            $table->timestamp('published_at')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
