<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_order_intents') && ! Schema::hasColumn('stock_order_intents', 'sgi_account_number')) {
            Schema::table('stock_order_intents', function (Blueprint $table) {
                $table->string('sgi_account_number', 120)->nullable()->after('partner_id');
            });
        }

        if (Schema::hasTable('scheduled_orders') && ! Schema::hasColumn('scheduled_orders', 'sgi_account_number')) {
            Schema::table('scheduled_orders', function (Blueprint $table) {
                $table->string('sgi_account_number', 120)->nullable()->after('partner_id');
            });
        }

        if (! Schema::hasTable('sgi_required_documents')) {
            Schema::create('sgi_required_documents', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->unsignedSmallInteger('display_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            $now = now();
            DB::table('sgi_required_documents')->insert([
                [
                    'title' => 'Pièce d’identité (CNI ou passeport)',
                    'description' => 'Copie recto-verso en cours de validité.',
                    'display_order' => 10,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title' => 'Photo d’identité récente',
                    'description' => 'Fond neutre, format standard.',
                    'display_order' => 20,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title' => 'Justificatif de domicile',
                    'description' => 'Facture d’électricité, d’eau ou quittance de loyer de moins de 3 mois.',
                    'display_order' => 30,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title' => 'IFU (Identifiant Fiscal Unique)',
                    'description' => 'Document ou numéro IFU selon votre situation.',
                    'display_order' => 40,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        if (! Schema::hasTable('sgi_account_requests')) {
            Schema::create('sgi_account_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->string('email');
                $table->string('phone', 40);
                $table->string('source', 40)->default('carnet'); // carnet|ordres
                $table->string('status', 30)->default('pending'); // pending|contacted|in_progress|done|cancelled
                $table->text('admin_notes')->nullable();
                $table->timestamp('contacted_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('feature_flags')) {
            DB::table('feature_flags')->where('key', 'client.vote_ag')->delete();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sgi_account_requests');
        Schema::dropIfExists('sgi_required_documents');

        if (Schema::hasTable('stock_order_intents') && Schema::hasColumn('stock_order_intents', 'sgi_account_number')) {
            Schema::table('stock_order_intents', function (Blueprint $table) {
                $table->dropColumn('sgi_account_number');
            });
        }

        if (Schema::hasTable('scheduled_orders') && Schema::hasColumn('scheduled_orders', 'sgi_account_number')) {
            Schema::table('scheduled_orders', function (Blueprint $table) {
                $table->dropColumn('sgi_account_number');
            });
        }

        if (Schema::hasTable('feature_flags')) {
            $exists = DB::table('feature_flags')->where('key', 'client.vote_ag')->exists();
            if (! $exists) {
                DB::table('feature_flags')->insert([
                    'key' => 'client.vote_ag',
                    'label' => 'Vote AG / Proxy voting',
                    'description' => 'Vote par procuration via compte-titres / SGI.',
                    'enabled' => false,
                    'group' => 'sgi',
                    'sort_order' => 20,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
};
