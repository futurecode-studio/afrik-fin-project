<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (! Schema::hasColumn('stocks', 'exchange')) {
                $table->string('exchange', 20)->default('BRVM')->after('symbol')->index();
            }
            if (! Schema::hasColumn('stocks', 'currency')) {
                $table->string('currency', 10)->default('XOF')->after('exchange');
            }
            if (! Schema::hasColumn('stocks', 'open_price')) {
                $table->decimal('open_price', 15, 2)->nullable()->after('current_price');
            }
            if (! Schema::hasColumn('stocks', 'change_amount')) {
                $table->decimal('change_amount', 15, 4)->nullable()->after('previous_price');
            }
            if (! Schema::hasColumn('stocks', 'source')) {
                $table->string('source', 40)->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('stocks', 'source_updated_at')) {
                $table->timestamp('source_updated_at')->nullable()->after('source');
            }
        });

        // Remplacer unique(symbol) par unique(symbol, exchange) si possible
        $this->dropSymbolUniqueIfExists();

        Schema::table('stocks', function (Blueprint $table) {
            $table->unique(['symbol', 'exchange'], 'stocks_symbol_exchange_unique');
        });

        if (! Schema::hasTable('stock_prices')) {
            Schema::create('stock_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('stock_id')->constrained('stocks')->cascadeOnDelete();
                $table->decimal('price', 15, 2);
                $table->decimal('open', 15, 2)->nullable();
                $table->decimal('high', 15, 2)->nullable();
                $table->decimal('low', 15, 2)->nullable();
                $table->bigInteger('volume')->default(0);
                $table->decimal('change_amount', 15, 4)->nullable();
                $table->decimal('change_percent', 8, 4)->nullable();
                $table->timestamp('recorded_at')->index();
                $table->timestamps();

                $table->index(['stock_id', 'recorded_at']);
            });
        }

        if (! Schema::hasTable('market_indices')) {
            Schema::create('market_indices', function (Blueprint $table) {
                $table->id();
                $table->string('code', 40);
                $table->string('name');
                $table->string('exchange', 20)->default('BRVM')->index();
                $table->string('currency', 10)->nullable();
                $table->decimal('value', 15, 4);
                $table->decimal('change', 15, 4)->nullable();
                $table->decimal('change_percent', 8, 4)->nullable();
                $table->string('source', 40)->nullable();
                $table->timestamp('source_updated_at')->nullable();
                $table->timestamps();

                $table->unique(['code', 'exchange']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_prices');
        Schema::dropIfExists('market_indices');

        Schema::table('stocks', function (Blueprint $table) {
            try {
                $table->dropUnique('stocks_symbol_exchange_unique');
            } catch (\Throwable) {
            }

            $cols = collect(['exchange', 'currency', 'open_price', 'change_amount', 'source', 'source_updated_at'])
                ->filter(fn ($col) => Schema::hasColumn('stocks', $col))
                ->all();

            if ($cols !== []) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->unique('symbol');
        });
    }

    private function dropSymbolUniqueIfExists(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        try {
            if ($driver === 'mysql') {
                $indexes = collect(DB::select('SHOW INDEX FROM stocks WHERE Key_name = ?', ['stocks_symbol_unique']));
                if ($indexes->isNotEmpty()) {
                    Schema::table('stocks', function (Blueprint $table) {
                        $table->dropUnique('stocks_symbol_unique');
                    });
                }

                return;
            }

            if ($driver === 'pgsql') {
                DB::statement('ALTER TABLE stocks DROP CONSTRAINT IF EXISTS stocks_symbol_unique');

                return;
            }

            Schema::table('stocks', function (Blueprint $table) {
                $table->dropUnique(['symbol']);
            });
        } catch (\Throwable $e) {
            // Index déjà absent
        }
    }
};
