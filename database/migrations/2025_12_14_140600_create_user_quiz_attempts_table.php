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
        Schema::create('user_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_quiz_id')->constrained()->onDelete('cascade');
            $table->integer('score')->default(0); // Score obtenu en %
            $table->integer('points_obtenus')->default(0);
            $table->integer('points_total')->default(0);
            $table->json('reponses')->nullable(); // Réponses de l'utilisateur
            $table->boolean('is_passed')->default(false); // A réussi le quiz
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_quiz_attempts');
    }
};
