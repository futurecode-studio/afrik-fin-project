<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_order_intents') && ! Schema::hasColumn('stock_order_intents', 'partner_id')) {
            Schema::table('stock_order_intents', function (Blueprint $table) {
                $table->foreignId('partner_id')->nullable()->after('user_id')->constrained('partners')->nullOnDelete();
            });
        }

        if (Schema::hasTable('scheduled_orders') && ! Schema::hasColumn('scheduled_orders', 'partner_id')) {
            Schema::table('scheduled_orders', function (Blueprint $table) {
                $table->foreignId('partner_id')->nullable()->after('user_id')->constrained('partners')->nullOnDelete();
            });
        }

        // Activer le parcours intentions (admin peut toujours désactiver via SGI/SGO)
        if (Schema::hasTable('feature_flags')) {
            DB::table('feature_flags')
                ->whereIn('key', ['client.ordres', 'marches.carnet'])
                ->update(['enabled' => true, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stock_order_intents') && Schema::hasColumn('stock_order_intents', 'partner_id')) {
            Schema::table('stock_order_intents', function (Blueprint $table) {
                $table->dropConstrainedForeignId('partner_id');
            });
        }
        if (Schema::hasTable('scheduled_orders') && Schema::hasColumn('scheduled_orders', 'partner_id')) {
            Schema::table('scheduled_orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('partner_id');
            });
        }
    }
};
