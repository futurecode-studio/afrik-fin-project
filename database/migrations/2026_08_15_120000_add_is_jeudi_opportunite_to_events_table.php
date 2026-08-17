<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'is_jeudi_opportunite')) {
                $table->boolean('is_jeudi_opportunite')->default(false)->after('is_featured');
                $table->index('is_jeudi_opportunite');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'is_jeudi_opportunite')) {
                $table->dropIndex(['is_jeudi_opportunite']);
                $table->dropColumn('is_jeudi_opportunite');
            }
        });
    }
};
