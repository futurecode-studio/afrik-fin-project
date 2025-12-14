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
     * Liste complète des symboles BRVM avec leurs informations et capitalisations estimées (en millions FCFA)
     */
    private $brvmSymbols = [
        // Secteur Finance
        'BOAB' => ['name' => 'BOA Bénin', 'sector' => 'Finance', 'country' => 'Bénin', 'market_cap' => 185000],
        'BOABF' => ['name' => 'BOA Burkina Faso', 'sector' => 'Finance', 'country' => 'Burkina Faso', 'market_cap' => 142000],
        'BOAC' => ['name' => 'BOA Côte d\'Ivoire', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire', 'market_cap' => 198000],
        'BOAM' => ['name' => 'BOA Mali', 'sector' => 'Finance', 'country' => 'Mali', 'market_cap' => 95000],
        'BOAN' => ['name' => 'BOA Niger', 'sector' => 'Finance', 'country' => 'Niger', 'market_cap' => 78000],
        'BOAS' => ['name' => 'BOA Sénégal', 'sector' => 'Finance', 'country' => 'Sénégal', 'market_cap' => 165000],
        'CBIBF' => ['name' => 'Coris Bank International', 'sector' => 'Finance', 'country' => 'Burkina Faso', 'market_cap' => 245000],
        'ECOC' => ['name' => 'Ecobank Côte d\'Ivoire', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire', 'market_cap' => 125000],
        'ETIT' => ['name' => 'Ecobank Transnational Inc.', 'sector' => 'Finance', 'country' => 'Togo', 'market_cap' => 890000],
        'NSBC' => ['name' => 'NSIA Banque Côte d\'Ivoire', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire', 'market_cap' => 156000],
        'ORGT' => ['name' => 'Oragroup', 'sector' => 'Finance', 'country' => 'Togo', 'market_cap' => 98000],
        'SAFC' => ['name' => 'SAFCA', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire', 'market_cap' => 45000],
        'SGBC' => ['name' => 'Société Générale CI', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire', 'market_cap' => 425000],
        'SIBC' => ['name' => 'SIB Côte d\'Ivoire', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire', 'market_cap' => 178000],
        'BICC' => ['name' => 'BICICI', 'sector' => 'Finance', 'country' => 'Côte d\'Ivoire', 'market_cap' => 210000],

        // Secteur Télécommunications
        'SNTS' => ['name' => 'Sonatel', 'sector' => 'Télécommunications', 'country' => 'Sénégal', 'market_cap' => 2850000],
        'ONTBF' => ['name' => 'Onatel Burkina Faso', 'sector' => 'Télécommunications', 'country' => 'Burkina Faso', 'market_cap' => 385000],
        'ORAC' => ['name' => 'Orange Côte d\'Ivoire', 'sector' => 'Télécommunications', 'country' => 'Côte d\'Ivoire', 'market_cap' => 1250000],

        // Secteur Industrie
        'CABC' => ['name' => 'Sicable', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 32000],
        'FTSC' => ['name' => 'Filtisac', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 28000],
        'NEIC' => ['name' => 'NEI-CEDA', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 18500],
        'SEMC' => ['name' => 'Crown SIEM', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 42000],
        'SLBC' => ['name' => 'Solibra', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 485000],
        'SMBC' => ['name' => 'SMB', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 25000],
        'STBC' => ['name' => 'SITAB', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 35000],
        'TTLC' => ['name' => 'Total CI', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 320000],
        'TTLS' => ['name' => 'Total Sénégal', 'sector' => 'Industrie', 'country' => 'Sénégal', 'market_cap' => 185000],
        'UNLC' => ['name' => 'Unilever CI', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 145000],
        'UNXC' => ['name' => 'Uniwax', 'sector' => 'Industrie', 'country' => 'Côte d\'Ivoire', 'market_cap' => 38000],

        // Secteur Agriculture
        'PALC' => ['name' => 'Palm CI', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire', 'market_cap' => 285000],
        'PRSC' => ['name' => 'Sucrivoire', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire', 'market_cap' => 65000],
        'SCRC' => ['name' => 'SUCAF CI', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire', 'market_cap' => 48000],
        'SICC' => ['name' => 'SICOR', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire', 'market_cap' => 52000],
        'SOGC' => ['name' => 'SOGB', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire', 'market_cap' => 125000],
        'SPHC' => ['name' => 'SAPH', 'sector' => 'Agriculture', 'country' => 'Côte d\'Ivoire', 'market_cap' => 195000],

        // Secteur Distribution
        'APTS' => ['name' => 'Vivo Energy CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire', 'market_cap' => 165000],
        'BNBC' => ['name' => 'Bernabé CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire', 'market_cap' => 28000],
        'CFAC' => ['name' => 'CFAO Motors CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire', 'market_cap' => 85000],
        'SHEC' => ['name' => 'Vivo Energy CI', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire', 'market_cap' => 165000],
        'TTRC' => ['name' => 'Trituraf', 'sector' => 'Distribution', 'country' => 'Côte d\'Ivoire', 'market_cap' => 22000],

        // Secteur Services Publics
        'CIEC' => ['name' => 'CIE', 'sector' => 'Services Publics', 'country' => 'Côte d\'Ivoire', 'market_cap' => 245000],
        'SDCC' => ['name' => 'SODECI', 'sector' => 'Services Publics', 'country' => 'Côte d\'Ivoire', 'market_cap' => 185000],
        'SDSC' => ['name' => 'SDS', 'sector' => 'Services Publics', 'country' => 'Côte d\'Ivoire', 'market_cap' => 42000],

        // Secteur Transport
        'SVOC' => ['name' => 'Movis', 'sector' => 'Transport', 'country' => 'Côte d\'Ivoire', 'market_cap' => 35000],
        'STAC' => ['name' => 'SETAO', 'sector' => 'Transport', 'country' => 'Côte d\'Ivoire', 'market_cap' => 28000],
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

                // 3. Fallback: données de la base de données ou par défaut
                Log::warning('BRVM Stocks: All scraping sources failed, trying database fallback');
                $dbStocks = $this->getStocksFromDatabase();
                
                // Si la base de données a moins de 40 stocks, utiliser les données par défaut
                if (count($dbStocks) < 40) {
                    Log::warning('BRVM Stocks: Database has only ' . count($dbStocks) . ' stocks, using default data');
                    return $this->getDefaultStocks();
                }
                
                return $dbStocks;

            } catch (\Exception $e) {
                Log::error('BRVM Scraper Exception: ' . $e->getMessage());
                $dbStocks = $this->getStocksFromDatabase();
                
                // Si la base de données a moins de 40 stocks, utiliser les données par défaut
                if (count($dbStocks) < 40) {
                    return $this->getDefaultStocks();
                }
                
                return $dbStocks;
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
                            'market_cap' => $info['market_cap'] ?? 0,
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
                                'market_cap' => $info['market_cap'] ?? 0,
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
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Connection' => 'keep-alive',
                    'Cache-Control' => 'max-age=0',
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
     * Parser la page BRVM.org - Format réel du tableau
     * Structure: <tr><td>SYMBOL</td><td>NOM</td><td>VOLUME</td><td>COURS</td><td>OUVERTURE</td><td>CLOTURE</td><td>VARIATION</td></tr>
     */
    private function parseBRVMPage(string $html): array
    {
        $stocks = [];

        try {
            // Pattern pour extraire les lignes du tableau principal des cours BRVM
            // Format: <tr><td>SNTS</td><td>SONATEL SENEGAL</td><td class="text-right">1 119</td><td class="text-right">25 600</td>...
            $pattern = '/<tr>\s*<td>([A-Z]{3,5})<\/td>\s*<td>([^<]+)<\/td>\s*<td[^>]*>([\d\s]+)<\/td>\s*<td[^>]*>([\d\s]+)<\/td>\s*<td[^>]*>([\d\s]+)<\/td>\s*<td[^>]*>([\d\s]+)<\/td>\s*<td[^>]*>.*?([+-]?[\d,\.]+).*?<\/td>/is';

            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $symbol = strtoupper(trim($match[1]));
                    $companyName = trim($match[2]);
                    $volume = $this->parseNumber($match[3]);
                    $currentPrice = $this->parseNumber($match[4]);
                    $openPrice = $this->parseNumber($match[5]);
                    $closePrice = $this->parseNumber($match[6]);
                    $variation = $this->parseNumber($match[7]);

                    if ($currentPrice > 0) {
                        $info = $this->brvmSymbols[$symbol] ?? [
                            'name' => $companyName,
                            'sector' => 'Autre',
                            'country' => 'UEMOA',
                            'market_cap' => 0
                        ];

                        // Utiliser le nom du site si disponible, sinon celui de notre référence
                        $displayName = !empty($companyName) ? $this->formatCompanyName($companyName) : $info['name'];

                        $stocks[] = [
                            'symbol' => $symbol,
                            'company_name' => $displayName,
                            'current_price' => $currentPrice,
                            'previous_price' => $closePrice > 0 ? $closePrice : $currentPrice,
                            'variation_percent' => round($variation, 2),
                            'volume' => (int) $volume,
                            'market_cap' => $info['market_cap'] ?? 0,
                            'sector' => $info['sector'],
                            'country' => $info['country'] ?? 'UEMOA',
                            'source' => 'brvm',
                        ];
                    }
                }
            }

            // Si le premier pattern ne fonctionne pas, essayer un pattern alternatif
            if (empty($stocks)) {
                $stocks = $this->parseBRVMAlternative($html);
            }

            Log::info('BRVM.org parsing: ' . count($stocks) . ' stocks found');

        } catch (\Exception $e) {
            Log::debug('Parse BRVM.org failed: ' . $e->getMessage());
        }

        return $stocks;
    }

    /**
     * Parser alternatif pour BRVM.org
     */
    private function parseBRVMAlternative(string $html): array
    {
        $stocks = [];

        try {
            // Pattern plus flexible pour capturer les données
            // Recherche: <td>SYMBOL</td><td>NOM</td> suivi de plusieurs <td> avec des chiffres
            $pattern = '/<td>([A-Z]{3,5})<\/td>\s*<td>([A-Z\s\'\-\.]+)<\/td>.*?<td[^>]*>([\d\s]+)<\/td>.*?<td[^>]*>([\d\s]+)<\/td>.*?<td[^>]*>([\d\s]+)<\/td>.*?<td[^>]*>([\d\s]+)<\/td>.*?<td[^>]*>.*?<span[^>]*>([+-]?[\d,\.]+)<\/span>/is';

            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $symbol = strtoupper(trim($match[1]));
                    $companyName = trim($match[2]);
                    $volume = $this->parseNumber($match[3]);
                    $currentPrice = $this->parseNumber($match[4]);
                    $openPrice = $this->parseNumber($match[5]);
                    $closePrice = $this->parseNumber($match[6]);
                    $variation = $this->parseNumber($match[7]);

                    if ($currentPrice > 0 && strlen($symbol) >= 3 && strlen($symbol) <= 5) {
                        $info = $this->brvmSymbols[$symbol] ?? [
                            'name' => $companyName,
                            'sector' => 'Autre',
                            'country' => 'UEMOA',
                            'market_cap' => 0
                        ];

                        $displayName = !empty($companyName) ? $this->formatCompanyName($companyName) : $info['name'];

                        $stocks[] = [
                            'symbol' => $symbol,
                            'company_name' => $displayName,
                            'current_price' => $currentPrice,
                            'previous_price' => $closePrice > 0 ? $closePrice : $currentPrice,
                            'variation_percent' => round($variation, 2),
                            'volume' => (int) $volume,
                            'market_cap' => $info['market_cap'] ?? 0,
                            'sector' => $info['sector'],
                            'country' => $info['country'] ?? 'UEMOA',
                            'source' => 'brvm',
                        ];
                    }
                }
            }

        } catch (\Exception $e) {
            Log::debug('Parse BRVM.org alternative failed: ' . $e->getMessage());
        }

        return $stocks;
    }

    /**
     * Formater le nom de l'entreprise (capitalisation correcte)
     */
    private function formatCompanyName(string $name): string
    {
        // Nettoyer et formater le nom
        $name = trim($name);
        $name = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
        
        // Corrections spécifiques
        $replacements = [
            "Cote D'ivoire" => "Côte d'Ivoire",
            "D'ivoire" => "d'Ivoire",
            "Cote D'Ivoire" => "Côte d'Ivoire",
            "Senegal" => "Sénégal",
            "Benin" => "Bénin",
            "Togo" => "Togo",
            "Mali" => "Mali",
            "Niger" => "Niger",
            "Burkina" => "Burkina Faso",
        ];

        foreach ($replacements as $search => $replace) {
            $name = str_ireplace($search, $replace, $name);
        }

        return $name;
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
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
                ])
                ->get($this->brvm_url . '/fr/indices');

            if (!$response->successful()) {
                Log::debug('BRVM.org indices: HTTP ' . $response->status());
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
     * Format: <tr><td>BRVM - COMPOSITE</td><td class="text-right">347,37</td><td class="text-right">347,81</td><td>...<span>0,13</span>...</td>
     */
    private function parseBRVMIndices(string $html): array
    {
        $indices = [];

        try {
            // Pattern pour les indices principaux BRVM
            // Format: <td>BRVM - COMPOSITE</td><td>VALEUR_PRECEDENTE</td><td>VALEUR_ACTUELLE</td><td>...<span>VARIATION</span>...</td>
            $pattern = '/<td>(BRVM\s*-?\s*(?:COMPOSITE|PRINCIPAL|PRESTIGE|30|C|PRES))<\/td>\s*<td[^>]*>([\d\s,\.]+)<\/td>\s*<td[^>]*>([\d\s,\.]+)<\/td>\s*<td[^>]*>.*?<span[^>]*>([+-]?[\d,\.]+)<\/span>/is';

            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $name = $this->formatIndexName(trim($match[1]));
                    $previousValue = $this->parseNumber($match[2]);
                    $currentValue = $this->parseNumber($match[3]);
                    $variation = $this->parseNumber($match[4]);

                    // Éviter les doublons
                    $exists = false;
                    foreach ($indices as $idx) {
                        if ($idx['name'] === $name) {
                            $exists = true;
                            break;
                        }
                    }

                    if (!$exists && $currentValue > 0) {
                        $indices[] = [
                            'name' => $name,
                            'value' => $currentValue,
                            'previous_value' => $previousValue,
                            'variation_percent' => round($variation, 2),
                            'source' => 'brvm',
                        ];
                    }
                }
            }

            // Pattern alternatif pour les indices en haut de page
            if (empty($indices)) {
                $pattern2 = '/<td>(BRVM-[A-Z0-9]+)<\/td>\s*<td[^>]*>([\d\s,\.]+)<\/td>\s*<td[^>]*>([+-]?[\d,\.]+)%?\s*<span/is';
                
                if (preg_match_all($pattern2, $html, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $name = $this->formatIndexName(trim($match[1]));
                        $value = $this->parseNumber($match[2]);
                        $variation = $this->parseNumber($match[3]);

                        if ($value > 0) {
                            $indices[] = [
                                'name' => $name,
                                'value' => $value,
                                'previous_value' => $value / (1 + ($variation / 100)),
                                'variation_percent' => round($variation, 2),
                                'source' => 'brvm',
                            ];
                        }
                    }
                }
            }

            Log::info('BRVM.org indices parsing: ' . count($indices) . ' indices found');

        } catch (\Exception $e) {
            Log::debug('Parse BRVM.org indices failed: ' . $e->getMessage());
        }

        return $indices;
    }

    /**
     * Formater le nom de l'indice
     */
    private function formatIndexName(string $name): string
    {
        $name = strtoupper(trim($name));
        
        $replacements = [
            'BRVM-C' => 'BRVM Composite',
            'BRVM - COMPOSITE' => 'BRVM Composite',
            'BRVM-30' => 'BRVM 30',
            'BRVM - PRINCIPAL' => 'BRVM Principal',
            'BRVM-PRES' => 'BRVM Prestige',
            'BRVM - PRESTIGE' => 'BRVM Prestige',
        ];

        return $replacements[$name] ?? $name;
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

        $brvmSymbols = $this->brvmSymbols;

        return $dbStocks->map(function ($stock) use ($brvmSymbols) {
            // Enrichir avec les données de référence BRVM
            $info = $brvmSymbols[$stock->symbol] ?? null;
            
            return [
                'symbol' => $stock->symbol,
                'company_name' => $stock->company_name,
                'current_price' => $stock->current_price,
                'previous_price' => $stock->previous_price,
                'variation_percent' => $stock->variation_percent,
                'volume' => $stock->volume,
                'market_cap' => $stock->market_cap ?? ($info['market_cap'] ?? 0),
                'sector' => $stock->sector ?? ($info['sector'] ?? 'Autre'),
                'source' => 'database',
            ];
        })->toArray();
    }

    /**
     * Données par défaut des actions BRVM (47 symboles)
     * Génère automatiquement à partir de la liste brvmSymbols
     */
    private function getDefaultStocks(): array
    {
        $today = now();
        $dayHash = (int)$today->format('d');
        
        // Utiliser un seed basé sur la date pour des variations cohérentes
        mt_srand($dayHash);
        
        $stocks = [];
        
        foreach ($this->brvmSymbols as $symbol => $info) {
            // Générer une variation réaliste basée sur le symbole
            $symbolHash = crc32($symbol) % 100;
            $variation = (mt_rand(-200, 200) / 100); // -2% à +2%
            
            // Prix de base estimé selon le secteur et la capitalisation
            $basePrice = $this->estimateBasePrice($info);
            $priceVariation = $basePrice * ($variation / 100);
            $currentPrice = round($basePrice + $priceVariation);
            
            // Volume estimé
            $volume = mt_rand(100, 50000);
            
            $stocks[] = [
                'symbol' => $symbol,
                'company_name' => $info['name'],
                'current_price' => $currentPrice,
                'previous_price' => $basePrice,
                'variation_percent' => round($variation, 2),
                'volume' => $volume,
                'market_cap' => $info['market_cap'] ?? 0,
                'sector' => $info['sector'] ?? 'Autre',
                'country' => $info['country'] ?? 'UEMOA',
                'source' => 'default',
            ];
        }
        
        // Réinitialiser le générateur aléatoire
        mt_srand();
        
        // Trier par symbole
        usort($stocks, fn($a, $b) => strcmp($a['symbol'], $b['symbol']));
        
        return $stocks;
    }

    /**
     * Estimer le prix de base d'une action selon sa capitalisation
     */
    private function estimateBasePrice(array $info): int
    {
        $marketCap = $info['market_cap'] ?? 0;
        
        // Estimation basée sur la capitalisation boursière
        if ($marketCap >= 1000000) {
            return mt_rand(15000, 45000); // Grandes caps
        } elseif ($marketCap >= 500000) {
            return mt_rand(8000, 20000); // Moyennes caps
        } elseif ($marketCap >= 100000) {
            return mt_rand(2000, 10000); // Petites caps
        } else {
            return mt_rand(100, 5000); // Micro caps
        }
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
