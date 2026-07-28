<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('market_alerts')) {
            Schema::create('market_alerts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('stock_id')->nullable()->constrained()->nullOnDelete();
                $table->string('asset_label')->nullable();
                $table->string('asset_category', 40)->default('action'); // action, obligation, indice, autre
                $table->string('trigger_type', 60); // price_above, price_below, rsi, volume, calendar
                $table->decimal('threshold', 18, 4)->nullable();
                $table->string('severity', 20)->default('normale'); // faible, normale, critique
                $table->string('status', 30)->default('active'); // active, triggered, planned, paused
                $table->string('channel', 40)->default('in_app'); // in_app, email, sms
                $table->text('notes')->nullable();
                $table->timestamp('triggered_at')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'status']);
            });
        }

        if (! Schema::hasTable('scheduled_orders')) {
            Schema::create('scheduled_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
                $table->string('condition_type', 40)->default('threshold'); // threshold, oco, trailing, linked
                $table->string('side', 10)->default('buy');
                $table->decimal('quantity', 18, 4);
                $table->decimal('target_price', 15, 2)->nullable();
                $table->decimal('stop_loss', 15, 2)->nullable();
                $table->decimal('take_profit', 15, 2)->nullable();
                $table->boolean('protection_active')->default(true);
                $table->string('status', 30)->default('pending'); // pending, triggered, cancelled
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'status']);
            });
        }

        if (! Schema::hasTable('structured_products')) {
            Schema::create('structured_products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('isin', 32)->nullable();
                $table->string('mnemonic', 40)->nullable();
                $table->string('product_type', 60)->default('certificat'); // certificat, phoenix, autocall, bonus
                $table->string('underlying')->nullable();
                $table->decimal('current_price', 15, 2)->nullable();
                $table->decimal('variation_percent', 8, 2)->nullable();
                $table->decimal('strike', 15, 2)->nullable();
                $table->decimal('barrier', 15, 2)->nullable();
                $table->decimal('cap', 15, 2)->nullable();
                $table->decimal('distance_to_barrier_pct', 8, 2)->nullable();
                $table->decimal('coupon_memorized', 15, 2)->nullable();
                $table->date('maturity_date')->nullable();
                $table->date('next_autocall_date')->nullable();
                $table->decimal('autocall_threshold_pct', 8, 2)->nullable();
                $table->unsignedTinyInteger('risk_level')->default(4); // 1-7
                $table->text('description')->nullable();
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        // Seed demo structured products + AG if empty
        if (Schema::hasTable('structured_products') && DB::table('structured_products')->count() === 0) {
            DB::table('structured_products')->insert([
                [
                    'name' => 'Certificat Rendement Afrique de l’Ouest v.24',
                    'slug' => 'afri-cert-24',
                    'isin' => 'CI0000001234',
                    'mnemonic' => 'AFRI-CERT-24',
                    'product_type' => 'certificat',
                    'underlying' => 'Panier BRVM Ouest',
                    'current_price' => 1245.50,
                    'variation_percent' => 1.24,
                    'strike' => 1000,
                    'barrier' => 850,
                    'cap' => 1450,
                    'distance_to_barrier_pct' => 31.7,
                    'coupon_memorized' => null,
                    'maturity_date' => '2026-12-15',
                    'next_autocall_date' => null,
                    'autocall_threshold_pct' => null,
                    'risk_level' => 5,
                    'description' => 'Certificat Bonus Cappé avec barrière de protection active en observation continue.',
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Phoenix Africa AI 2026',
                    'slug' => 'phoenix-africa-ai-2026',
                    'isin' => 'XS000PHOENIX',
                    'mnemonic' => 'PHX-AI-26',
                    'product_type' => 'phoenix',
                    'underlying' => 'Indice Africa AI',
                    'current_price' => 62.5,
                    'variation_percent' => -1.1,
                    'strike' => 100,
                    'barrier' => 60,
                    'cap' => null,
                    'distance_to_barrier_pct' => 4.2,
                    'coupon_memorized' => 18500,
                    'maturity_date' => '2026-06-30',
                    'next_autocall_date' => '2026-09-15',
                    'autocall_threshold_pct' => 100,
                    'risk_level' => 6,
                    'description' => 'Phoenix avec mémoire de coupons et barrière de protection à 60%.',
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Autocall Emerging Markets ESG',
                    'slug' => 'autocall-em-esg',
                    'isin' => 'XS000AUTOESG',
                    'mnemonic' => 'AC-ESG',
                    'product_type' => 'autocall',
                    'underlying' => 'MSCI EM ESG',
                    'current_price' => 88.5,
                    'variation_percent' => 0.6,
                    'strike' => 100,
                    'barrier' => 70,
                    'cap' => null,
                    'distance_to_barrier_pct' => 18.5,
                    'coupon_memorized' => 12600,
                    'maturity_date' => '2027-03-01',
                    'next_autocall_date' => '2026-10-01',
                    'autocall_threshold_pct' => 95,
                    'risk_level' => 5,
                    'description' => 'Autocall ESG marchés émergents avec seuil de rappel à 95%.',
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_orders');
        Schema::dropIfExists('market_alerts');
        Schema::dropIfExists('structured_products');
    }
};
