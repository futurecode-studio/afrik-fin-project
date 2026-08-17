<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('company');
            $table->string('company_short')->nullable();
            $table->string('category');
            $table->string('country')->default('Bénin');
            $table->decimal('origin_nav', 15, 2)->nullable();
            $table->decimal('current_nav', 15, 2)->nullable();
            $table->decimal('variation_origin', 8, 2)->nullable();
            $table->date('vl_date')->nullable();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->string('flyer')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
            $table->index('category');
            $table->index('company');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
