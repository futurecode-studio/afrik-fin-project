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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_quiz_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->enum('type', ['choix_unique', 'choix_multiple', 'vrai_faux'])->default('choix_unique');
            $table->text('explication')->nullable(); // Explication de la bonne réponse
            $table->integer('points')->default(1);
            $table->integer('ordre')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
