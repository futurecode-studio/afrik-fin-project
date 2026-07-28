<?php

namespace App\Services;

use App\Models\ApiIntegration;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Résout les credentials API : DB (admin) prioritaire, sinon .env / config/services.php.
 */
class ApiCredentials
{
    public const CACHE_KEY = 'api_integrations.all';

    /** @var array<string, array>|null Snapshot config fichier (avant overlay DB) */
    private static ?array $baseConfig = null;

    private static ?bool $tableReady = null;

    /**
     * @return array<string, array{
     *   label: string,
     *   docs: string,
     *   category: string,
     *   description: string,
     *   has_sandbox: bool,
     *   fields: array<string, array{label: string, secret: bool, env: string, required?: bool, placeholder?: string, type?: string}>
     * }>
     */
    public static function catalog(): array
    {
        return [
            'kkiapay' => [
                'label' => 'KKiaPay',
                'docs' => 'https://docs.kkiapay.me',
                'category' => 'paiement',
                'description' => 'Agrégateur Mobile Money / carte. Clés dans le dashboard → Développeurs → API Keys.',
                'has_sandbox' => true,
                'fields' => [
                    'public_key' => ['label' => 'Public Key', 'secret' => false, 'env' => 'KKIAPAY_PUBLIC_KEY', 'required' => true, 'placeholder' => 'pk_…'],
                    'private_key' => ['label' => 'Private Key', 'secret' => true, 'env' => 'KKIAPAY_PRIVATE_KEY', 'required' => true, 'placeholder' => 'prv_…'],
                    'secret' => ['label' => 'Secret', 'secret' => true, 'env' => 'KKIAPAY_SECRET', 'required' => true, 'placeholder' => 'sk_…'],
                ],
            ],
            'fedapay' => [
                'label' => 'FedaPay',
                'docs' => 'https://docs.fedapay.com',
                'category' => 'paiement',
                'description' => 'Clés sandbox (sk_sandbox / pk_sandbox) ou live (sk_live / pk_live). API URL dérivée du mode si vide.',
                'has_sandbox' => true,
                'fields' => [
                    'public_key' => ['label' => 'Public Key', 'secret' => false, 'env' => 'FEDAPAY_PUBLIC_KEY', 'required' => true, 'placeholder' => 'pk_sandbox_… / pk_live_…'],
                    'secret_key' => ['label' => 'Secret Key', 'secret' => true, 'env' => 'FEDAPAY_SECRET_KEY', 'required' => true, 'placeholder' => 'sk_sandbox_… / sk_live_…'],
                    'api_url' => ['label' => 'API URL (optionnel)', 'secret' => false, 'env' => 'FEDAPAY_API_URL', 'required' => false, 'placeholder' => 'https://sandbox-api.fedapay.com/v1'],
                ],
            ],
            'feexpay' => [
                'label' => 'FeexPay',
                'docs' => 'https://docs.feexpay.me',
                'category' => 'paiement',
                'description' => 'Shop ID + API Token (Bearer) depuis le menu Développeur FeexPay. Mode SANDBOX ou LIVE.',
                'has_sandbox' => true,
                'fields' => [
                    'shop_id' => ['label' => 'Shop ID', 'secret' => false, 'env' => 'FEEXPAY_SHOP_ID', 'required' => true, 'placeholder' => 'ID boutique'],
                    'api_key' => ['label' => 'API Key / Token', 'secret' => true, 'env' => 'FEEXPAY_API_KEY', 'required' => true, 'placeholder' => 'fp_…'],
                    'api_url' => ['label' => 'API URL (optionnel)', 'secret' => false, 'env' => 'FEEXPAY_API_URL', 'required' => false, 'placeholder' => 'https://api.feexpay.me'],
                    'callback_url' => ['label' => 'Callback URL (optionnel)', 'secret' => false, 'env' => 'FEEXPAY_CALLBACK_URL', 'required' => false, 'placeholder' => 'https://votresite.com/payment/feexpay/callback'],
                ],
            ],
            'mansa' => [
                'label' => 'Mansa (BRVM)',
                'docs' => 'https://mansaapi.com',
                'category' => 'marches',
                'description' => 'Source serveur des cotations BRVM (`market:sync-brvm`). Jamais exposée au frontend.',
                'has_sandbox' => false,
                'fields' => [
                    'api_key' => ['label' => 'API Key', 'secret' => true, 'env' => 'MANSA_API_KEY', 'required' => true],
                    'base_url' => ['label' => 'Base URL', 'secret' => false, 'env' => 'MANSA_API_BASE_URL', 'required' => false, 'placeholder' => 'https://mansaapi.com'],
                    'timeout' => ['label' => 'Timeout (s)', 'secret' => false, 'env' => 'MANSA_TIMEOUT', 'required' => false, 'type' => 'number', 'placeholder' => '20'],
                    'cache_ttl' => ['label' => 'Cache TTL (s)', 'secret' => false, 'env' => 'MANSA_CACHE_TTL', 'required' => false, 'type' => 'number', 'placeholder' => '1800'],
                ],
            ],
            'marketstack' => [
                'label' => 'Marketstack',
                'docs' => 'https://marketstack.com',
                'category' => 'marches',
                'description' => 'API marchés alternative (legacy / optionnelle).',
                'has_sandbox' => false,
                'fields' => [
                    'api_key' => ['label' => 'API Key', 'secret' => true, 'env' => 'MARKETSTACK_API_KEY', 'required' => true],
                    'api_url' => ['label' => 'API URL', 'secret' => false, 'env' => 'MARKETSTACK_API_URL', 'required' => false, 'placeholder' => 'http://api.marketstack.com/v1'],
                    'cache_duration' => ['label' => 'Cache (s)', 'secret' => false, 'env' => 'MARKETSTACK_CACHE_DURATION', 'required' => false, 'type' => 'number', 'placeholder' => '300'],
                ],
            ],
        ];
    }

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function allCached()
    {
        if (! self::tableReady()) {
            return collect();
        }

        return Cache::remember(self::CACHE_KEY, 300, function () {
            return ApiIntegration::query()->orderBy('id')->get();
        });
    }

    public static function for(string $provider): ?ApiIntegration
    {
        return self::allCached()->firstWhere('provider', $provider);
    }

    /**
     * Valeur effective : DB → .env → snapshot config fichier → $default.
     */
    public static function get(string $provider, string $key, mixed $default = null): mixed
    {
        $row = self::for($provider);
        if ($row && $row->hasCredential($key)) {
            return $row->credential($key);
        }

        $envKey = self::catalog()[$provider]['fields'][$key]['env'] ?? null;
        if ($envKey) {
            $fromEnv = Env::get($envKey);
            if ($fromEnv !== null && $fromEnv !== '') {
                return $fromEnv;
            }
        }

        $base = self::$baseConfig[$provider][$key] ?? null;
        if ($base !== null && $base !== '') {
            return $base;
        }

        return $default;
    }

    public static function sandbox(string $provider): bool
    {
        $row = self::for($provider);
        if ($row !== null) {
            return (bool) $row->sandbox;
        }

        $envName = match ($provider) {
            'kkiapay' => 'KKIAPAY_SANDBOX',
            'fedapay' => 'FEDAPAY_SANDBOX',
            'feexpay' => 'FEEXPAY_SANDBOX',
            default => null,
        };
        if ($envName) {
            $env = Env::get($envName);
            if ($env !== null && $env !== '') {
                return filter_var($env, FILTER_VALIDATE_BOOLEAN);
            }
        }

        return (bool) (self::$baseConfig[$provider]['sandbox'] ?? config("services.{$provider}.sandbox", true));
    }

    public static function enabled(string $provider): bool
    {
        $row = self::for($provider);
        if ($row !== null) {
            return (bool) $row->is_enabled && self::isConfigured($provider);
        }

        return self::isConfigured($provider);
    }

    public static function isConfigured(string $provider): bool
    {
        $catalog = self::catalog()[$provider] ?? null;
        if (! $catalog) {
            return false;
        }

        foreach ($catalog['fields'] as $key => $field) {
            if (! ($field['required'] ?? false)) {
                continue;
            }
            if (! filled(self::get($provider, $key))) {
                return false;
            }
        }

        return true;
    }

    public static function captureBaseConfig(): void
    {
        if (self::$baseConfig !== null) {
            return;
        }
        self::$baseConfig = [];
        foreach (array_keys(self::catalog()) as $provider) {
            self::$baseConfig[$provider] = config("services.{$provider}", []);
        }
    }

    /**
     * Applique DB > env dans config('services.*') pour le runtime.
     */
    public static function applyToConfig(): void
    {
        self::captureBaseConfig();

        if (! self::tableReady()) {
            return;
        }

        foreach (self::catalog() as $provider => $meta) {
            $row = self::for($provider);
            $cfg = is_array(self::$baseConfig[$provider] ?? null)
                ? self::$baseConfig[$provider]
                : [];

            foreach ($meta['fields'] as $key => $field) {
                $resolved = self::get($provider, $key);
                if ($resolved !== null && $resolved !== '') {
                    $cfg[$key] = $resolved;
                } else {
                    unset($cfg[$key]);
                }
            }

            $sandbox = self::sandbox($provider);
            if (in_array($provider, ['mansa', 'marketstack'], true)) {
                $sandbox = false;
            }

            $cfg['sandbox'] = $sandbox;
            $cfg['enabled'] = $row ? (bool) $row->is_enabled : false;

            if ($provider === 'fedapay') {
                $explicitUrl = self::get($provider, 'api_url');
                $cfg['api_url'] = filled($explicitUrl)
                    ? $explicitUrl
                    : ($sandbox ? 'https://sandbox-api.fedapay.com/v1' : 'https://api.fedapay.com/v1');
            }

            if ($provider === 'kkiapay') {
                $cfg['api_url'] = $sandbox
                    ? 'https://api-sandbox.kkiapay.me'
                    : 'https://api.kkiapay.me';
            }

            if ($provider === 'feexpay') {
                if (empty($cfg['api_url'])) {
                    $cfg['api_url'] = 'https://api.feexpay.me';
                }
                $cfg['mode'] = $sandbox ? 'SANDBOX' : 'LIVE';
            }

            config(["services.{$provider}" => $cfg]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function statusBoard(): array
    {
        $board = [];
        foreach (self::catalog() as $provider => $meta) {
            $row = self::for($provider);
            $fields = [];
            foreach ($meta['fields'] as $key => $field) {
                $fromDb = $row && $row->hasCredential($key);
                $envVal = Env::get($field['env']);
                $effective = self::get($provider, $key);
                $fields[$key] = [
                    'label' => $field['label'],
                    'secret' => (bool) ($field['secret'] ?? false),
                    'env' => $field['env'],
                    'required' => (bool) ($field['required'] ?? false),
                    'type' => $field['type'] ?? 'text',
                    'placeholder' => $field['placeholder'] ?? '',
                    'filled' => filled($effective),
                    'source' => $fromDb ? 'db' : (filled($envVal) ? 'env' : 'empty'),
                    'preview' => self::preview($effective, (bool) ($field['secret'] ?? false)),
                ];
            }

            $board[] = [
                'provider' => $provider,
                'label' => $meta['label'],
                'docs' => $meta['docs'],
                'category' => $meta['category'],
                'description' => $meta['description'],
                'has_sandbox' => $meta['has_sandbox'],
                'is_enabled' => (bool) ($row?->is_enabled ?? false),
                'sandbox' => self::sandbox($provider),
                'configured' => self::isConfigured($provider),
                'fields' => $fields,
                'field_defs' => $meta['fields'],
            ];
        }

        return $board;
    }

    public static function preview(mixed $value, bool $secret): string
    {
        if (! filled($value)) {
            return '';
        }
        $str = (string) $value;
        if (! $secret) {
            return mb_strlen($str) > 48 ? mb_substr($str, 0, 24).'…'.mb_substr($str, -8) : $str;
        }
        if (mb_strlen($str) <= 8) {
            return str_repeat('•', 8);
        }

        return mb_substr($str, 0, 4).str_repeat('•', 8).mb_substr($str, -4);
    }

    private static function tableReady(): bool
    {
        if (self::$tableReady !== null) {
            return self::$tableReady;
        }

        try {
            self::$tableReady = Schema::hasTable('api_integrations');
        } catch (Throwable) {
            self::$tableReady = false;
        }

        return self::$tableReady;
    }
}
