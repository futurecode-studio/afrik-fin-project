<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('market_ipos')) {
            Schema::create('market_ipos', function (Blueprint $table) {
                $table->id();
                $table->string('company_name');
                $table->string('symbol', 20)->nullable();
                $table->string('sector')->nullable();
                $table->string('exchange', 20)->default('BRVM');
                $table->string('status', 40)->default('annonce'); // annonce, souscription, cloture, cote
                $table->decimal('offer_price_min', 15, 2)->nullable();
                $table->decimal('offer_price_max', 15, 2)->nullable();
                $table->unsignedBigInteger('shares_offered')->nullable();
                $table->date('subscription_start')->nullable();
                $table->date('subscription_end')->nullable();
                $table->date('listing_date')->nullable();
                $table->text('description')->nullable();
                $table->string('prospectus_url')->nullable();
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('portfolio_holdings')) {
            Schema::create('portfolio_holdings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('stock_id')->nullable()->constrained()->nullOnDelete();
                $table->string('label')->nullable(); // FCP / cash / autre
                $table->string('asset_type', 40)->default('action'); // action, fcp, obligation, cash
                $table->decimal('quantity', 18, 4)->default(0);
                $table->decimal('avg_cost', 15, 2)->nullable();
                $table->string('currency', 10)->default('XOF');
                $table->string('external_ref')->nullable(); // id FCP etc.
                $table->timestamps();
                $table->index(['user_id', 'asset_type']);
            });
        }

        if (! Schema::hasTable('stock_order_intents')) {
            Schema::create('stock_order_intents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
                $table->string('side', 10); // buy, sell
                $table->string('order_type', 20)->default('limit'); // limit, market
                $table->decimal('quantity', 18, 4);
                $table->decimal('limit_price', 15, 2)->nullable();
                $table->string('status', 30)->default('pending'); // pending, relayed, cancelled
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_order_intents');
        Schema::dropIfExists('portfolio_holdings');
        Schema::dropIfExists('market_ipos');
    }
};
