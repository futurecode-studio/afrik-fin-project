<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('event_products')->cascadeOnDelete();
            $table->string('sku', 100)->unique();
            $table->string('variant_name');
            $table->string('size', 20)->nullable();
            $table->string('color', 30)->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('reserved_quantity')->default(0);
            $table->timestamps();
            $table->index(['product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_product_variants');
    }
};
