<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('formation_forum_posts')) {
            Schema::create('formation_forum_posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('module_lesson_id')->nullable()->constrained('module_lessons')->nullOnDelete();
                $table->foreignId('parent_id')->nullable()->constrained('formation_forum_posts')->cascadeOnDelete();
                $table->string('title')->nullable();
                $table->text('body');
                $table->boolean('is_pinned')->default(false);
                $table->timestamps();
                $table->index(['formation_id', 'parent_id']);
            });
        }

        if (! Schema::hasTable('instructor_questions')) {
            Schema::create('instructor_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
                $table->string('subject');
                $table->text('body');
                $table->string('status', 30)->default('open'); // open, answered, closed
                $table->text('answer')->nullable();
                $table->timestamp('answered_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('formation_reviews')) {
            Schema::create('formation_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
                $table->unsignedTinyInteger('rating_overall')->default(5);
                $table->unsignedTinyInteger('rating_content')->nullable();
                $table->unsignedTinyInteger('rating_instructor')->nullable();
                $table->unsignedTinyInteger('rating_difficulty')->nullable();
                $table->unsignedTinyInteger('rating_materials')->nullable();
                $table->text('comment')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'formation_id']);
            });
        }

        if (! Schema::hasTable('formation_resources')) {
            Schema::create('formation_resources', function (Blueprint $table) {
                $table->id();
                $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
                $table->foreignId('formation_module_id')->nullable()->constrained()->nullOnDelete();
                $table->string('title');
                $table->string('type', 40)->default('pdf'); // pdf, link, video, zip
                $table->string('url');
                $table->string('file_size')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('formation_favorites')) {
            Schema::create('formation_favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('module_lesson_id')->nullable()->constrained('module_lessons')->cascadeOnDelete();
                $table->foreignId('article_id')->nullable()->constrained()->cascadeOnDelete();
                $table->string('label')->nullable();
                $table->timestamps();
                $table->index(['user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('formation_favorites');
        Schema::dropIfExists('formation_resources');
        Schema::dropIfExists('formation_reviews');
        Schema::dropIfExists('instructor_questions');
        Schema::dropIfExists('formation_forum_posts');
    }
};
