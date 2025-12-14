<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service de scraping pour récupérer les données BRVM en temps réel
 * Sources: RichBourse.com, BRVM.org (données africaines gratuites)
 */
class BRVMScraperService
{
    private $cacheDuration;
    private $timeout;
    private $richbourseUrl = 'https://www.richbourse.com';
    private $brvm_url = 'https://www.brvm.org';

    /**
     * Liste complète des symboles BRVM avec leurs informations
     */
    private $brvmSymbols = [
        // Secteur Finance
        'BOAB' => ['name' => 'BOA Bénin', 'sector' => 'Finance', 'country' => 'Bénin'],
        'BOABF' => ['name' => 'BOA Burkina Faso', 'sector' => 'Finance', 'country' => 'Burkina Faso'],
        'BOAC' => ['name' => 'BOA Côte d\'Ivoire', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire'],
        'BOAM' => ['name' => 'BOA Mali', 'sector' => 'Finance', 'country' => 'Mali'],
        'BOAN' => ['name' => 'BOA Niger', 'sector' => 'Finance', 'country' => 'Niger'],
        'BOAS' => ['name' => 'BOA Sénégal', 'sector' => 'Finance', 'country' => 'Sénégal'],
        'CBIBF' => ['name' => 'Coris Bank International', 'sector' => 'Finance', 'country' => 'Burkina Faso'],
        'ECOC' => ['name' => 'Ecobank Côte d\'Ivoire', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire'],
        'ETIT' => ['name' => 'Ecobank Transnational Inc.', 'sector' => 'Finance', 'country' => 'Togo'],
        'NSBC' => ['name' => 'NSIA Banque Côte d\'Ivoire', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire'],
        'ORGT' => ['name' => 'Oragroup', 'sector' => 'Finance', 'country' => 'Togo'],
        'SAFC' => ['name' => 'SAFCA', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire'],
        'SGBC' => ['name' => 'Société Générale CI', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire'],
        'SIBC' => ['name' => 'SIB Côte d\'Ivoire', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire'],
        'BICC' => ['name' => 'BICICI', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire'],

        // Secteur Télécommunications
        'SNTS' => ['name' => 'Sonatel', 'sector' => 'Télécommunications', 'country' => 'Sénégal'],
        'ONTBF' => ['name' => 'Onatel Burkina Faso', 'sector' => 'Télécommunications', 'country' => 'Burkina Faso'],
        'ORAC' => ['name' => 'Orange Côte d\'Ivoire', 'sector' => 'Télécommunications', 'country' => 'Côte d\'Ivoire'],

        // Secteur Industrie
        'CABC' => ['name' => 'Sicable', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'FTSC' => ['name' => 'Filtisac', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'NEIC' => ['name' => 'NEI-CEDA', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'SEMC' => ['name' => 'Crown SIEM', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'SLBC' => ['name' => 'Solibra', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'SMBC' => ['name' => 'SMB', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'STBC' => ['name' => 'SITAB', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'TTLC' => ['name' => 'Total CI', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'TTLS' => ['name' => 'Total Sénégal', 'sector' => 'Industrie', 'country' => 'Sénégal'],
        'UNLC' => ['name' => 'Unilever CI', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],
        'UNXC' => ['name' => 'Uniwax', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire'],

        // Secteur Agriculture
        'PALC' => ['name' => 'Palm CI', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire'],
        'PRSC' => ['name' => 'Sucrivoire', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire'],
        'SCRC' => ['name' => 'SUCAF CI', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire'],
        'SICC' => ['name' => 'SICOR', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire'],
        'SOGC' => ['name' => 'SOGB', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire'],
        'SPHC' => ['name' => 'SAPH', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire'],

        // Secteur Distribution
        'APTS' => ['name' => 'Vivo Energy CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire'],
        'BNBC' => ['name' => 'Bernabé CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire'],
        'CFAC' => ['name' => 'CFAO Motors CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire'],
        'PRSC' => ['name' => 'Tractafric Motors CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire'],
        'SHEC' => ['name' => 'Vivo Energy CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire'],
        'TTRC' => ['name' => 'Trituraf', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire'],

        // Secteur Services Publics
        'CIEC' => ['name' => 'CIE', 'sector' => 'Services Publics', 'country' => 'Côte d\'Ivoire'],
        'SDCC' => ['name' => 'SODECI', 'sector' => 'Services Publics', 'country' => 'Côte d\'Ivoire'],
        'SDSC' => ['name' => 'SDS', 'sector' => 'Services Publics', 'country' => 'Côte d\'Ivoire'],

        // Secteur Transport
        'SVOC' => ['name' => 'Movis', 'sector' => 'Transport', 'country' => 'Côte d\'Ivoire'],
        'STAC' => ['name' => 'SETAO', 'sector' => 'Transport', 'country' => 'Côte d\'Ivoire'],
    ];

    public function __construct()
    {
        $this->cacheDuration = config('services.brvm.cache_duration', 300);
        $this->timeout = config('services.brvm.timeout', 30);
    }

    /**
     * Récupérer toutes les données boursières BRVM
     */
    public function getStocks(): array
    {
        return Cache::remember('brvm_stocks', $this->cacheDuration, function () {
            try {
                // 1. Essayer RichBourse (source principale)
                $stocks = $this->fetchFromRichBourse();
                if (!empty($stocks)) {
                    Log::info('BRVM Stocks: RichBourse data loaded successfully (' . count($stocks) . ' stocks)');
                    $this->syncStocksWithDatabase($stocks);
                    return $stocks;
                }

                // 2. Essayer BRVM.org directement
                $stocks = $this->fetchFromBRVMDirect();
                if (!empty($stocks)) {
                    Log::info('BRVM Stocks: BRVM.org data loaded successfully (' . count($stocks) . ' stocks)');
                    $this->syncStocksWithDatabase($stocks);
                    return $stocks;
                }

                // 3. Fallback: données de la base de données
                Log::warning('BRVM Stocks: All sources failed, using database fallback');
                return $this->getStocksFromDatabase();

            } catch (\Exception $e) {
                Log::error('BRVM Scraper Exception: ' . $e->getMessage());
                return $this->getStocksFromDatabase();
            }
        });
    }

    /**
     * Récupérer les indices BRVM
     */
    public function getIndices(): array
    {
        return Cache::remember('brvm_indices', $this->cacheDuration, function () {
            try {
                // 1. Essayer RichBourse pour les indices
                $indices = $this->fetchIndicesFromRichBourse();
                if (!empty($indices)) {
                    Log::info('BRVM Indices: RichBourse data loaded successfully');
                    return $indices;
                }

                // 2. Essayer BRVM.org
                $indices = $this->fetchIndicesFromBRVM();
                if (!empty($indices)) {
                    Log::info('BRVM Indices: BRVM.org data loaded successfully');
                    return $indices;
                }

                // 3. Fallback: calculer depuis les stocks
                return $this->calculateIndicesFromStocks();

            } catch (\Exception $e) {
                Log::error('BRVM Indices Exception: ' . $e->getMessage());
                return $this->calculateIndicesFromStocks();
            }
        });
    }

    /**
     * Scraper les données depuis RichBourse
     */
    private function fetchFromRichBourse(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
                ])
                ->get($this->richbourseUrl . '/cours/brvm');

            if (!$response->successful()) {
                Log::debug('RichBourse: HTTP ' . $response->status());
                return [];
            }

            return $this->parseRichBoursePage($response->body());

        } catch (\Exception $e) {
            Log::debug('RichBourse fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parser la page RichBourse pour extraire les cours
     */
    private function parseRichBoursePage(string $html): array
    {
        $stocks = [];

        try {
            // Pattern pour extraire les lignes du tableau des cours
            // Format typique: <tr>...<td>SYMBOL</td><td>PRICE</td><td>VARIATION</td>...</tr>
            $pattern = '/<tr[^>]*>.*?<td[^>]*>([A-Z]{2,6})<\/td>.*?<td[^>]*>([\d\s,\.]+)<\/td>.*?<td[^>]*class="[^"]*(?:pos|neg|neu)[^"]*"[^>]*>([+-]?[\d,\.]+)\s*%?<\/td>/is';
            
            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $symbol = strtoupper(trim($match[1]));
                    $price = $this->parseNumber($match[2]);
                    $variation = $this->parseNumber($match[3]);

                    if ($price > 0 && isset($this->brvmSymbols[$symbol])) {
                        $info = $this->brvmSymbols[$symbol];
                        $stocks[] = [
                            'symbol' => $symbol,
                            'company_name' => $info['name'],
                            'current_price' => $price,
                            'previous_price' => $price / (1 + ($variation / 100)),
                            'variation_percent' => round($variation, 2),
                            'volume' => 0,
                            'sector' => $info['sector'],
                            'country' => $info['country'],
                            'source' => 'richbourse',
                        ];
                    }
                }
            }

            // Pattern alternatif si le premier ne fonctionne pas
            if (empty($stocks)) {
                $stocks = $this->parseRichBourseAlternative($html);
            }

        } catch (\Exception $e) {
            Log::debug('Parse RichBourse failed: ' . $e->getMessage());
        }

        return $stocks;
    }

    /**
     * Parser alternatif pour RichBourse
     */
    private function parseRichBourseAlternative(string $html): array
    {
        $stocks = [];

        // Essayer de trouver les données dans un format JSON embarqué
        if (preg_match('/var\s+stockData\s*=\s*(\[.*?\]);/s', $html, $match)) {
            try {
                $data = json_decode($match[1], true);
                if (is_array($data)) {
                    foreach ($data as $item) {
                        $symbol = $item['symbol'] ?? $item['ticker'] ?? null;
                        if ($symbol && isset($this->brvmSymbols[$symbol])) {
                            $info = $this->brvmSymbols[$symbol];
                            $stocks[] = [
                                'symbol' => $symbol,
                                'company_name' => $info['name'],
                                'current_price' => $item['price'] ?? $item['close'] ?? 0,
                                'previous_price' => $item['previous'] ?? $item['open'] ?? 0,
                                'variation_percent' => $item['change'] ?? $item['variation'] ?? 0,
                                'volume' => $item['volume'] ?? 0,
                                'sector' => $info['sector'],
                                'country' => $info['country'],
                                'source' => 'richbourse',
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::debug('Parse RichBourse JSON failed: ' . $e->getMessage());
            }
        }

        return $stocks;
    }

    /**
     * Scraper les données directement depuis BRVM.org
     */
    private function fetchFromBRVMDirect(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'fr-FR,fr;q=0.9',
                ])
                ->get($this->brvm_url . '/fr/cours-actions/0');

            if (!$response->successful()) {
                Log::debug('BRVM.org: HTTP ' . $response->status());
                return [];
            }

            return $this->parseBRVMPage($response->body());

        } catch (\Exception $e) {
            Log::debug('BRVM.org fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parser la page BRVM.org
     */
    private function parseBRVMPage(string $html): array
    {
        $stocks = [];

        try {
            // Pattern pour le tableau des cours BRVM
            $pattern = '/<tr[^>]*>.*?<td[^>]*>.*?<a[^>]*>([A-Z]{2,6})<\/a>.*?<\/td>.*?<td[^>]*>([\d\s,\.]+)<\/td>.*?<td[^>]*>([\d\s,\.]+)<\/td>.*?<td[^>]*class="[^"]*"[^>]*>([+-]?[\d,\.]+)/is';

            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $symbol = strtoupper(trim($match[1]));
                    $currentPrice = $this->parseNumber($match[2]);
                    $previousPrice = $this->parseNumber($match[3]);
                    $variation = $this->parseNumber($match[4]);

                    if ($currentPrice > 0) {
                        $info = $this->brvmSymbols[$symbol] ?? [
                            'name' => $symbol,
                            'sector' => 'Autre',
                            'country' => 'UEMOA'
                        ];

                        $stocks[] = [
                            'symbol' => $symbol,
                            'company_name' => $info['name'],
                            'current_price' => $currentPrice,
                            'previous_price' => $previousPrice > 0 ? $previousPrice : $currentPrice,
                            'variation_percent' => round($variation, 2),
                            'volume' => 0,
                            'sector' => $info['sector'],
                            'country' => $info['country'],
                            'source' => 'brvm',
                        ];
                    }
                }
            }

        } catch (\Exception $e) {
            Log::debug('Parse BRVM.org failed: ' . $e->getMessage());
        }

        return $stocks;
    }

    /**
     * Récupérer les indices depuis RichBourse
     */
    private function fetchIndicesFromRichBourse(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($this->richbourseUrl . '/common/mouvements/indice/BRVM-PRINCIPAL');

            if (!$response->successful()) {
                return [];
            }

            return $this->parseRichBourseIndices($response->body());

        } catch (\Exception $e) {
            Log::debug('RichBourse indices fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parser les indices depuis RichBourse
     */
    private function parseRichBourseIndices(string $html): array
    {
        $indices = [];

        try {
            // Rechercher BRVM 10
            if (preg_match('/BRVM\s*10[^<]*<[^>]*>[\s\S]*?([\d\s,\.]+)[\s\S]*?([+-]?[\d,\.]+)\s*%/i', $html, $match)) {
                $indices[] = [
                    'name' => 'BRVM 10',
                    'value' => $this->parseNumber($match[1]),
                    'variation_percent' => $this->parseNumber($match[2]),
                    'source' => 'richbourse',
                ];
            }

            // Rechercher BRVM Composite
            if (preg_match('/BRVM\s*Composite[^<]*<[^>]*>[\s\S]*?([\d\s,\.]+)[\s\S]*?([+-]?[\d,\.]+)\s*%/i', $html, $match)) {
                $indices[] = [
                    'name' => 'BRVM Composite',
                    'value' => $this->parseNumber($match[1]),
                    'variation_percent' => $this->parseNumber($match[2]),
                    'source' => 'richbourse',
                ];
            }

            // Rechercher BRVM Prestige
            if (preg_match('/BRVM\s*Prestige[^<]*<[^>]*>[\s\S]*?([\d\s,\.]+)[\s\S]*?([+-]?[\d,\.]+)\s*%/i', $html, $match)) {
                $indices[] = [
                    'name' => 'BRVM Prestige',
                    'value' => $this->parseNumber($match[1]),
                    'variation_percent' => $this->parseNumber($match[2]),
                    'source' => 'richbourse',
                ];
            }

        } catch (\Exception $e) {
            Log::debug('Parse RichBourse indices failed: ' . $e->getMessage());
        }

        return $indices;
    }

    /**
     * Récupérer les indices depuis BRVM.org
     */
    private function fetchIndicesFromBRVM(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($this->brvm_url . '/fr/indices');

            if (!$response->successful()) {
                return [];
            }

            return $this->parseBRVMIndices($response->body());

        } catch (\Exception $e) {
            Log::debug('BRVM.org indices fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parser les indices depuis BRVM.org
     */
    private function parseBRVMIndices(string $html): array
    {
        $indices = [];

        try {
            // Pattern pour les indices BRVM
            $pattern = '/(BRVM\s*(?:10|Composite|Prestige))[^<]*<[^>]*>[\s\S]*?<td[^>]*>([\d\s,\.]+)<\/td>[\s\S]*?<td[^>]*>([+-]?[\d,\.]+)/i';

            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $indices[] = [
                        'name' => trim($match[1]),
                        'value' => $this->parseNumber($match[2]),
                        'variation_percent' => $this->parseNumber($match[3]),
                        'source' => 'brvm',
                    ];
                }
            }

        } catch (\Exception $e) {
            Log::debug('Parse BRVM.org indices failed: ' . $e->getMessage());
        }

        return $indices;
    }

    /**
     * Calculer les indices depuis les stocks (fallback)
     */
    private function calculateIndicesFromStocks(): array
    {
        $stocks = Stock::where('is_active', true)->get();

        if ($stocks->isEmpty()) {
            return $this->getDefaultIndices();
        }

        // Top 10 par capitalisation pour BRVM 10
        $top10 = $stocks->sortByDesc('market_cap')->take(10);
        $avgVariation10 = $top10->avg('variation_percent') ?? 0;
        $totalValue10 = $top10->sum('current_price');

        // Tous les stocks pour BRVM Composite
        $avgVariationAll = $stocks->avg('variation_percent') ?? 0;
        $totalValueAll = $stocks->sum('current_price');

        return [
            [
                'name' => 'BRVM 10',
                'value' => round($totalValue10, 2),
                'variation_percent' => round($avgVariation10, 2),
                'source' => 'calculated',
            ],
            [
                'name' => 'BRVM Composite',
                'value' => round($totalValueAll, 2),
                'variation_percent' => round($avgVariationAll, 2),
                'source' => 'calculated',
            ],
        ];
    }

    /**
     * Indices par défaut
     */
    private function getDefaultIndices(): array
    {
        return [
            [
                'name' => 'BRVM 10',
                'value' => 164.52,
                'variation_percent' => 0.45,
                'source' => 'default',
            ],
            [
                'name' => 'BRVM Composite',
                'value' => 215.78,
                'variation_percent' => 0.32,
                'source' => 'default',
            ],
        ];
    }

    /**
     * Synchroniser avec la base de données
     */
    private function syncStocksWithDatabase(array $stocks): void
    {
        try {
            foreach ($stocks as $stockData) {
                Stock::updateOrCreate(
                    ['symbol' => $stockData['symbol']],
                    [
                        'company_name' => $stockData['company_name'],
                        'current_price' => $stockData['current_price'],
                        'previous_price' => $stockData['previous_price'],
                        'variation_percent' => $stockData['variation_percent'],
                        'volume' => $stockData['volume'] ?? 0,
                        'sector' => $stockData['sector'] ?? null,
                        'is_active' => true,
                        'last_updated' => now(),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Error syncing BRVM stocks with database: ' . $e->getMessage());
        }
    }

    /**
     * Récupérer depuis la base de données (fallback)
     */
    private function getStocksFromDatabase(): array
    {
        $dbStocks = Stock::where('is_active', true)->orderBy('symbol')->get();

        if ($dbStocks->isEmpty()) {
            return $this->getDefaultStocks();
        }

        return $dbStocks->map(function ($stock) {
            return [
                'symbol' => $stock->symbol,
                'company_name' => $stock->company_name,
                'current_price' => $stock->current_price,
                'previous_price' => $stock->previous_price,
                'variation_percent' => $stock->variation_percent,
                'volume' => $stock->volume,
                'sector' => $stock->sector,
                'source' => 'database',
            ];
        })->toArray();
    }

    /**
     * Données par défaut des actions BRVM
     */
    private function getDefaultStocks(): array
    {
        $today = now();
        $dayHash = (int)$today->format('d');
        $seedOffset = ($dayHash % 5) - 2;

        return [
            [
                'symbol' => 'SNTS',
                'company_name' => 'Sonatel',
                'current_price' => 15200 + ($seedOffset * 100),
                'previous_price' => 15200,
                'variation_percent' => round($seedOffset * 0.66, 2),
                'volume' => 12500,
                'sector' => 'Télécommunications',
                'source' => 'default',
            ],
            [
                'symbol' => 'ORAC',
                'company_name' => 'Orange Côte d\'Ivoire',
                'current_price' => 12500 + ($seedOffset * 80),
                'previous_price' => 12500,
                'variation_percent' => round($seedOffset * 0.64, 2),
                'volume' => 8900,
                'sector' => 'Télécommunications',
                'source' => 'default',
            ],
            [
                'symbol' => 'SGBC',
                'company_name' => 'Société Générale CI',
                'current_price' => 11800 + ($seedOffset * 90),
                'previous_price' => 11800,
                'variation_percent' => round($seedOffset * 0.76, 2),
                'volume' => 5600,
                'sector' => 'Finance',
                'source' => 'default',
            ],
            [
                'symbol' => 'BOAB',
                'company_name' => 'BOA Bénin',
                'current_price' => 5950 + ($seedOffset * 40),
                'previous_price' => 5950,
                'variation_percent' => round($seedOffset * 0.67, 2),
                'volume' => 3200,
                'sector' => 'Finance',
                'source' => 'default',
            ],
            [
                'symbol' => 'ETIT',
                'company_name' => 'Ecobank Transnational Inc.',
                'current_price' => 18 + ($seedOffset * 0.5),
                'previous_price' => 18,
                'variation_percent' => round($seedOffset * 2.78, 2),
                'volume' => 45000,
                'sector' => 'Finance',
                'source' => 'default',
            ],
            [
                'symbol' => 'PALC',
                'company_name' => 'Palm CI',
                'current_price' => 6200 + ($seedOffset * 50),
                'previous_price' => 6200,
                'variation_percent' => round($seedOffset * 0.81, 2),
                'volume' => 2100,
                'sector' => 'Agriculture',
                'source' => 'default',
            ],
            [
                'symbol' => 'SLBC',
                'company_name' => 'Solibra',
                'current_price' => 89000 + ($seedOffset * 500),
                'previous_price' => 89000,
                'variation_percent' => round($seedOffset * 0.56, 2),
                'volume' => 450,
                'sector' => 'Industrie',
                'source' => 'default',
            ],
            [
                'symbol' => 'ONTBF',
                'company_name' => 'Onatel Burkina Faso',
                'current_price' => 3800 + ($seedOffset * 30),
                'previous_price' => 3800,
                'variation_percent' => round($seedOffset * 0.79, 2),
                'volume' => 1800,
                'sector' => 'Télécommunications',
                'source' => 'default',
            ],
            [
                'symbol' => 'CBIBF',
                'company_name' => 'Coris Bank International',
                'current_price' => 9500 + ($seedOffset * 70),
                'previous_price' => 9500,
                'variation_percent' => round($seedOffset * 0.74, 2),
                'volume' => 2800,
                'sector' => 'Finance',
                'source' => 'default',
            ],
            [
                'symbol' => 'CIEC',
                'company_name' => 'CIE',
                'current_price' => 2100 + ($seedOffset * 20),
                'previous_price' => 2100,
                'variation_percent' => round($seedOffset * 0.95, 2),
                'volume' => 4500,
                'sector' => 'Services Publics',
                'source' => 'default',
            ],
        ];
    }

    /**
     * Parser un nombre
     */
    private function parseNumber(string $value): float
    {
        $value = trim($value);
        $value = str_replace([' ', "\u{00A0}", "\xc2\xa0"], '', $value);
        $value = str_replace(',', '.', $value);
        $value = preg_replace('/[^\d.\-]/', '', $value);
        return (float) $value;
    }

    /**
     * Rafraîchir les données
     */
    public function refreshData(): void
    {
        Cache::forget('brvm_stocks');
        Cache::forget('brvm_indices');
    }

    /**
     * Vérifier si le service est fonctionnel
     */
    public function isConfigured(): bool
    {
        return true; // Toujours configuré car utilise le scraping gratuit
    }

    /**
     * Récupérer un stock spécifique
     */
    public function getStock(string $symbol): ?array
    {
        $stocks = $this->getStocks();
        
        foreach ($stocks as $stock) {
            if ($stock['symbol'] === strtoupper($symbol)) {
                return $stock;
            }
        }

        return null;
    }
}
