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
        Schema::create('module_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_module_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->longText('contenu')->nullable(); // Contenu HTML de la leçon
            $table->string('video_url')->nullable(); // URL vidéo YouTube/Vimeo
            $table->string('duree_estimee')->nullable(); // ex: "15 min"
            $table->integer('ordre')->default(0);
            $table->enum('type', ['texte', 'video', 'mixte'])->default('texte');
            $table->boolean('is_active')->default(true);
            $table->json('ressources')->nullable(); // Fichiers PDF, liens, etc.
            $table->timestamps();
            
            $table->unique(['formation_module_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_lessons');
    }
};
