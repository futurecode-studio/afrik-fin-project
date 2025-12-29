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
        Schema::create('government_bonds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('issuer');
            $table->string('country');
            $table->string('isin_code')->unique()->nullable();
            $table->decimal('nominal_value', 15, 2);
            $table->string('currency', 10)->default('FCFA');
            $table->decimal('interest_rate', 5, 2);
            $table->string('interest_type')->default('fixed');
            $table->string('payment_frequency');
            $table->date('issue_date');
            $table->date('maturity_date');
            $table->integer('maturity_years');
            $table->decimal('current_price', 15, 2)->nullable();
            $table->decimal('yield_to_maturity', 5, 2)->nullable();
            $table->string('rating')->nullable();
            $table->text('description')->nullable();
            $table->string('risk_level')->default('medium');
            $table->decimal('minimum_investment', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_bonds');
    }
};
