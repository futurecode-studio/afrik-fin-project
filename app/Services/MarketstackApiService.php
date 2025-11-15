<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MarketstackApiService
{
    private $apiUrl;
    private $apiKey;
    private $cacheDuration;

    public function __construct()
    {
        $this->apiUrl = config('services.marketstack.api_url');
        $this->apiKey = config('services.marketstack.api_key');
        $this->cacheDuration = config('services.marketstack.cache_duration', 300);
    }

    /**
     * Vérifier si l'API est configurée
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiUrl) && !empty($this->apiKey);
    }

    /**
     * Récupérer toutes les données boursières
     */
    public function getStocks()
    {
        if (!$this->isConfigured()) {
            return $this->getStocksFromDatabase();
        }

        return Cache::remember('marketstack_stocks', $this->cacheDuration, function () {
            try {
                // Récupérer les symboles des actions depuis la base de données
                $symbols = Stock::where('is_active', true)->pluck('symbol')->toArray();
                
                if (empty($symbols)) {
                    return $this->getStocksFromDatabase();
                }

                // Marketstack utilise une limite de symboles par requête
                $symbolsString = implode(',', array_slice($symbols, 0, 100)); // Limite à 100 symboles
                
                $response = Http::timeout(15)
                    ->get($this->apiUrl . '/eod/latest', [
                        'access_key' => $this->apiKey,
                        'symbols' => $symbolsString,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $apiStocks = $data['data'] ?? [];
                    
                    if (!empty($apiStocks)) {
                        // Synchroniser avec la base de données (backup)
                        $this->syncStocksWithDatabase($apiStocks);
                        
                        // Formater et retourner les données de l'API directement
                        return $this->formatApiStocks($apiStocks);
                    }
                }

                Log::warning('Marketstack API responded with error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return $this->getStocksFromDatabase();

            } catch (\Exception $e) {
                Log::error('Marketstack API Exception: ' . $e->getMessage());
                return $this->getStocksFromDatabase();
            }
        });
    }

    /**
     * Récupérer les indices boursiers
     */
    public function getIndices()
    {
        if (!$this->isConfigured()) {
            return $this->getDefaultIndices();
        }

        return Cache::remember('marketstack_indices', $this->cacheDuration, function () {
            try {
                // Marketstack ne propose pas d'endpoint spécifique pour les indices BRVM
                // Utiliser les indices par défaut calculés
                return $this->getDefaultIndices();

            } catch (\Exception $e) {
                Log::error('Marketstack Indices API Exception: ' . $e->getMessage());
                return $this->getDefaultIndices();
            }
        });
    }

    /**
     * Récupérer un titre spécifique
     */
    public function getStock($symbol)
    {
        if (!$this->isConfigured()) {
            return Stock::where('symbol', $symbol)->first();
        }

        try {
            $response = Http::timeout(10)
                ->get($this->apiUrl . '/eod/latest', [
                    'access_key' => $this->apiKey,
                    'symbols' => $symbol,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $stockData = $data['data'][0] ?? null;
                
                if ($stockData) {
                    $this->syncStocksWithDatabase([$stockData]);
                }
                
                return Stock::where('symbol', $symbol)->first();
            }

            return Stock::where('symbol', $symbol)->first();

        } catch (\Exception $e) {
            Log::error('Marketstack Single Stock API Exception: ' . $e->getMessage());
            return Stock::where('symbol', $symbol)->first();
        }
    }

    /**
     * Rafraîchir les données (vider le cache)
     */
    public function refreshData()
    {
        Cache::forget('marketstack_stocks');
        Cache::forget('marketstack_indices');
    }

    /**
     * Synchroniser les données de l'API avec la base de données
     */
    private function syncStocksWithDatabase($apiStocks)
    {
        try {
            foreach ($apiStocks as $stockData) {
                // Format Marketstack API
                // {
                //   "symbol": "AAPL",
                //   "exchange": "XNAS",
                //   "date": "2025-01-15",
                //   "open": 225.57,
                //   "high": 227.67,
                //   "low": 224.42,
                //   "close": 226.50,
                //   "volume": 42516184,
                //   "adj_high": 227.67,
                //   "adj_low": 224.42,
                //   "adj_close": 226.50,
                //   "adj_open": 225.57,
                //   "adj_volume": 42516184
                // }
                
                $currentPrice = $stockData['close'] ?? $stockData['adj_close'] ?? 0;
                $previousPrice = $stockData['open'] ?? $stockData['adj_open'] ?? $currentPrice;
                
                // Calculer la variation
                $variationPercent = 0;
                if ($previousPrice > 0 && $currentPrice != $previousPrice) {
                    $variationPercent = (($currentPrice - $previousPrice) / $previousPrice) * 100;
                }
                
                Stock::updateOrCreate(
                    ['symbol' => $stockData['symbol']],
                    [
                        'current_price' => $currentPrice,
                        'previous_price' => $previousPrice,
                        'variation_percent' => round($variationPercent, 2),
                        'volume' => $stockData['volume'] ?? $stockData['adj_volume'] ?? 0,
                        'high_price' => $stockData['high'] ?? $stockData['adj_high'] ?? null,
                        'low_price' => $stockData['low'] ?? $stockData['adj_low'] ?? null,
                        'is_active' => true,
                        'last_updated' => now(),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Error syncing stocks with database: ' . $e->getMessage());
        }
    }

    /**
     * Formater les données de l'API Marketstack pour l'affichage
     */
    private function formatApiStocks($apiStocks)
    {
        $formattedStocks = [];
        
        foreach ($apiStocks as $stockData) {
            // Récupérer les infos complémentaires depuis la base de données
            $dbStock = Stock::where('symbol', $stockData['symbol'])->first();
            
            $currentPrice = $stockData['close'] ?? $stockData['adj_close'] ?? 0;
            $previousPrice = $stockData['open'] ?? $stockData['adj_open'] ?? $currentPrice;
            
            // Calculer la variation
            $variationPercent = 0;
            if ($previousPrice > 0 && $currentPrice != $previousPrice) {
                $variationPercent = (($currentPrice - $previousPrice) / $previousPrice) * 100;
            }
            
            $formattedStocks[] = [
                'symbol' => $stockData['symbol'],
                'company_name' => $dbStock->company_name ?? $stockData['symbol'],
                'current_price' => $currentPrice,
                'previous_price' => $previousPrice,
                'variation_percent' => round($variationPercent, 2),
                'volume' => $stockData['volume'] ?? $stockData['adj_volume'] ?? 0,
                'market_cap' => $dbStock->market_cap ?? null,
                'sector' => $dbStock->sector ?? null,
                'high_price' => $stockData['high'] ?? $stockData['adj_high'] ?? null,
                'low_price' => $stockData['low'] ?? $stockData['adj_low'] ?? null,
            ];
        }
        
        return $formattedStocks;
    }

    /**
     * Récupérer les données depuis la base de données (fallback)
     */
    private function getStocksFromDatabase()
    {
        return Stock::where('is_active', true)
            ->orderBy('symbol')
            ->get()
            ->map(function ($stock) {
                return [
                    'symbol' => $stock->symbol,
                    'company_name' => $stock->company_name,
                    'current_price' => $stock->current_price,
                    'previous_price' => $stock->previous_price,
                    'variation_percent' => $stock->variation_percent,
                    'volume' => $stock->volume,
                    'market_cap' => $stock->market_cap,
                    'sector' => $stock->sector,
                    'high_price' => $stock->high_price,
                    'low_price' => $stock->low_price,
                ];
            })
            ->toArray();
    }

    /**
     * Indices par défaut calculés depuis les actions
     */
    private function getDefaultIndices()
    {
        $stocks = Stock::where('is_active', true)->get();
        
        if ($stocks->isEmpty()) {
            return [
                [
                    'name' => 'BRVM 10',
                    'value' => 0,
                    'variation_percent' => 0,
                ],
                [
                    'name' => 'BRVM Composite',
                    'value' => 0,
                    'variation_percent' => 0,
                ],
            ];
        }

        // Calculer la moyenne des variations
        $avgVariation = $stocks->avg('variation_percent');
        $totalMarketCap = $stocks->sum('market_cap');
        
        return [
            [
                'name' => 'BRVM 10',
                'value' => round($totalMarketCap / 1000000, 2), // En millions
                'variation_percent' => round($avgVariation, 2),
            ],
            [
                'name' => 'BRVM Composite',
                'value' => round($totalMarketCap / 1000000 * 1.15, 2), // Estimation
                'variation_percent' => round($avgVariation * 0.95, 2),
            ],
        ];
    }

    /**
     * Tester la connexion à l'API
     */
    public function testConnection()
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'API non configurée',
                'data' => null,
            ];
        }

        try {
            $response = Http::timeout(5)
                ->get($this->apiUrl . '/eod/latest', [
                    'access_key' => $this->apiKey,
                    'symbols' => 'AAPL', // Test avec un symbole connu
                    'limit' => 1,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connexion réussie',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur API: ' . $response->status(),
                'data' => $response->body(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Récupérer les informations sur l'utilisation de l'API (si disponible)
     */
    public function getApiUsage()
    {
        // Marketstack ne fournit pas d'endpoint pour l'utilisation de l'API
        // Cette méthode pourrait être étendue si nécessaire
        return [
            'available' => false,
            'message' => 'Marketstack ne fournit pas de statistiques d\'utilisation via l\'API',
        ];
    }
}
