<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('is_featured');
        });

        // Marquer payants les événements qui ont déjà au moins un billet à prix > 0
        if (Schema::hasTable('event_ticket_types')) {
            $paidEventIds = DB::table('event_ticket_types')
                ->where('price', '>', 0)
                ->distinct()
                ->pluck('event_id');

            if ($paidEventIds->isNotEmpty()) {
                DB::table('events')->whereIn('id', $paidEventIds)->update(['is_paid' => true]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('is_paid');
        });
    }
};
