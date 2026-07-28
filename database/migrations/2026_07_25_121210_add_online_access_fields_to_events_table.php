<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('online_platform', 50)->nullable()->after('country');
            $table->string('online_meeting_url', 500)->nullable()->after('online_platform');
            $table->string('online_meeting_id', 100)->nullable()->after('online_meeting_url');
            $table->string('online_meeting_passcode', 100)->nullable()->after('online_meeting_id');
            $table->text('online_access_instructions')->nullable()->after('online_meeting_passcode');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'online_platform',
                'online_meeting_url',
                'online_meeting_id',
                'online_meeting_passcode',
                'online_access_instructions',
            ]);
        });
    }
};
