<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('api_integrations')) {
            Schema::create('api_integrations', function (Blueprint $table) {
                $table->id();
                $table->string('provider', 64)->unique();
                $table->string('label');
                $table->boolean('is_enabled')->default(false);
                $table->boolean('sandbox')->default(true);
                $table->text('credentials')->nullable(); // encrypted JSON
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        $now = now();
        $defaults = [
            ['provider' => 'kkiapay', 'label' => 'KKiaPay', 'is_enabled' => false, 'sandbox' => true],
            ['provider' => 'fedapay', 'label' => 'FedaPay', 'is_enabled' => false, 'sandbox' => true],
            ['provider' => 'feexpay', 'label' => 'FeexPay', 'is_enabled' => false, 'sandbox' => true],
            ['provider' => 'mansa', 'label' => 'Mansa (BRVM)', 'is_enabled' => false, 'sandbox' => false],
            ['provider' => 'marketstack', 'label' => 'Marketstack', 'is_enabled' => false, 'sandbox' => false],
        ];

        foreach ($defaults as $row) {
            $exists = DB::table('api_integrations')->where('provider', $row['provider'])->exists();
            if (! $exists) {
                DB::table('api_integrations')->insert(array_merge($row, [
                    'credentials' => null,
                    'meta' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('api_integrations');
    }
};
