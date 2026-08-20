<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('event_registrations')) {
            return;
        }

        if (! Schema::hasColumn('event_registrations', 'reminder_7_days_sent_at')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                $table->timestamp('reminder_7_days_sent_at')->nullable()->after('cancelled_at');
            });
        }

        if (! Schema::hasColumn('event_registrations', 'reminder_1_day_sent_at')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                $table->timestamp('reminder_1_day_sent_at')->nullable()->after('reminder_7_days_sent_at');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('event_registrations')) {
            return;
        }

        if (Schema::hasColumn('event_registrations', 'reminder_1_day_sent_at')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                $table->dropColumn('reminder_1_day_sent_at');
            });
        }

        if (Schema::hasColumn('event_registrations', 'reminder_7_days_sent_at')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                $table->dropColumn('reminder_7_days_sent_at');
            });
        }
    }
};
