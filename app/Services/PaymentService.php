<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Créer une inscription en attente et initier le paiement
     */
    public function initiatePayment(User $user, Formation $formation, string $provider, ?string $phone = null): array
    {
        // Vérifier si l'utilisateur est déjà inscrit
        if ($user->isEnrolledIn($formation)) {
            return [
                'success' => false,
                'message' => 'Vous êtes déjà inscrit à cette formation.',
            ];
        }

        // Si la formation est gratuite, inscrire directement
        if ($formation->isFree()) {
            return $this->enrollForFree($user, $formation);
        }

        // Créer ou récupérer l'inscription en attente
        $enrollment = Enrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'formation_id' => $formation->id,
            ],
            [
                'status' => 'pending',
                'amount_paid' => 0,
            ]
        );

        // Créer le paiement en attente
        $payment = Payment::create([
            'user_id' => $user->id,
            'enrollment_id' => $enrollment->id,
            'formation_id' => $formation->id,
            'transaction_id' => 'PENDING-' . Str::random(10),
            'amount' => $formation->prix,
            'currency' => 'XOF',
            'provider' => $provider,
            'status' => 'pending',
            'phone' => $phone,
        ]);

        return [
            'success' => true,
            'payment' => $payment,
            'enrollment' => $enrollment,
            'amount' => $formation->prix,
            'reference' => $payment->reference,
        ];
    }

    /**
     * Inscription gratuite
     */
    public function enrollForFree(User $user, Formation $formation): array
    {
        $enrollment = Enrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'formation_id' => $formation->id,
            ],
            [
                'status' => 'active',
                'amount_paid' => 0,
                'enrolled_at' => now(),
            ]
        );

        if ($enrollment->wasRecentlyCreated || $enrollment->status !== 'active') {
            $enrollment->update([
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }

        return [
            'success' => true,
            'message' => 'Inscription réussie ! Vous pouvez maintenant accéder à la formation.',
            'enrollment' => $enrollment,
        ];
    }

    /**
     * Traiter le callback de KKiaPay
     */
    public function handleKkiapayCallback(array $data): array
    {
        try {
            $transactionId = $data['transactionId'] ?? null;
            $reference = $data['reference'] ?? null;
            $status = $data['status'] ?? null;

            if (!$reference) {
                return ['success' => false, 'message' => 'Référence manquante'];
            }

            $payment = Payment::where('reference', $reference)->first();

            if (!$payment) {
                return ['success' => false, 'message' => 'Paiement non trouvé'];
            }

            if ($status === 'SUCCESS' || $status === 'SUCCESSFUL') {
                // Vérifier la transaction auprès de KKiaPay
                $verified = $this->verifyKkiapayTransaction($transactionId);

                if ($verified) {
                    $payment->markAsCompleted($transactionId, $data);
                    return [
                        'success' => true,
                        'message' => 'Paiement confirmé avec succès',
                        'enrollment' => $payment->enrollment,
                    ];
                }
            }

            $payment->markAsFailed($data['reason'] ?? 'Paiement échoué', $data);
            return ['success' => false, 'message' => 'Paiement échoué'];

        } catch (\Exception $e) {
            Log::error('KKiaPay callback error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur de traitement'];
        }
    }

    /**
     * Vérifier une transaction KKiaPay
     */
    public function verifyKkiapayTransaction(string $transactionId): bool
    {
        try {
            $base = rtrim((string) config('services.kkiapay.api_url', 'https://api.kkiapay.me'), '/');
            $response = Http::withHeaders([
                'x-private-key' => (string) config('services.kkiapay.private_key'),
            ])->get("{$base}/api/v1/transactions/status/{$transactionId}");

            if ($response->successful()) {
                $data = $response->json();

                return ($data['status'] ?? '') === 'SUCCESS';
            }

            return false;
        } catch (\Exception $e) {
            Log::error('KKiaPay verification error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Traiter le callback de FedaPay
     */
    public function handleFedapayCallback(array $data): array
    {
        try {
            $transactionId = $data['id'] ?? null;
            $reference = $data['reference'] ?? ($data['description'] ?? null);
            $status = $data['status'] ?? null;

            // Extraire la référence du description si nécessaire
            if (!$reference && isset($data['transaction']['description'])) {
                preg_match('/REF:([A-Z0-9-]+)/', $data['transaction']['description'], $matches);
                $reference = $matches[1] ?? null;
            }

            if (!$reference) {
                return ['success' => false, 'message' => 'Référence manquante'];
            }

            $payment = Payment::where('reference', $reference)->first();

            if (!$payment) {
                return ['success' => false, 'message' => 'Paiement non trouvé'];
            }

            if ($status === 'approved' || $status === 'completed') {
                $payment->markAsCompleted($transactionId, $data);
                return [
                    'success' => true,
                    'message' => 'Paiement confirmé avec succès',
                    'enrollment' => $payment->enrollment,
                ];
            }

            $payment->markAsFailed($data['reason'] ?? 'Paiement échoué', $data);
            return ['success' => false, 'message' => 'Paiement échoué'];

        } catch (\Exception $e) {
            Log::error('FedaPay callback error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur de traitement'];
        }
    }

    /**
     * Créer une transaction FedaPay
     */
    public function createFedapayTransaction(Payment $payment): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.config('services.fedapay.secret_key'),
                'Content-Type' => 'application/json',
            ])->post(rtrim((string) config('services.fedapay.api_url'), '/').'/transactions', [
                'description' => 'Formation: ' . $payment->formation->titre . ' - REF:' . $payment->reference,
                'amount' => (int) $payment->amount,
                'currency' => ['iso' => 'XOF'],
                'callback_url' => route('payment.fedapay.callback'),
                'customer' => [
                    'email' => $payment->user->email,
                    'firstname' => $payment->user->name,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'transaction_id' => $data['v1']['id'] ?? null,
                    'token' => $data['v1']['token'] ?? null,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de la création de la transaction',
            ];

        } catch (\Exception $e) {
            Log::error('FedaPay create transaction error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur de connexion au service de paiement',
            ];
        }
    }

    /**
     * Vérifier un paiement FeexPay (status par référence / id).
     */
    public function verifyFeexpayPayment(string $paymentId): array
    {
        try {
            $base = rtrim((string) config('services.feexpay.api_url', 'https://api.feexpay.me'), '/');
            $response = Http::withToken((string) config('services.feexpay.api_key'))
                ->acceptJson()
                ->get("{$base}/api/transactions/{$paymentId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Vérification FeexPay échouée',
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('FeexPay verify error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur de connexion FeexPay',
            ];
        }
    }

    public function isProviderReady(string $provider): bool
    {
        return ApiCredentials::isConfigured($provider);
    }

    /**
     * Providers de paiement utilisables (clés DB ou .env présentes).
     *
     * @return list<string>
     */
    public function availablePaymentProviders(): array
    {
        $list = [];
        foreach (['kkiapay', 'fedapay', 'feexpay'] as $provider) {
            if (ApiCredentials::isConfigured($provider)) {
                $list[] = $provider;
            }
        }

        return $list;
    }
}
