<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mansa Markets API (BRVM sync serveur uniquement)
    |--------------------------------------------------------------------------
    */
    'mansa' => [
        'base_url' => env('MANSA_API_BASE_URL', 'https://mansaapi.com'),
        'api_key' => env('MANSA_API_KEY'),
        'timeout' => env('MANSA_TIMEOUT', 20),
        'cache_ttl' => env('MANSA_CACHE_TTL', 1800),
    ],

    /*
    |--------------------------------------------------------------------------
    | BRVM (lecture locale / cache UI)
    |--------------------------------------------------------------------------
    |
    | Les cotations viennent de Mansa via `php artisan market:sync-brvm`.
    | Plus de scraping RichBourse / BRVM.org.
    |
    */

    'brvm' => [
        'cache_duration' => env('BRVM_CACHE_DURATION', 900),
        'timeout' => env('BRVM_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketstack API Configuration (Legacy - optionnel)
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'API Marketstack (alternative payante)
    | Obtenez une clé API sur https://marketstack.com
    |
    */

    'marketstack' => [
        'api_url' => env('MARKETSTACK_API_URL', 'http://api.marketstack.com/v1'),
        'api_key' => env('MARKETSTACK_API_KEY'),
        'cache_duration' => env('MARKETSTACK_CACHE_DURATION', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mutual Funds API Configuration (VL/FCP)
    |--------------------------------------------------------------------------
    |
    | Configuration du scraping Sikafinance pour les VL des FCP/OPCVM UEMOA.
    | 100% données réelles — aucun fallback mock. Cache 1h par défaut.
    |
    */

    'mutual_funds' => [
        'cache_duration' => env('MUTUAL_FUNDS_CACHE_DURATION', 3600),
        'timeout' => env('MUTUAL_FUNDS_TIMEOUT', 20),
        'source' => 'https://www.sikafinance.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | KKiaPay Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'agrégateur de paiement KKiaPay
    | Obtenez vos clés sur https://kkiapay.me
    |
    */

    'kkiapay' => [
        'public_key' => env('KKIAPAY_PUBLIC_KEY', ''),
        'private_key' => env('KKIAPAY_PRIVATE_KEY', ''),
        'secret' => env('KKIAPAY_SECRET', ''),
        'sandbox' => env('KKIAPAY_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | FedaPay Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'agrégateur de paiement FedaPay
    | Obtenez vos clés sur https://fedapay.com
    |
    */

    'fedapay' => [
        'public_key' => env('FEDAPAY_PUBLIC_KEY', ''),
        'secret_key' => env('FEDAPAY_SECRET_KEY', ''),
        'api_url' => env('FEDAPAY_API_URL', 'https://sandbox-api.fedapay.com/v1'),
        'sandbox' => env('FEDAPAY_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | FeexPay Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Shop ID + API Token (Bearer) depuis https://app.feexpay.me → Développeur
    | Docs: https://docs.feexpay.me
    |
    */

    'feexpay' => [
        'shop_id' => env('FEEXPAY_SHOP_ID', ''),
        'api_key' => env('FEEXPAY_API_KEY', ''),
        'api_url' => env('FEEXPAY_API_URL', 'https://api.feexpay.me'),
        'callback_url' => env('FEEXPAY_CALLBACK_URL', ''),
        'sandbox' => env('FEEXPAY_SANDBOX', true),
        'mode' => env('FEEXPAY_SANDBOX', true) ? 'SANDBOX' : 'LIVE',
    ],

];
