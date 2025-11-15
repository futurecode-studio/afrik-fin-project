<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('agregateur')->comment('fedapay, kkiapay, etc.');
            $table->string('external_transaction_id')->nullable()->comment('ID de la transaction chez le provider');
            $table->string('status')->default('pending')->comment('pending, approved, succeeded, canceled, declined, failed');
            $table->decimal('amount', 10, 2)->comment('Montant de la transaction');
            $table->string('currency', 3)->default('XOF')->comment('Devise de la transaction');
            $table->text('description')->nullable();
            $table->string('mode')->nullable()->comment('sandbox ou live');
            $table->text('callback_url')->nullable();
            $table->string('fullname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('account')->nullable();
            $table->text('person')->nullable()->comment('Données complètes de la personne');
            $table->string('type_paiement')->nullable()->comment('Type de paiement KKiaPay');
            $table->timestamp('performed_at')->nullable()->comment('Date d\'exécution KKiaPay');
            $table->timestamp('received_at')->nullable()->comment('Date de réception KKiaPay');
            $table->string('type')->nullable()->comment('Type de transaction KKiaPay');
            $table->string('source')->nullable()->comment('Source du paiement');
            $table->string('source_common_name')->nullable()->comment('Nom commun de la source');
            $table->decimal('fees', 10, 2)->nullable()->comment('Frais de la transaction');
            $table->unsignedTinyInteger('isFeesBorneByMerchant')->default(0)->comment('Frais supportés par le marchand');
            $table->decimal('net', 10, 2)->nullable()->comment('Montant net après frais');
            $table->text('paymentlink')->nullable()->comment('Lien de paiement');
            $table->string('country')->nullable()->comment('Pays de la transaction');
            $table->text('reason')->nullable()->comment('Raison/motif');
            $table->string('state')->nullable()->comment('État de la transaction');
            $table->decimal('before_balance', 10, 2)->nullable()->comment('Solde avant transaction');
            $table->decimal('after_balance', 10, 2)->nullable()->comment('Solde après transaction');
            $table->unsignedTinyInteger('is_payout')->default(0)->comment('Est-ce un payout');
            $table->unsignedTinyInteger('is_counted')->default(1)->comment('Transaction comptabilisée');
            $table->string('wallet')->nullable()->comment('Portefeuille utilisé');
            $table->text('meta_data')->nullable()->comment('Métadonnées additionnelles');
            $table->unsignedTinyInteger('isNewGeneration')->default(0)->comment('Nouvelle génération KKiaPay');
            $table->string('transactionId')->nullable()->comment('ID de transaction KKiaPay');
            $table->timestamp('performedAt')->nullable()->comment('Date d\'exécution formatée');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID de l\'utilisateur');
            $table->unsignedBigInteger('course_id')->nullable()->comment('ID du cours');
            $table->unsignedBigInteger('enrollment_id')->nullable()->comment('ID de l\'inscription');
            $table->text('raw_response')->nullable()->comment('Réponse brute du provider');
            $table->text('webhook_data')->nullable()->comment('Données webhook reçues');
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('agregateur');
            $table->index('external_transaction_id');
            $table->index('status');
            $table->index('user_id');
            $table->index('course_id');
            $table->index('enrollment_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
