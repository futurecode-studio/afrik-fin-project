<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('module_quizzes', function (Blueprint $table) {
            if (! Schema::hasColumn('module_quizzes', 'is_final')) {
                $table->boolean('is_final')->default(false)->after('afficher_corrections');
            }
        });
    }

    public function down(): void
    {
        Schema::table('module_quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('module_quizzes', 'is_final')) {
                $table->dropColumn('is_final');
            }
        });
    }
};
