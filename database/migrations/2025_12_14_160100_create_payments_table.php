<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('formation_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // ID de transaction du provider
            $table->string('reference')->unique(); // Référence interne
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->enum('provider', ['kkiapay', 'fedapay', 'manual'])->default('kkiapay');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('provider_response')->nullable(); // Réponse complète du provider
            $table->string('payment_method')->nullable(); // mobile_money, card, etc.
            $table->string('phone')->nullable(); // Numéro de téléphone pour mobile money
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
