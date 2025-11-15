<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Formation;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un utilisateur et une formation pour les tests
        $user = User::first();
        $formation = Formation::first();

        $transactions = [
            [
                'agregateur' => 'kkiapay',
                'external_transaction_id' => 'KKP_' . uniqid(),
                'status' => Transaction::STATUS_SUCCEEDED,
                'amount' => 50000,
                'currency' => 'XOF',
                'description' => 'Paiement formation Laravel Avancé',
                'mode' => 'live',
                'fullname' => 'Jean Dupont',
                'phone' => '+22997123456',
                'email' => 'jean.dupont@example.com',
                'type_paiement' => 'MOBILE_MONEY',
                'type' => 'DEBIT',
                'source' => 'mobile_money',
                'source_common_name' => 'MTN Mobile Money',
                'fees' => 1500,
                'isFeesBorneByMerchant' => false,
                'net' => 48500,
                'country' => 'BJ',
                'state' => 'SUCCESSFUL',
                'is_counted' => true,
                'user_id' => $user?->id,
                'course_id' => $formation?->id,
                'performed_at' => now()->subDays(2),
                'received_at' => now()->subDays(2),
            ],
            [
                'agregateur' => 'fedapay',
                'external_transaction_id' => 'FDP_' . uniqid(),
                'status' => Transaction::STATUS_SUCCEEDED,
                'amount' => 75000,
                'currency' => 'XOF',
                'description' => 'Inscription formation React JS',
                'mode' => 'live',
                'fullname' => 'Marie Kouassi',
                'phone' => '+2250708123456',
                'email' => 'marie.kouassi@example.com',
                'type_paiement' => 'CARD',
                'type' => 'DEBIT',
                'source' => 'card',
                'source_common_name' => 'Visa',
                'fees' => 2250,
                'isFeesBorneByMerchant' => true,
                'net' => 72750,
                'country' => 'CI',
                'state' => 'SUCCESSFUL',
                'is_counted' => true,
                'user_id' => $user?->id,
                'course_id' => $formation?->id,
                'performed_at' => now()->subDay(),
                'received_at' => now()->subDay(),
            ],
            [
                'agregateur' => 'kkiapay',
                'external_transaction_id' => 'KKP_' . uniqid(),
                'status' => Transaction::STATUS_PENDING,
                'amount' => 35000,
                'currency' => 'XOF',
                'description' => 'Achat cours Vue.js',
                'mode' => 'live',
                'fullname' => 'Kouadio Ange',
                'phone' => '+22501234567',
                'email' => 'kouadio.ange@example.com',
                'type_paiement' => 'MOBILE_MONEY',
                'type' => 'DEBIT',
                'source' => 'mobile_money',
                'source_common_name' => 'Moov Money',
                'country' => 'BJ',
                'state' => 'PENDING',
                'is_counted' => false,
                'user_id' => $user?->id,
                'course_id' => $formation?->id,
                'performed_at' => now(),
            ],
            [
                'agregateur' => 'kkiapay',
                'external_transaction_id' => 'KKP_' . uniqid(),
                'status' => Transaction::STATUS_FAILED,
                'amount' => 45000,
                'currency' => 'XOF',
                'description' => 'Paiement formation Node.js',
                'mode' => 'live',
                'fullname' => 'Abdoul Razak',
                'phone' => '+22996789012',
                'email' => 'abdoul.razak@example.com',
                'type_paiement' => 'MOBILE_MONEY',
                'type' => 'DEBIT',
                'source' => 'mobile_money',
                'source_common_name' => 'Orange Money',
                'fees' => 0,
                'country' => 'BJ',
                'state' => 'FAILED',
                'reason' => 'Solde insuffisant',
                'is_counted' => false,
                'user_id' => $user?->id,
                'course_id' => $formation?->id,
                'performed_at' => now()->subHours(3),
            ],
            [
                'agregateur' => 'fedapay',
                'external_transaction_id' => 'FDP_' . uniqid(),
                'status' => Transaction::STATUS_SUCCEEDED,
                'amount' => 100000,
                'currency' => 'XOF',
                'description' => 'Pack formation complet',
                'mode' => 'live',
                'fullname' => 'Fatou Diallo',
                'phone' => '+221771234567',
                'email' => 'fatou.diallo@example.com',
                'type_paiement' => 'MOBILE_MONEY',
                'type' => 'DEBIT',
                'source' => 'mobile_money',
                'source_common_name' => 'Wave',
                'fees' => 3000,
                'isFeesBorneByMerchant' => false,
                'net' => 97000,
                'country' => 'SN',
                'state' => 'SUCCESSFUL',
                'is_counted' => true,
                'user_id' => $user?->id,
                'course_id' => $formation?->id,
                'performed_at' => now()->subDays(5),
                'received_at' => now()->subDays(5),
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }

        $this->command->info('5 transactions de test créées avec succès !');
    }
}
