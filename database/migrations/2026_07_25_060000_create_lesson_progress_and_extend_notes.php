<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('lesson_progress')) {
            Schema::create('lesson_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('module_lesson_id')->constrained('module_lessons')->cascadeOnDelete();
                $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedInteger('video_position')->default(0);
                $table->unsignedInteger('watched_seconds')->default(0);
                $table->unsignedInteger('duration_seconds')->nullable();
                $table->timestamp('last_watched_at')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'module_lesson_id']);
            });
        }

        if (Schema::hasTable('lesson_notes') && ! Schema::hasColumn('lesson_notes', 'video_seconds')) {
            Schema::table('lesson_notes', function (Blueprint $table) {
                $table->unsignedInteger('video_seconds')->nullable()->after('body');
            });
        }

        if (Schema::hasTable('module_lessons') && ! Schema::hasColumn('module_lessons', 'transcript')) {
            Schema::table('module_lessons', function (Blueprint $table) {
                $table->longText('transcript')->nullable()->after('contenu');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
        if (Schema::hasTable('lesson_notes') && Schema::hasColumn('lesson_notes', 'video_seconds')) {
            Schema::table('lesson_notes', function (Blueprint $table) {
                $table->dropColumn('video_seconds');
            });
        }
        if (Schema::hasTable('module_lessons') && Schema::hasColumn('module_lessons', 'transcript')) {
            Schema::table('module_lessons', function (Blueprint $table) {
                $table->dropColumn('transcript');
            });
        }
    }
};
