<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('event_registrations');
            $table->foreignId('checked_in_by')->nullable()->constrained('users');
            $table->string('method')->default('qr_scan');
            $table->string('device_id')->nullable();
            $table->timestamp('checked_in_at');
            $table->timestamps();
            $table->unique(['registration_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_check_ins');
    }
};
