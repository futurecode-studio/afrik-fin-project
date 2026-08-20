<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')
                ->where('name', 'like', 'event_orders.%')
                ->orWhere('name', 'like', 'event_products.%')
                ->delete();
        }

        Schema::dropIfExists('event_order_items');
        Schema::dropIfExists('event_orders');
        Schema::dropIfExists('event_product_variants');
        Schema::dropIfExists('event_products');
    }

    public function down(): void
    {
        if (! Schema::hasTable('event_products')) {
            Schema::create('event_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('event_product_variants')) {
            Schema::create('event_product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('event_products')->cascadeOnDelete();
                $table->string('sku')->nullable();
                $table->string('variant_name');
                $table->decimal('price', 12, 2)->nullable();
                $table->string('size')->nullable();
                $table->string('color')->nullable();
                $table->unsignedInteger('stock_quantity')->default(0);
                $table->unsignedInteger('reserved_quantity')->default(0);
                $table->timestamps();
                $table->index(['product_id']);
            });
        }

        if (! Schema::hasTable('event_orders')) {
            Schema::create('event_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events');
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->foreignId('registration_id')->nullable()->constrained('event_registrations');
                $table->string('order_number')->unique();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('tax', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->string('currency', 3)->default('XOF');
                $table->string('status')->default('pending');
                $table->text('notes')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('event_order_items')) {
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
    }
};
