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
        Schema::create('formation_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->string('duree_estimee')->nullable(); // ex: "2 heures", "30 min"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['formation_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_modules');
    }
};
