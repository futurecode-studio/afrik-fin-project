<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('registration_id')->nullable()->constrained('event_registrations');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('currency', 3)->default('XOF');
            $table->string('status')->default('pending'); // pending, paid, processing, shipped, delivered, cancelled, refunded
            $table->string('payment_provider')->nullable();
            $table->string('payment_transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['event_id','status']);
            $table->index(['user_id','status']);
            $table->index(['order_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_orders');
    }
};
