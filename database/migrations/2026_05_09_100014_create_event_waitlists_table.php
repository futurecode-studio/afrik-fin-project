<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('status')->default('waiting'); // waiting, converted, cancelled
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            $table->index(['event_id','status','position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_waitlists');
    }
};
