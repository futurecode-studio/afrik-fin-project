<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            if (! Schema::hasColumn('team_members', 'is_leadership')) {
                $table->boolean('is_leadership')->default(false)->after('is_active');
            }
        });

        // Dirigeants : hors chargés de clientèle
        DB::table('team_members')
            ->where('poste', 'not like', '%Clientèle%')
            ->where('poste', 'not like', '%clientèle%')
            ->update(['is_leadership' => true]);
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            if (Schema::hasColumn('team_members', 'is_leadership')) {
                $table->dropColumn('is_leadership');
            }
        });
    }
};
