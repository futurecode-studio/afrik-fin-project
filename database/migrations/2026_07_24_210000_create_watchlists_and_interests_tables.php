<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_watchlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'stock_id']);
        });

        Schema::create('user_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('interest_key', 80);
            $table->timestamps();
            $table->unique(['user_id', 'interest_key']);
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'interests_completed_at')) {
                $table->timestamp('interests_completed_at')->nullable()->after('last_login_at');
            }
        });

        // Ne pas forcer l’onboarding sur les comptes déjà créés
        DB::table('users')->whereNull('interests_completed_at')->update([
            'interests_completed_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('user_interests');
        Schema::dropIfExists('stock_watchlists');
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'interests_completed_at')) {
                $table->dropColumn('interests_completed_at');
            }
        });
    }
};
