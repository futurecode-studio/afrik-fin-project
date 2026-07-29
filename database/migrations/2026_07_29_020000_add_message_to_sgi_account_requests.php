<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sgi_account_requests') && ! Schema::hasColumn('sgi_account_requests', 'message')) {
            Schema::table('sgi_account_requests', function (Blueprint $table) {
                $table->text('message')->nullable()->after('phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sgi_account_requests') && Schema::hasColumn('sgi_account_requests', 'message')) {
            Schema::table('sgi_account_requests', function (Blueprint $table) {
                $table->dropColumn('message');
            });
        }
    }
};
