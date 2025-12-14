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
    | BRVM Scraper Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour le scraping des données BRVM en temps réel
    | Sources: RichBourse.com, BRVM.org (données africaines gratuites)
    |
    */

    'brvm' => [
        'cache_duration' => env('BRVM_CACHE_DURATION', 300), // 5 minutes par défaut
        'timeout' => env('BRVM_TIMEOUT', 30), // timeout en secondes
        'sources' => [
            'richbourse' => 'https://www.richbourse.com',
            'brvm' => 'https://www.brvm.org',
        ],
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
    | Configuration pour récupérer les valeurs liquidatives des FCP/OPCVM
    | Sources: Sikafinance, RichBourse, BRVM (données africaines UEMOA)
    |
    */

    'mutual_funds' => [
        'cache_duration' => env('MUTUAL_FUNDS_CACHE_DURATION', 3600), // 1 heure par défaut
        'timeout' => env('MUTUAL_FUNDS_TIMEOUT', 30), // timeout en secondes
        'use_default_fallback' => env('MUTUAL_FUNDS_USE_DEFAULT_FALLBACK', true),
        'use_mock' => env('MUTUAL_FUNDS_USE_MOCK', false),
        'sources' => [
            'sikafinance' => 'https://www.sikafinance.com',
            'richbourse' => 'https://www.richbourse.com',
            'brvm' => 'https://www.brvm.org',
        ],
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

];
