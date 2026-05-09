<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_product_variants', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->after('variant_name');
        });
    }

    public function down(): void
    {
        Schema::table('event_product_variants', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
