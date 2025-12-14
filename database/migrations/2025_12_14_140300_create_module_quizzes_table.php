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
        Schema::create('module_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_module_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('duree_minutes')->nullable(); // Durée limite en minutes
            $table->integer('score_minimum')->default(70); // Score minimum pour valider (%)
            $table->integer('tentatives_max')->default(3); // Nombre max de tentatives
            $table->boolean('is_active')->default(true);
            $table->boolean('afficher_corrections')->default(true); // Afficher les corrections après
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_quizzes');
    }
};
