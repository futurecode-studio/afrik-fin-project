<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('module_lessons', function (Blueprint $table) {
            if (! Schema::hasColumn('module_lessons', 'audio_url')) {
                $table->string('audio_url', 500)->nullable()->after('video_url');
            }
            if (! Schema::hasColumn('module_lessons', 'pdf_url')) {
                $table->string('pdf_url', 500)->nullable()->after('audio_url');
            }
            if (! Schema::hasColumn('module_lessons', 'consigne')) {
                $table->text('consigne')->nullable()->after('contenu');
            }
        });

        // Étendre l'enum MySQL des types de leçon
        try {
            \Illuminate\Support\Facades\DB::statement(
                "ALTER TABLE module_lessons MODIFY COLUMN type ENUM('texte','video','audio','pdf','mixte','exercice') NOT NULL DEFAULT 'texte'"
            );
        } catch (\Throwable $e) {
            // SQLite / déjà modifié
        }

        if (! Schema::hasTable('lesson_notes')) {
            Schema::create('lesson_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('module_lesson_id')->constrained('module_lessons')->cascadeOnDelete();
                $table->text('body');
                $table->timestamps();
                $table->index(['user_id', 'module_lesson_id']);
            });
        }

        if (! Schema::hasTable('lesson_exercise_submissions')) {
            Schema::create('lesson_exercise_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
                $table->foreignId('module_lesson_id')->constrained('module_lessons')->cascadeOnDelete();
                $table->text('answer_text')->nullable();
                $table->string('file_path')->nullable();
                $table->string('file_name')->nullable();
                $table->string('status', 30)->default('submitted'); // submitted, reviewing, corrected
                $table->decimal('score', 5, 2)->nullable();
                $table->decimal('max_score', 5, 2)->default(20);
                $table->text('feedback')->nullable();
                $table->string('annotated_file_path')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('corrected_at')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'module_lesson_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_exercise_submissions');
        Schema::dropIfExists('lesson_notes');
        Schema::table('module_lessons', function (Blueprint $table) {
            foreach (['audio_url', 'pdf_url', 'consigne'] as $col) {
                if (Schema::hasColumn('module_lessons', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
