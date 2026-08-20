<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sgi_account_requests')) {
            return;
        }

        if (! Schema::hasColumn('sgi_account_requests', 'client_confirmation_sent_at')) {
            Schema::table('sgi_account_requests', function (Blueprint $table) {
                $table->timestamp('client_confirmation_sent_at')->nullable()->after('contacted_at');
            });
        }

        if (! Schema::hasColumn('sgi_account_requests', 'admin_notified_at')) {
            Schema::table('sgi_account_requests', function (Blueprint $table) {
                $table->timestamp('admin_notified_at')->nullable()->after('client_confirmation_sent_at');
            });
        }

        if (! Schema::hasColumn('sgi_account_requests', 'client_reminded_at')) {
            Schema::table('sgi_account_requests', function (Blueprint $table) {
                $table->timestamp('client_reminded_at')->nullable()->after('admin_notified_at');
            });
        }

        if (! Schema::hasColumn('sgi_account_requests', 'admin_reminded_at')) {
            Schema::table('sgi_account_requests', function (Blueprint $table) {
                $table->timestamp('admin_reminded_at')->nullable()->after('client_reminded_at');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('sgi_account_requests')) {
            return;
        }

        Schema::table('sgi_account_requests', function (Blueprint $table) {
            $columns = [
                'client_confirmation_sent_at',
                'admin_notified_at',
                'client_reminded_at',
                'admin_reminded_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('sgi_account_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
