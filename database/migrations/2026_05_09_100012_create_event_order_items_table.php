<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('event_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('event_products');
            $table->foreignId('variant_id')->nullable()->constrained('event_product_variants');
            $table->string('product_name');
            $table->decimal('unit_price', 12, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_order_items');
    }
};
