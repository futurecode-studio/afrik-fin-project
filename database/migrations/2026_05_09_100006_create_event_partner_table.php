<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('sponsorship_level', 50)->default('bronze');
            $table->text('benefits_description')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->timestamps();
            $table->index(['event_id','sponsorship_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_partner');
    }
};
