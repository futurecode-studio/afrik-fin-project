<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BRVMApiService
{
    private $apiUrl;
    private $apiKey;
    private $cacheDuration;

    public function __construct()
    {
        $this->apiUrl = config('services.brvm.api_url');
        $this->apiKey = config('services.brvm.api_key');
        $this->cacheDuration = config('services.brvm.cache_duration', 300);
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

        return Cache::remember('brvm_stocks', $this->cacheDuration, function () {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Accept' => 'application/json',
                    ])
                    ->get($this->apiUrl . '/stocks');

                if ($response->successful()) {
                    $data = $response->json();
                    $stocks = $data['data'] ?? $data;
                    
                    // Synchroniser avec la base de données
                    $this->syncStocksWithDatabase($stocks);
                    
                    return $stocks;
                }

                Log::warning('BRVM API responded with error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return $this->getStocksFromDatabase();

            } catch (\Exception $e) {
                Log::error('BRVM API Exception: ' . $e->getMessage());
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

        return Cache::remember('brvm_indices', $this->cacheDuration, function () {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Accept' => 'application/json',
                    ])
                    ->get($this->apiUrl . '/indices');

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? $data;
                }

                return $this->getDefaultIndices();

            } catch (\Exception $e) {
                Log::error('BRVM Indices API Exception: ' . $e->getMessage());
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
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->apiUrl . '/stocks/' . $symbol);

            if ($response->successful()) {
                return $response->json();
            }

            return Stock::where('symbol', $symbol)->first();

        } catch (\Exception $e) {
            Log::error('BRVM Single Stock API Exception: ' . $e->getMessage());
            return Stock::where('symbol', $symbol)->first();
        }
    }

    /**
     * Rafraîchir les données (vider le cache)
     */
    public function refreshData()
    {
        Cache::forget('brvm_stocks');
        Cache::forget('brvm_indices');
    }

    /**
     * Synchroniser les données de l'API avec la base de données
     */
    private function syncStocksWithDatabase($apiStocks)
    {
        try {
            foreach ($apiStocks as $stockData) {
                // Adapter les noms de champs selon le format de l'API
                $symbol = strtoupper((string) ($stockData['symbol'] ?? $stockData['ticker'] ?? ''));
                $sector = match ($symbol) {
                    'BIIC' => 'Finance',
                    'NTLC' => 'Industrie',
                    default => $stockData['sector'] ?? $stockData['industry'] ?? null,
                };

                Stock::updateOrCreate(
                    ['symbol' => $symbol],
                    [
                        'company_name' => $stockData['company_name'] ?? $stockData['name'] ?? $stockData['companyName'],
                        'current_price' => $stockData['current_price'] ?? $stockData['price'] ?? $stockData['lastPrice'],
                        'previous_price' => $stockData['previous_price'] ?? $stockData['previous_close'] ?? $stockData['previousClose'],
                        'variation_percent' => $stockData['variation_percent'] ?? $stockData['change_percent'] ?? $stockData['changePercent'] ?? 0,
                        'volume' => $stockData['volume'] ?? $stockData['tradedVolume'] ?? 0,
                        'market_cap' => $stockData['market_cap'] ?? $stockData['marketCap'] ?? null,
                        'sector' => $sector,
                        'high_price' => $stockData['high_price'] ?? $stockData['high'] ?? $stockData['dayHigh'] ?? null,
                        'low_price' => $stockData['low_price'] ?? $stockData['low'] ?? $stockData['dayLow'] ?? null,
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
     * Récupérer les données depuis la base de données
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
     * Indices par défaut
     */
    private function getDefaultIndices()
    {
        return [
            [
                'name' => 'BRVM 10',
                'value' => 160.45,
                'variation' => 1.2,
                'positive' => true,
            ],
            [
                'name' => 'BRVM Composite',
                'value' => 215.78,
                'variation' => 0.8,
                'positive' => true,
            ],
            [
                'name' => 'BRVM Prestige',
                'value' => 102.34,
                'variation' => -0.3,
                'positive' => false,
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
                'message' => 'API non configurée. Veuillez ajouter BRVM_API_URL et BRVM_API_KEY dans votre fichier .env'
            ];
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->apiUrl . '/health');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connexion réussie à l\'API BRVM',
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur de connexion: ' . $response->status(),
                'error' => $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
