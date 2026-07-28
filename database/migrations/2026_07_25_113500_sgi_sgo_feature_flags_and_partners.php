<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            if (! Schema::hasColumn('partners', 'country')) {
                $table->string('country', 80)->nullable()->after('type');
            }
            if (! Schema::hasColumn('partners', 'city')) {
                $table->string('city', 80)->nullable()->after('country');
            }
            if (! Schema::hasColumn('partners', 'agreement_number')) {
                $table->string('agreement_number', 120)->nullable()->after('city');
            }
            if (! Schema::hasColumn('partners', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
            if (! Schema::hasColumn('partners', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('description');
            }
        });

        if (! Schema::hasTable('feature_flags')) {
            Schema::create('feature_flags', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('label');
                $table->text('description')->nullable();
                $table->boolean('enabled')->default(false);
                $table->string('group', 40)->default('sgi'); // sgi, sgo, general
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });

            $now = now();
            DB::table('feature_flags')->insert([
                [
                    'key' => 'client.ordres',
                    'label' => 'Ordres programmés (espace client)',
                    'description' => 'Relais d’intentions d’ordres vers une SGI agréée.',
                    'enabled' => false,
                    'group' => 'sgi',
                    'sort_order' => 10,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'key' => 'client.vote_ag',
                    'label' => 'Vote AG / Proxy voting',
                    'description' => 'Vote par procuration via compte-titres / SGI.',
                    'enabled' => false,
                    'group' => 'sgi',
                    'sort_order' => 20,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'key' => 'marches.carnet',
                    'label' => 'Carnet d’ordres (public)',
                    'description' => 'Intentions d’ordres relayées vers une SGI.',
                    'enabled' => false,
                    'group' => 'sgi',
                    'sort_order' => 30,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'key' => 'services.mandat',
                    'label' => 'Gestion sous mandat',
                    'description' => 'Délégation de portefeuille via SGI / SGO.',
                    'enabled' => false,
                    'group' => 'sgi',
                    'sort_order' => 40,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'key' => 'investir.souscription_opcvm',
                    'label' => 'Souscription OPCVM en ligne',
                    'description' => 'Souscription FCP / OPCVM via SGO partenaire.',
                    'enabled' => false,
                    'group' => 'sgo',
                    'sort_order' => 50,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flags');

        Schema::table('partners', function (Blueprint $table) {
            foreach (['country', 'city', 'agreement_number', 'is_featured', 'admin_notes'] as $col) {
                if (Schema::hasColumn('partners', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
