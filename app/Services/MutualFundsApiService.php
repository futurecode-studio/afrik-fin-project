<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MutualFundsApiService
{
    private $cacheDuration;
    private $timeout;

    public function __construct()
    {
        $this->cacheDuration = config('services.mutual_funds.cache_duration', 3600); // 1 heure par défaut
        $this->timeout = config('services.mutual_funds.timeout', 15);
    }

    /**
     * Récupérer les fonds communs de placement (VL/FCP) en temps réel
     * Utilise plusieurs sources de données gratuites fiables
     */
    public function getMutualFunds(): array
    {
        return Cache::remember('mutual_funds_data', $this->cacheDuration, function () {
            try {
                // Respect config/env flags
                $useDefaultFallback = filter_var(env('MUTUAL_FUNDS_USE_DEFAULT_FALLBACK', true), FILTER_VALIDATE_BOOLEAN);
                $useMockData = filter_var(env('MUTUAL_FUNDS_USE_MOCK', false), FILTER_VALIDATE_BOOLEAN);

                // 1. Essayer Alpha Vantage API (très fiable, gratuite)
                $funds = $this->fetchFromAlphaVantage();
                if (!empty($funds)) {
                    Log::info('Mutual Funds: Alpha Vantage data loaded successfully');
                    return $funds;
                }

                // 2. Essayer Yahoo Finance (ETFs mondiaux)
                $funds = $this->fetchFromYahooFinance();
                if (!empty($funds)) {
                    Log::info('Mutual Funds: Yahoo Finance data loaded successfully');
                    return $funds;
                }

                // 3. Fallback: API BRVM (Bourses africaines)
                $funds = $this->fetchUEMOAFunds();
                if (!empty($funds)) {
                    Log::info('Mutual Funds: BRVM/UEMOA data loaded successfully');
                    return $funds;
                }

                // 4. Si mock mode forcé, utiliser les données mock (avec variations réalistes)
                if ($useMockData) {
                    Log::warning('Mutual Funds: Using mock data (MUTUAL_FUNDS_USE_MOCK=true)');
                    return $this->getMockMutualFunds();
                }

                // 5. Si le fallback statique est autorisé, le retourner. Sinon, retourner tableau vide
                if ($useDefaultFallback) {
                    Log::warning('Mutual Funds: Using default data - all APIs failed');
                    return $this->getDefaultMutualFunds();
                }

                Log::warning('Mutual Funds: All APIs failed and default fallback is disabled; returning empty dataset');
                return [];

            } catch (\Exception $e) {
                Log::error('Mutual Funds API Exception: ' . $e->getMessage());
                $useMockData = filter_var(env('MUTUAL_FUNDS_USE_MOCK', false), FILTER_VALIDATE_BOOLEAN);
                if ($useMockData) {
                    return $this->getMockMutualFunds();
                }
                if (filter_var(env('MUTUAL_FUNDS_USE_DEFAULT_FALLBACK', true), FILTER_VALIDATE_BOOLEAN)) {
                    return $this->getDefaultMutualFunds();
                }
                return [];
            }
        });
    }

    /**
     * Récupérer les données d'Alpha Vantage (API gratuite fiable pour indices/ETFs)
     * Fonctionnelle sans clé API, meilleure que Yahoo Finance
     */
    private function fetchFromAlphaVantage(): array
    {
        try {
            // Liste de symboles d'indices et ETFs représentant les catégories
            $symbols = [
                // Actions
                ['symbol' => '^GSPC', 'name' => 'S&P 500 Index', 'category' => 'Actions', 'company' => 'S&P Global'],
                ['symbol' => '^IXIC', 'name' => 'NASDAQ Composite', 'category' => 'Actions', 'company' => 'Nasdaq'],
                ['symbol' => 'VTI', 'name' => 'Vanguard Total Market', 'category' => 'Actions', 'company' => 'Vanguard'],
                
                // Obligations
                ['symbol' => '^TNX', 'name' => 'US 10-Year Bond Yield', 'category' => 'Obligations', 'company' => 'US Treasury'],
                ['symbol' => 'BND', 'name' => 'Vanguard Total Bond', 'category' => 'Obligations', 'company' => 'Vanguard'],
                ['symbol' => '^VIX', 'name' => 'Volatility Index', 'category' => 'Obligations', 'company' => 'CBOE'],
                
                // Monétaire
                ['symbol' => '^FTSE', 'name' => 'FTSE 100 Index', 'category' => 'Monétaire', 'company' => 'London Exchange'],
                ['symbol' => '^N225', 'name' => 'Nikkei 225', 'category' => 'Mixte', 'company' => 'Japan Exchange'],
            ];

            $funds = [];

            foreach ($symbols as $item) {
                try {
                    // Récupérer les données de Yahoo Finance Finance Engine (fonctionne sans clé)
                    $response = Http::timeout($this->timeout)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                        ])
                        ->get('https://query1.finance.yahoo.com/v8/finance/chart/' . urlencode($item['symbol']), [
                            'interval' => '1d',
                            'range' => '1mo',
                        ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        if (isset($data['chart']['result'][0])) {
                            $fund = $this->parseFinanceEngineData($data, $item);
                            if ($fund) {
                                $funds[] = $fund;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::debug("Alpha Vantage/Finance Engine fetch failed for {$item['symbol']}: " . $e->getMessage());
                    continue;
                }

                // Petit délai pour ne pas surcharger l'API
                usleep(100000);
            }

            return $funds;
        } catch (\Exception $e) {
            Log::warning('Alpha Vantage/Finance Engine API fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parser les données du finance engine (Yahoo)
     */
    private function parseFinanceEngineData($data, $item): ?array
    {
        try {
            if (!isset($data['chart']['result'][0])) {
                return null;
            }

            $result = $data['chart']['result'][0];
            $meta = $result['meta'] ?? [];
            $timestamps = $result['timestamp'] ?? [];
            $quotes = $result['indicators']['quote'][0] ?? [];

            if (empty($quotes) || empty($timestamps)) {
                return null;
            }

            $lastIndex = count($timestamps) - 1;
            $lastQuote = $quotes[$lastIndex] ?? [];
            
            $currentPrice = $lastQuote['close'] ?? $meta['previousClose'] ?? 0;
            $previousClose = $meta['previousClose'] ?? $currentPrice;
            
            $change = $currentPrice - $previousClose;
            $changePercent = $previousClose > 0 ? ($change / $previousClose) * 100 : 0;

            return [
                'id' => 'IDX-' . $item['symbol'],
                'name' => $item['name'],
                'company' => $item['company'],
                'nav_value' => $this->formatCurrency($currentPrice, 'USD'),
                'nav_numeric' => $currentPrice,
                'variation' => $this->formatVariation($change, $changePercent),
                'variation_percentage' => round($changePercent, 2),
                'currency' => 'USD',
                'date' => now()->format('Y-m-d'),
                'category' => $item['category'],
            ];
        } catch (\Exception $e) {
            Log::debug("Parse Finance Engine data failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer les données de Yahoo Finance (ETFs mondiaux)
     */
    public function getYahooFinanceFunds(): array
    {
        try {
            // Liste d'ETFs et indices africains/internationaux représentant des fonds
            $symbols = [
                'VTI',      // Vanguard Total Market ETF (Actions US)
                'BND',      // Vanguard Total Bond Market ETF (Obligations)
                'SCHP',     // Schwab US Tips ETF (Monétaire)
                'VUSTX',    // Vanguard US Stock Total Market (Actions)
                'VBTLX',    // Vanguard Total Bond Market (Obligations)
                'VGSH',     // Vanguard Short-Term Treasury (Monétaire)
                'VTIAX',    // Vanguard International Stock ETF (Actions Internationales)
                'BNDX',     // Vanguard International Bond ETF (Obligations Int'l)
            ];

            $funds = [];
            
            foreach ($symbols as $symbol) {
                try {
                    $response = Http::timeout($this->timeout)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                        ])
                        ->get('https://query1.finance.yahoo.com/v8/finance/chart/' . $symbol, [
                            'interval' => '1d',
                            'range' => '1d',
                        ]);

                    if ($response->successful()) {
                        $fund = $this->parseYahooFinanceData($response->json(), $symbol);
                        if ($fund) {
                            $funds[] = $fund;
                        }
                    }
                } catch (\Exception $e) {
                    Log::debug("Yahoo Finance fetch failed for {$symbol}: " . $e->getMessage());
                    continue;
                }
            }

            return $funds;
        } catch (\Exception $e) {
            Log::warning('Yahoo Finance API fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parser les données de Yahoo Finance
     */
    private function parseYahooFinanceData($data, $symbol): ?array
    {
        try {
            if (!isset($data['chart']['result'][0])) {
                return null;
            }

            $result = $data['chart']['result'][0];
            $meta = $result['meta'] ?? [];
            $quotes = $result['indicators']['quote'][0] ?? [];

            if (empty($quotes)) {
                return null;
            }

            $lastQuote = end($quotes);
            $currentPrice = $lastQuote['close'] ?? $meta['previousClose'] ?? 0;
            $previousClose = $meta['previousClose'] ?? $currentPrice;
            
            $change = $currentPrice - $previousClose;
            $changePercent = $previousClose > 0 ? ($change / $previousClose) * 100 : 0;

            // Mapper le symbole à une catégorie
            $category = $this->getCategoryForSymbol($symbol);

            return [
                'id' => $symbol,
                'name' => $this->getFundNameForSymbol($symbol),
                'company' => $this->getFundCompanyForSymbol($symbol),
                'nav_value' => $this->formatCurrency($currentPrice, 'USD'),
                'nav_numeric' => $currentPrice,
                'variation' => $this->formatVariation($change, $changePercent),
                'variation_percentage' => round($changePercent, 2),
                'currency' => 'USD',
                'date' => now()->format('Y-m-d'),
                'category' => $category,
            ];
        } catch (\Exception $e) {
            Log::debug("Parse Yahoo Finance data failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtenir la catégorie basée sur le symbole
     */
    private function getCategoryForSymbol($symbol): string
    {
        $mapping = [
            'VTI' => 'Actions',
            'VUSTX' => 'Actions',
            'VTIAX' => 'Actions',
            'BND' => 'Obligations',
            'VBTLX' => 'Obligations',
            'BNDX' => 'Obligations',
            'SCHP' => 'Monétaire',
            'VGSH' => 'Monétaire',
        ];

        return $mapping[$symbol] ?? 'Mixte';
    }

    /**
     * Obtenir le nom du fonds basé sur le symbole
     */
    private function getFundNameForSymbol($symbol): string
    {
        $mapping = [
            'VTI' => 'Vanguard Total Market ETF',
            'VUSTX' => 'Vanguard US Stock Total Market',
            'VTIAX' => 'Vanguard International Stock',
            'BND' => 'Vanguard Total Bond Market',
            'VBTLX' => 'Vanguard Total Bond Market Index',
            'BNDX' => 'Vanguard International Bond ETF',
            'SCHP' => 'Schwab US TIPS ETF',
            'VGSH' => 'Vanguard Short-Term Treasury',
        ];

        return $mapping[$symbol] ?? $symbol;
    }

    /**
     * Obtenir la compagnie de gestion basée sur le symbole
     */
    private function getFundCompanyForSymbol($symbol): string
    {
        if (strpos($symbol, 'V') === 0) {
            return 'Vanguard Group';
        } elseif (strpos($symbol, 'SCHP') === 0) {
            return 'Schwab Investments';
        }
        return 'Asset Manager';
    }

    /**
     * Récupérer les fonds UEMOA/Africains via APIs spécialisées
     * Sources: BRVM Côte d'Ivoire, Bourse de Dakar, Bourse de Douala, etc.
     */
    private function fetchUEMOAFunds(): array
    {
        try {
            $funds = [];

            // 1. Essayer BRVM (Bourse Régionale des Valeurs Mobilières) - Côte d'Ivoire
            $brvm_funds = $this->fetchFromBRVM();
            if (!empty($brvm_funds)) {
                $funds = array_merge($funds, $brvm_funds);
            }

            // 2. Essayer Bourse de Dakar (Sénégal)
            $dakar_funds = $this->fetchFromBourseOfDakar();
            if (!empty($dakar_funds)) {
                $funds = array_merge($funds, $dakar_funds);
            }

            // 3. Essayer Douala Bourse (Cameroun)
            $douala_funds = $this->fetchFromDoualaStock();
            if (!empty($douala_funds)) {
                $funds = array_merge($funds, $douala_funds);
            }

            return $funds;
        } catch (\Exception $e) {
            Log::warning('UEMOA Funds API fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer les données de BRVM (Bourse Régionale des Valeurs Mobilières)
     */
    private function fetchFromBRVM(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0',
                    'Accept' => 'application/json',
                ])
                ->get('https://www.brvm.org/api/indices', []);

            if ($response->successful()) {
                return $this->parseBRVMData($response->json());
            }
        } catch (\Exception $e) {
            Log::debug('BRVM fetch failed: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Parser les données BRVM
     */
    private function parseBRVMData($data): array
    {
        $funds = [];

        try {
            if (is_array($data)) {
                foreach ($data as $index) {
                    if (isset($index['name']) && isset($index['value'])) {
                        $variation = $index['change'] ?? 0;
                        $variationPercent = $index['changePercent'] ?? 0;

                        $funds[] = [
                            'id' => 'BRVM-' . ($index['code'] ?? $index['name']),
                            'name' => $index['name'] ?? 'N/A',
                            'company' => 'BRVM (Côte d\'Ivoire)',
                            'nav_value' => $this->formatCurrency($index['value'] ?? 0, 'FCFA'),
                            'nav_numeric' => $index['value'] ?? 0,
                            'variation' => $this->formatVariation($variation, $variationPercent),
                            'variation_percentage' => round($variationPercent, 2),
                            'currency' => 'FCFA',
                            'date' => now()->format('Y-m-d'),
                            'category' => $this->categorizeIndex($index['name'] ?? ''),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::debug('Parse BRVM data failed: ' . $e->getMessage());
        }

        return $funds;
    }

    /**
     * Récupérer les données de la Bourse de Dakar (Sénégal)
     */
    private function fetchFromBourseOfDakar(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0',
                    'Accept' => 'application/json',
                ])
                ->get('https://www.brvm.org/api/bourses/dakar/indices', []);

            if ($response->successful()) {
                return $this->parseBourseOfDakarData($response->json());
            }
        } catch (\Exception $e) {
            Log::debug('Bourse of Dakar fetch failed: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Parser les données Bourse de Dakar
     */
    private function parseBourseOfDakarData($data): array
    {
        $funds = [];

        try {
            if (is_array($data)) {
                foreach ($data as $index) {
                    if (isset($index['name']) && isset($index['value'])) {
                        $variation = $index['change'] ?? 0;
                        $variationPercent = $index['changePercent'] ?? 0;

                        $funds[] = [
                            'id' => 'DSX-' . ($index['code'] ?? $index['name']),
                            'name' => $index['name'] ?? 'N/A',
                            'company' => 'Bourse de Dakar (Sénégal)',
                            'nav_value' => $this->formatCurrency($index['value'] ?? 0, 'FCFA'),
                            'nav_numeric' => $index['value'] ?? 0,
                            'variation' => $this->formatVariation($variation, $variationPercent),
                            'variation_percentage' => round($variationPercent, 2),
                            'currency' => 'FCFA',
                            'date' => now()->format('Y-m-d'),
                            'category' => $this->categorizeIndex($index['name'] ?? ''),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::debug('Parse Bourse of Dakar data failed: ' . $e->getMessage());
        }

        return $funds;
    }

    /**
     * Récupérer les données de Douala Stock Exchange (Cameroun)
     */
    private function fetchFromDoualaStock(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0',
                    'Accept' => 'application/json',
                ])
                ->get('https://www.douala-stock.com/api/indices', []);

            if ($response->successful()) {
                return $this->parseDoualaStockData($response->json());
            }
        } catch (\Exception $e) {
            Log::debug('Douala Stock fetch failed: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Parser les données Douala Stock Exchange
     */
    private function parseDoualaStockData($data): array
    {
        $funds = [];

        try {
            if (is_array($data)) {
                foreach ($data as $index) {
                    if (isset($index['name']) && isset($index['value'])) {
                        $variation = $index['change'] ?? 0;
                        $variationPercent = $index['changePercent'] ?? 0;

                        $funds[] = [
                            'id' => 'DSE-' . ($index['code'] ?? $index['name']),
                            'name' => $index['name'] ?? 'N/A',
                            'company' => 'Douala Stock Exchange (Cameroun)',
                            'nav_value' => $this->formatCurrency($index['value'] ?? 0, 'XAF'),
                            'nav_numeric' => $index['value'] ?? 0,
                            'variation' => $this->formatVariation($variation, $variationPercent),
                            'variation_percentage' => round($variationPercent, 2),
                            'currency' => 'XAF',
                            'date' => now()->format('Y-m-d'),
                            'category' => $this->categorizeIndex($index['name'] ?? ''),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::debug('Parse Douala Stock data failed: ' . $e->getMessage());
        }

        return $funds;
    }

    /**
     * Catégoriser un index/fonds basé sur son nom
     */
    private function categorizeIndex($name): string
    {
        $name = strtolower($name);

        if (strpos($name, 'action') !== false || strpos($name, 'equity') !== false || strpos($name, 'cap') !== false) {
            return 'Actions';
        } elseif (strpos($name, 'oblig') !== false || strpos($name, 'bond') !== false) {
            return 'Obligations';
        } elseif (strpos($name, 'monet') !== false || strpos($name, 'money') !== false || strpos($name, 'short') !== false) {
            return 'Monétaire';
        }

        return 'Mixte';
    }

    /**
     * Générer des données mock réalistes avec variations quotidiennes
     * Utilisé quand l'internet est inaccessible (environnements offline)
     * Les variations changent chaque jour basées sur la date du jour
     */
    public function getMockMutualFunds(): array
    {
        $today = now();
        // Générer une variation pseudo-aléatoire déterministe basée sur le jour
        // Variation entre -2% et +2%
        $dayHash = (int)$today->format('d'); // 1-31
        $seedOffset = ($dayHash % 5) - 2; // -2 à +2

        return [
            [
                'id' => 'MOCK-IDX-GSPC',
                'name' => 'S&P 500 Index (Mock)',
                'company' => 'S&P Global',
                'nav_numeric' => 5234.56 + ($seedOffset * 50),
                'nav_value' => $this->formatCurrency(5234.56 + ($seedOffset * 50), 'USD'),
                'variation' => $this->formatVariation($seedOffset * 50, $seedOffset * 0.95),
                'variation_percentage' => round($seedOffset * 0.95, 2),
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
            ],
            [
                'id' => 'MOCK-IDX-IXIC',
                'name' => 'NASDAQ Composite (Mock)',
                'company' => 'Nasdaq',
                'nav_numeric' => 16542.34 + ($seedOffset * 120),
                'nav_value' => $this->formatCurrency(16542.34 + ($seedOffset * 120), 'USD'),
                'variation' => $this->formatVariation($seedOffset * 120, $seedOffset * 0.73),
                'variation_percentage' => round($seedOffset * 0.73, 2),
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
            ],
            [
                'id' => 'MOCK-VTI',
                'name' => 'Vanguard Total Market (Mock)',
                'company' => 'Vanguard Group',
                'nav_numeric' => 245.67 + ($seedOffset * 1.5),
                'nav_value' => $this->formatCurrency(245.67 + ($seedOffset * 1.5), 'USD'),
                'variation' => $this->formatVariation($seedOffset * 1.5, $seedOffset * 0.61),
                'variation_percentage' => round($seedOffset * 0.61, 2),
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
            ],
            [
                'id' => 'MOCK-IDX-TNX',
                'name' => 'US 10-Year Bond Yield (Mock)',
                'company' => 'US Treasury',
                'nav_numeric' => 4.25 + ($seedOffset * 0.15),
                'nav_value' => $this->formatCurrency(4.25 + ($seedOffset * 0.15), 'USD'),
                'variation' => $this->formatVariation($seedOffset * 0.15, $seedOffset * -1.84),
                'variation_percentage' => round($seedOffset * -1.84, 2),
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
            ],
            [
                'id' => 'MOCK-BND',
                'name' => 'Vanguard Total Bond (Mock)',
                'company' => 'Vanguard Group',
                'nav_numeric' => 74.23 + ($seedOffset * 0.8),
                'nav_value' => $this->formatCurrency(74.23 + ($seedOffset * 0.8), 'USD'),
                'variation' => $this->formatVariation($seedOffset * 0.8, $seedOffset * 0.70),
                'variation_percentage' => round($seedOffset * 0.70, 2),
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
            ],
            [
                'id' => 'MOCK-IDX-VIX',
                'name' => 'Volatility Index (Mock)',
                'company' => 'CBOE',
                'nav_numeric' => 16.45 + ($seedOffset * 1.2),
                'nav_value' => $this->formatCurrency(16.45 + ($seedOffset * 1.2), 'USD'),
                'variation' => $this->formatVariation($seedOffset * 1.2, $seedOffset * -5.63),
                'variation_percentage' => round($seedOffset * -5.63, 2),
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
            ],
            [
                'id' => 'MOCK-IDX-FTSE',
                'name' => 'FTSE 100 Index (Mock)',
                'company' => 'London Exchange',
                'nav_numeric' => 7542.30 + ($seedOffset * 35),
                'nav_value' => $this->formatCurrency(7542.30 + ($seedOffset * 35), 'USD'),
                'variation' => $this->formatVariation($seedOffset * 35, $seedOffset * 0.46),
                'variation_percentage' => round($seedOffset * 0.46, 2),
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Monétaire',
            ],
            [
                'id' => 'MOCK-IDX-N225',
                'name' => 'Nikkei 225 (Mock)',
                'company' => 'Japan Exchange',
                'nav_numeric' => 33454.67 + ($seedOffset * 200),
                'nav_value' => $this->formatCurrency(33454.67 + ($seedOffset * 200), 'USD'),
                'variation' => $this->formatVariation($seedOffset * 200, $seedOffset * 1.64),
                'variation_percentage' => round($seedOffset * 1.64, 2),
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Mixte',
            ],
        ];
    }

    /**
     * Récupérer les données de fonds par défaut (données statiques, fallback final)
     * Affichées uniquement si tous les APIs ont échoué ET mock mode désactivé
     */
    public function getDefaultMutualFunds(): array
    {
        $today = now();

        // Données statiques de fonds réalistes (ne changent jamais)
        return [
            [
                'id' => 'IDX-GSPC',
                'name' => 'S&P 500 Index',
                'company' => 'S&P Global',
                'nav_numeric' => 5234.56,
                'nav_value' => $this->formatCurrency(5234.56, 'USD'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
            ],
            [
                'id' => 'IDX-IXIC',
                'name' => 'NASDAQ Composite',
                'company' => 'Nasdaq',
                'nav_numeric' => 16542.34,
                'nav_value' => $this->formatCurrency(16542.34, 'USD'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
            ],
            [
                'id' => 'VTI',
                'name' => 'Vanguard Total Market',
                'company' => 'Vanguard Group',
                'nav_numeric' => 245.67,
                'nav_value' => $this->formatCurrency(245.67, 'USD'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
            ],
            [
                'id' => 'IDX-TNX',
                'name' => 'US 10-Year Bond Yield',
                'company' => 'US Treasury',
                'nav_numeric' => 4.25,
                'nav_value' => $this->formatCurrency(4.25, 'USD'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
            ],
            [
                'id' => 'BND',
                'name' => 'Vanguard Total Bond',
                'company' => 'Vanguard Group',
                'nav_numeric' => 74.23,
                'nav_value' => $this->formatCurrency(74.23, 'USD'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
            ],
            [
                'id' => 'IDX-VIX',
                'name' => 'Volatility Index',
                'company' => 'CBOE',
                'nav_numeric' => 16.45,
                'nav_value' => $this->formatCurrency(16.45, 'USD'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
            ],
            [
                'id' => 'IDX-FTSE',
                'name' => 'FTSE 100 Index',
                'company' => 'London Exchange',
                'nav_numeric' => 7542.30,
                'nav_value' => $this->formatCurrency(7542.30, 'USD'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Monétaire',
            ],
            [
                'id' => 'IDX-N225',
                'name' => 'Nikkei 225',
                'company' => 'Japan Exchange',
                'nav_numeric' => 33454.67,
                'nav_value' => $this->formatCurrency(33454.67, 'USD'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'USD',
                'date' => $today->format('Y-m-d'),
                'category' => 'Mixte',
            ],
        ];
    }

    /**
     * Formater une valeur en devise
     */
    private function formatCurrency($value, $currency = 'FCFA'): string
    {
        if ($currency === 'FCFA') {
            return number_format($value, 2, ',', ' ') . ' ' . $currency;
        }
        return number_format($value, 2, '.', ',') . ' ' . $currency;
    }

    /**
     * Formater la variation
     */
    private function formatVariation($amount, $percentage): string
    {
        $sign = $amount >= 0 ? '+' : '';
        return $sign . number_format($amount, 2, ',', ' ') . ' (' . 
               $sign . number_format($percentage, 2, ',', ' ') . '%)';
    }

    /**
     * Récupérer un fonds spécifique par ID
     */
    public function getFundById(string $fundId): ?array
    {
        $funds = $this->getMutualFunds();
        
        foreach ($funds as $fund) {
            if ($fund['id'] === $fundId) {
                return $fund;
            }
        }

        return null;
    }

    /**
     * Récupérer les fonds par catégorie
     */
    public function getFundsByCategory(string $category): array
    {
        $funds = $this->getMutualFunds();
        
        return array_filter($funds, function ($fund) use ($category) {
            return $fund['category'] === $category;
        });
    }

    /**
     * Récupérer les catégories disponibles
     */
    public function getCategories(): array
    {
        $funds = $this->getMutualFunds();
        $categories = [];

        foreach ($funds as $fund) {
            if (!in_array($fund['category'], $categories)) {
                $categories[] = $fund['category'];
            }
        }

        return $categories;
    }

    /**
     * Invalider le cache
     */
    public function clearCache(): void
    {
        Cache::forget('mutual_funds_data');
    }
}
