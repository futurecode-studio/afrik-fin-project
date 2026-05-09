<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('ticket_type_id')->nullable()->constrained('event_ticket_types');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('t_shirt_size', 10)->nullable();
            $table->text('medical_notes')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('status')->default('registered'); // registered, confirmed, checked_in, cancelled, no_show
            $table->string('qr_code')->nullable()->unique();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->string('source')->default('web');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['event_id','email','deleted_at']);
            $table->index(['event_id','status']);
            $table->index(['qr_code']);
            $table->index(['user_id','event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
