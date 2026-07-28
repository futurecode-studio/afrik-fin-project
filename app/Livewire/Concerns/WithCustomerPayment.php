<?php

namespace App\Livewire\Concerns;

use App\Services\PaymentService;

/**
 * Choix client (opérateur / carte) + résolution passerelle backend.
 */
trait WithCustomerPayment
{
    /** Méthode visible client : mtn|moov|celtiis|card */
    public string $paymentMethod = 'mtn';

    /** Passerelle réellement utilisée (fedapay|kkiapay|…) — jamais affichée */
    public string $paymentProvider = 'fedapay';

    public function bootWithCustomerPayment(): void
    {
        $gateway = app(PaymentService::class)->resolveGateway();
        if ($gateway) {
            $this->paymentProvider = $gateway;
        }
    }

    protected function resolvePaymentGateway(): ?string
    {
        $service = app(PaymentService::class);
        $gateway = $service->resolveGateway();
        if ($gateway) {
            $this->paymentProvider = $gateway;
        }

        return $gateway;
    }

    /**
     * @return array{provider: ?string, fallback_provider: ?string, method: string}
     */
    protected function paymentWidgetMeta(): array
    {
        $service = app(PaymentService::class);
        $provider = $this->resolvePaymentGateway();

        return [
            'provider' => $provider,
            'fallback_provider' => $provider ? $service->fallbackGateway($provider) : null,
            'method' => $this->paymentMethod,
        ];
    }

    protected function paymentMethodNeedsPhone(): bool
    {
        $methods = app(PaymentService::class)->customerPaymentMethods();

        return (bool) ($methods[$this->paymentMethod]['needs_phone'] ?? true);
    }

    protected function handleGatewayCallback(array $data, PaymentService $paymentService): array
    {
        $provider = (string) ($data['provider'] ?? $this->paymentProvider);

        return match ($provider) {
            'kkiapay' => $paymentService->handleKkiapayCallback($data),
            'feexpay' => method_exists($paymentService, 'handleFeexpayCallback')
                ? $paymentService->handleFeexpayCallback($data)
                : $paymentService->handleFedapayCallback($data),
            default => $paymentService->handleFedapayCallback($data),
        };
    }
}
