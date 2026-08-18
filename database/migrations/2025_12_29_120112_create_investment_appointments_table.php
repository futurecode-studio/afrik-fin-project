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
        Schema::create('investment_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('investment_type', ['actions_brvm', 'obligations', 'fcp', 'gestion_mandat', 'institutionnel', 'mise_en_relation'])->comment('Type de demande');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->decimal('investment_amount', 15, 2)->nullable()->comment('Montant estimé de l\'investissement');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('preferred_date')->nullable();
            $table->timestamp('confirmed_date')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'investment_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_appointments');
    }
};
