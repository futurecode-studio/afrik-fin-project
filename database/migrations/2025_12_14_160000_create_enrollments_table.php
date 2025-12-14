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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('formation_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('progress')->default(0); // Pourcentage de progression
            $table->timestamps();
            $table->softDeletes();

            // Un utilisateur ne peut s'inscrire qu'une seule fois à une formation
            $table->unique(['user_id', 'formation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
