<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service pour récupérer les Valeurs Liquidatives des FCP/OPCVM
 * Sources: Sikafinance, BRVM, RichBourse (données africaines UEMOA)
 */
class MutualFundsApiService
{
    private $cacheDuration;
    private $timeout;
    private $sikafinanceBaseUrl = 'https://www.sikafinance.com';
    private $richbourseBaseUrl = 'https://www.richbourse.com';

    public function __construct()
    {
        $this->cacheDuration = config('services.mutual_funds.cache_duration', 3600);
        $this->timeout = config('services.mutual_funds.timeout', 30);
    }

    /**
     * Récupérer les fonds communs de placement (VL/FCP) en temps réel
     * Utilise des sources de données africaines fiables
     */
    public function getMutualFunds(): array
    {
        return Cache::remember('mutual_funds_data', $this->cacheDuration, function () {
            try {
                $useDefaultFallback = filter_var(env('MUTUAL_FUNDS_USE_DEFAULT_FALLBACK', true), FILTER_VALIDATE_BOOLEAN);
                $useMockData = filter_var(env('MUTUAL_FUNDS_USE_MOCK', false), FILTER_VALIDATE_BOOLEAN);

                // 1. Essayer de scraper Sikafinance (source principale pour OPCVM UEMOA)
                $funds = $this->fetchFromSikafinance();
                if (!empty($funds)) {
                    Log::info('Mutual Funds: Sikafinance data loaded successfully (' . count($funds) . ' funds)');
                    return $funds;
                }

                // 2. Essayer RichBourse (alternative pour données BRVM)
                $funds = $this->fetchFromRichBourse();
                if (!empty($funds)) {
                    Log::info('Mutual Funds: RichBourse data loaded successfully (' . count($funds) . ' funds)');
                    return $funds;
                }

                // 3. Essayer les données BRVM directement
                $funds = $this->fetchFromBRVMDirect();
                if (!empty($funds)) {
                    Log::info('Mutual Funds: BRVM direct data loaded successfully');
                    return $funds;
                }

                // 4. Si mock mode forcé, utiliser les données mock
                if ($useMockData) {
                    Log::warning('Mutual Funds: Using mock data (MUTUAL_FUNDS_USE_MOCK=true)');
                    return $this->getMockMutualFunds();
                }

                // 5. Fallback: données réalistes des FCP africains
                if ($useDefaultFallback) {
                    Log::warning('Mutual Funds: Using default African FCP data - all sources failed');
                    return $this->getDefaultAfricanFunds();
                }

                Log::warning('Mutual Funds: All sources failed; returning empty dataset');
                return [];

            } catch (\Exception $e) {
                Log::error('Mutual Funds API Exception: ' . $e->getMessage());
                if (filter_var(env('MUTUAL_FUNDS_USE_MOCK', false), FILTER_VALIDATE_BOOLEAN)) {
                    return $this->getMockMutualFunds();
                }
                if (filter_var(env('MUTUAL_FUNDS_USE_DEFAULT_FALLBACK', true), FILTER_VALIDATE_BOOLEAN)) {
                    return $this->getDefaultAfricanFunds();
                }
                return [];
            }
        });
    }

    /**
     * Récupérer les données depuis Sikafinance (OPCVM UEMOA)
     * Source principale pour les FCP africains
     */
    private function fetchFromSikafinance(): array
    {
        try {
            $funds = [];
            $opcvmList = $this->getAfricanOPCVMList();

            foreach ($opcvmList as $opcvm) {
                try {
                    $response = Http::timeout($this->timeout)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
                        ])
                        ->get($this->sikafinanceBaseUrl . $opcvm['url']);

                    if ($response->successful()) {
                        $fund = $this->parseSikafinancePage($response->body(), $opcvm);
                        if ($fund) {
                            $funds[] = $fund;
                        }
                    }
                } catch (\Exception $e) {
                    Log::debug("Sikafinance fetch failed for {$opcvm['name']}: " . $e->getMessage());
                    continue;
                }
                
                // Délai pour éviter de surcharger le serveur
                usleep(150000); // 150ms
            }

            return $funds;
        } catch (\Exception $e) {
            Log::warning('Sikafinance scraping failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parser une page Sikafinance pour extraire les données VL
     */
    private function parseSikafinancePage(string $html, array $opcvm): ?array
    {
        try {
            $navValue = null;
            $variation = 0;
            $date = now()->format('Y-m-d');

            // Rechercher la valeur liquidative
            if (preg_match('/(?:VL|Valeur\s*liquidative|NAV)[^\d]*(\d[\d\s,\.]+)/i', $html, $matches)) {
                $navValue = $this->parseNumber($matches[1]);
            }

            // Pattern alternatif pour les tableaux
            if (!$navValue && preg_match('/<td[^>]*class="[^"]*vl[^"]*"[^>]*>(\d[\d\s,\.]+)/i', $html, $matches)) {
                $navValue = $this->parseNumber($matches[1]);
            }

            // Rechercher la variation/performance
            if (preg_match('/(?:variation|performance|rendement)[^\d-]*([+-]?\d[\d,\.]*)\s*%/i', $html, $matches)) {
                $variation = $this->parseNumber($matches[1]);
            }

            // Rechercher la date
            if (preg_match('/(\d{2})[\/\-](\d{2})[\/\-](\d{4})/', $html, $matches)) {
                $date = "{$matches[3]}-{$matches[2]}-{$matches[1]}";
            }

            if ($navValue && $navValue > 0) {
                return [
                    'id' => $opcvm['id'],
                    'name' => $opcvm['name'],
                    'company' => $opcvm['company'],
                    'nav_value' => $this->formatCurrency($navValue, 'FCFA'),
                    'nav_numeric' => $navValue,
                    'variation' => $this->formatVariation(0, $variation),
                    'variation_percentage' => round($variation, 2),
                    'currency' => 'FCFA',
                    'date' => $date,
                    'category' => $opcvm['category'],
                    'isin' => $opcvm['isin'] ?? null,
                    'source' => 'sikafinance',
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::debug("Parse Sikafinance page failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer les données depuis RichBourse
     */
    private function fetchFromRichBourse(): array
    {
        try {
            $funds = [];
            
            // Essayer de récupérer les indices BRVM depuis RichBourse
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($this->richbourseBaseUrl . '/common/mouvements/indice/BRVM-PRINCIPAL');

            if ($response->successful()) {
                $funds = $this->parseRichBoursePage($response->body());
            }

            return $funds;
        } catch (\Exception $e) {
            Log::warning('RichBourse fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parser la page RichBourse
     */
    private function parseRichBoursePage(string $html): array
    {
        $funds = [];
        
        try {
            // Rechercher les données d'indices dans le HTML
            if (preg_match_all('/<tr[^>]*>.*?<td[^>]*>([^<]+)<\/td>.*?<td[^>]*>(\d[\d\s,\.]+)<\/td>.*?<td[^>]*>([+-]?\d[\d,\.]*)\s*%?<\/td>/is', $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $name = trim(strip_tags($match[1]));
                    $value = $this->parseNumber($match[2]);
                    $variation = $this->parseNumber($match[3]);

                    if ($value > 0 && !empty($name)) {
                        $funds[] = [
                            'id' => 'RB-' . preg_replace('/[^A-Z0-9]/i', '', $name),
                            'name' => $name,
                            'company' => 'BRVM (RichBourse)',
                            'nav_value' => $this->formatCurrency($value, 'FCFA'),
                            'nav_numeric' => $value,
                            'variation' => $this->formatVariation(0, $variation),
                            'variation_percentage' => round($variation, 2),
                            'currency' => 'FCFA',
                            'date' => now()->format('Y-m-d'),
                            'category' => $this->categorizeIndex($name),
                            'source' => 'richbourse',
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::debug('Parse RichBourse page failed: ' . $e->getMessage());
        }

        return $funds;
    }

    /**
     * Récupérer les données directement depuis BRVM
     */
    private function fetchFromBRVMDirect(): array
    {
        try {
            // La BRVM n'a pas d'API publique, on utilise le scraping
            $response = Http::timeout($this->timeout)
                ->withOptions(['verify' => false]) // Certificat SSL parfois problématique
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get('https://www.brvm.org/fr/cours-actions/0/status/200/stock_order/percent_change/order/desc');

            if ($response->successful()) {
                return $this->parseBRVMDirectPage($response->body());
            }
        } catch (\Exception $e) {
            Log::debug('BRVM direct fetch failed: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Parser la page BRVM directement
     */
    private function parseBRVMDirectPage(string $html): array
    {
        $funds = [];
        
        try {
            // Pattern pour extraire les données du tableau des cours
            if (preg_match_all('/<tr[^>]*class="[^"]*stock[^"]*"[^>]*>.*?<td[^>]*>([^<]+)<\/td>.*?<td[^>]*>(\d[\d\s,\.]+)<\/td>.*?<td[^>]*>([+-]?\d[\d,\.]*)/is', $html, $matches, PREG_SET_ORDER)) {
                foreach (array_slice($matches, 0, 20) as $match) { // Limiter à 20 résultats
                    $name = trim(strip_tags($match[1]));
                    $value = $this->parseNumber($match[2]);
                    $variation = $this->parseNumber($match[3]);

                    if ($value > 0 && !empty($name)) {
                        $funds[] = [
                            'id' => 'BRVM-' . preg_replace('/[^A-Z0-9]/i', '', $name),
                            'name' => $name,
                            'company' => 'BRVM (Côte d\'Ivoire)',
                            'nav_value' => $this->formatCurrency($value, 'FCFA'),
                            'nav_numeric' => $value,
                            'variation' => $this->formatVariation(0, $variation),
                            'variation_percentage' => round($variation, 2),
                            'currency' => 'FCFA',
                            'date' => now()->format('Y-m-d'),
                            'category' => 'Actions',
                            'source' => 'brvm',
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::debug('Parse BRVM direct page failed: ' . $e->getMessage());
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
     * Liste des OPCVM africains pour le scraping Sikafinance
     */
    private function getAfricanOPCVMList(): array
    {
        return [
            // FCP Actions - Côte d'Ivoire
            [
                'id' => 'FCP-SOGEVALOR',
                'name' => 'FCP SOGEVALOR',
                'url' => '/opcvm/fcp-sogevalor_CI0000001954',
                'company' => 'Société Générale CI',
                'category' => 'Actions',
                'isin' => 'CI0000001954',
            ],
            [
                'id' => 'FCP-SOGEDYNAMIQUE',
                'name' => 'FCP SOGEDYNAMIQUE',
                'url' => '/opcvm/fcp-sogedynamique_CI0000001970',
                'company' => 'Société Générale CI',
                'category' => 'Mixte',
                'isin' => 'CI0000001970',
            ],
            // FCP Actions - Sénégal
            [
                'id' => 'FCP-PLACEMENT-CROISSANCE',
                'name' => 'FCP PLACEMENT CROISSANCE',
                'url' => '/opcvm/fcp-placement-croissancce_SN0000000274',
                'company' => 'CGF Bourse (Sénégal)',
                'category' => 'Actions',
                'isin' => 'SN0000000274',
            ],
            [
                'id' => 'FCP-ACTION-PHARMACIE',
                'name' => 'FCP ACTION PHARMACIE',
                'url' => '/opcvm/fcp-action-pharmacie_SN0000000787',
                'company' => 'CGF Bourse (Sénégal)',
                'category' => 'Actions',
                'isin' => 'SN0000000787',
            ],
            // FCP Actions - Togo
            [
                'id' => 'FCP-OPTI-PLACEMENT',
                'name' => 'FCP-1 OPTI PLACEMENT',
                'url' => '/opcvm/fcp-1-opti-placement_TG0000000003',
                'company' => 'OPTI Gestion (Togo)',
                'category' => 'Actions',
                'isin' => 'TG0000000003',
            ],
            // FCP Actions - Bénin
            [
                'id' => 'FCP-AAM-EPARGNE-ACTION',
                'name' => 'FCP AAM EPARGNE ACTION',
                'url' => '/opcvm/fcp-aam-epargne-action_BJ0000000931',
                'company' => 'Africaine Asset Management (Bénin)',
                'category' => 'Actions',
                'isin' => 'BJ0000000931',
            ],
            [
                'id' => 'FCP-SOAGA-EPARGNE',
                'name' => 'FCP SOAGA EPARGNE ACTIONS',
                'url' => '/opcvm/fcp-soaga-epargne-actions_BJ0000001467',
                'company' => 'SOAGA (Bénin)',
                'category' => 'Actions',
                'isin' => 'BJ0000001467',
            ],
            // FCP Actions - Burkina Faso
            [
                'id' => 'FCP-CORIS-ACTION',
                'name' => 'FCP CORIS ACTION',
                'url' => '/opcvm/fcp-coris-action_BF0000000208',
                'company' => 'Coris Asset Management (Burkina)',
                'category' => 'Actions',
                'isin' => 'BF0000000208',
            ],
            // FCP Actions - Régional UEMOA
            [
                'id' => 'ATTIJARI-ACTIONS',
                'name' => 'ATTIJARI ACTIONS',
                'url' => '/opcvm/attijari-actions_AFRICA000011',
                'company' => 'Attijari Asset Management',
                'category' => 'Actions',
                'isin' => 'AFRICA000011',
            ],
            [
                'id' => 'FCP-PAM-ACTIONS',
                'name' => 'FCP PAM ACTIONS',
                'url' => '/opcvm/fcp-pam-actions_AFRICA000005',
                'company' => 'PAM Asset Management',
                'category' => 'Actions',
                'isin' => 'AFRICA000005',
            ],
            [
                'id' => 'FCP-BOA-ACTIONS',
                'name' => 'FCP BOA ACTIONS',
                'url' => '/opcvm/fcp-boa-actions_AFRICA000014',
                'company' => 'BOA Asset Management',
                'category' => 'Actions',
                'isin' => 'AFRICA000014',
            ],
            [
                'id' => 'FCP-ATLANTIQUE-ACTIONS',
                'name' => 'FCP ATLANTIQUE ACTIONS',
                'url' => '/opcvm/fcp-atlantique-actions_AFRICA000017',
                'company' => 'Atlantique Finance',
                'category' => 'Actions',
                'isin' => 'AFRICA000017',
            ],
            [
                'id' => 'FCP-ECOBANK-ACTIONS',
                'name' => 'FCP ECOBANK ACTIONS UEMOA',
                'url' => '/opcvm/fcp-ecobank-actions-uemoa_SIKA00000028',
                'company' => 'Ecobank Asset Management',
                'category' => 'Actions',
                'isin' => 'SIKA00000028',
            ],
            // FCP Mixtes et Obligations
            [
                'id' => 'FCP-AL-BARAKA',
                'name' => 'FCP AL BARAKA',
                'url' => '/opcvm/fcp-al-baraka_SIKA00000011',
                'company' => 'Al Baraka Finance',
                'category' => 'Mixte',
                'isin' => 'SIKA00000011',
            ],
            [
                'id' => 'FCP-BNDE-VALEURS',
                'name' => 'FCP BNDE VALEURS',
                'url' => '/opcvm/fcp-bnde-valeurs_SIKA00000013',
                'company' => 'BNDE (Sénégal)',
                'category' => 'Obligations',
                'isin' => 'SIKA00000013',
            ],
            [
                'id' => 'FCP-WALO',
                'name' => 'FCP WALO',
                'url' => '/opcvm/fcp-walo_SIKA00000023',
                'company' => 'Walo Asset Management',
                'category' => 'Obligations',
                'isin' => 'SIKA00000023',
            ],
            [
                'id' => 'FCP-VALORIS',
                'name' => 'FCP VALORIS',
                'url' => '/opcvm/fcp-valoris_SIKA00000003',
                'company' => 'Valoris Asset Management',
                'category' => 'Mixte',
                'isin' => 'SIKA00000003',
            ],
        ];
    }

    /**
     * Parser un nombre depuis une chaîne
     */
    private function parseNumber(string $value): float
    {
        $value = trim($value);
        $value = str_replace([' ', "\u{00A0}"], '', $value); // Espaces normaux et insécables
        $value = str_replace(',', '.', $value);
        $value = preg_replace('/[^\d.]/', '', $value);
        return (float) $value;
    }

    /**
     * Générer des données mock réalistes avec variations quotidiennes
     * Données de FCP africains pour environnements offline
     */
    public function getMockMutualFunds(): array
    {
        $today = now();
        $dayHash = (int)$today->format('d');
        $seedOffset = ($dayHash % 5) - 2; // -2 à +2

        return [
            [
                'id' => 'FCP-SOGEVALOR',
                'name' => 'FCP SOGEVALOR',
                'company' => 'Société Générale CI',
                'nav_numeric' => 15234.56 + ($seedOffset * 150),
                'nav_value' => $this->formatCurrency(15234.56 + ($seedOffset * 150), 'FCFA'),
                'variation' => $this->formatVariation($seedOffset * 150, $seedOffset * 0.98),
                'variation_percentage' => round($seedOffset * 0.98, 2),
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'mock',
            ],
            [
                'id' => 'FCP-PLACEMENT-CROISSANCE',
                'name' => 'FCP PLACEMENT CROISSANCE',
                'company' => 'CGF Bourse (Sénégal)',
                'nav_numeric' => 12890.45 + ($seedOffset * 120),
                'nav_value' => $this->formatCurrency(12890.45 + ($seedOffset * 120), 'FCFA'),
                'variation' => $this->formatVariation($seedOffset * 120, $seedOffset * 0.93),
                'variation_percentage' => round($seedOffset * 0.93, 2),
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'mock',
            ],
            [
                'id' => 'ATTIJARI-ACTIONS',
                'name' => 'ATTIJARI ACTIONS',
                'company' => 'Attijari Asset Management',
                'nav_numeric' => 18567.89 + ($seedOffset * 180),
                'nav_value' => $this->formatCurrency(18567.89 + ($seedOffset * 180), 'FCFA'),
                'variation' => $this->formatVariation($seedOffset * 180, $seedOffset * 0.97),
                'variation_percentage' => round($seedOffset * 0.97, 2),
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'mock',
            ],
            [
                'id' => 'FCP-BOA-ACTIONS',
                'name' => 'FCP BOA ACTIONS',
                'company' => 'BOA Asset Management',
                'nav_numeric' => 14234.12 + ($seedOffset * 140),
                'nav_value' => $this->formatCurrency(14234.12 + ($seedOffset * 140), 'FCFA'),
                'variation' => $this->formatVariation($seedOffset * 140, $seedOffset * 0.98),
                'variation_percentage' => round($seedOffset * 0.98, 2),
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'mock',
            ],
            [
                'id' => 'FCP-CORIS-ACTION',
                'name' => 'FCP CORIS ACTION',
                'company' => 'Coris Asset Management (Burkina)',
                'nav_numeric' => 11456.78 + ($seedOffset * 110),
                'nav_value' => $this->formatCurrency(11456.78 + ($seedOffset * 110), 'FCFA'),
                'variation' => $this->formatVariation($seedOffset * 110, $seedOffset * 0.96),
                'variation_percentage' => round($seedOffset * 0.96, 2),
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'mock',
            ],
            [
                'id' => 'FCP-BNDE-VALEURS',
                'name' => 'FCP BNDE VALEURS',
                'company' => 'BNDE (Sénégal)',
                'nav_numeric' => 10234.56 + ($seedOffset * 50),
                'nav_value' => $this->formatCurrency(10234.56 + ($seedOffset * 50), 'FCFA'),
                'variation' => $this->formatVariation($seedOffset * 50, $seedOffset * 0.49),
                'variation_percentage' => round($seedOffset * 0.49, 2),
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
                'source' => 'mock',
            ],
            [
                'id' => 'FCP-WALO',
                'name' => 'FCP WALO',
                'company' => 'Walo Asset Management',
                'nav_numeric' => 10567.89 + ($seedOffset * 45),
                'nav_value' => $this->formatCurrency(10567.89 + ($seedOffset * 45), 'FCFA'),
                'variation' => $this->formatVariation($seedOffset * 45, $seedOffset * 0.43),
                'variation_percentage' => round($seedOffset * 0.43, 2),
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
                'source' => 'mock',
            ],
            [
                'id' => 'FCP-SOGEDYNAMIQUE',
                'name' => 'FCP SOGEDYNAMIQUE',
                'company' => 'Société Générale CI',
                'nav_numeric' => 13456.78 + ($seedOffset * 100),
                'nav_value' => $this->formatCurrency(13456.78 + ($seedOffset * 100), 'FCFA'),
                'variation' => $this->formatVariation($seedOffset * 100, $seedOffset * 0.74),
                'variation_percentage' => round($seedOffset * 0.74, 2),
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Mixte',
                'source' => 'mock',
            ],
        ];
    }

    /**
     * Données par défaut des FCP africains (fallback statique)
     * Utilisé quand toutes les sources échouent
     */
    public function getDefaultAfricanFunds(): array
    {
        $today = now();

        return [
            [
                'id' => 'FCP-SOGEVALOR',
                'name' => 'FCP SOGEVALOR',
                'company' => 'Société Générale CI',
                'nav_numeric' => 15234.56,
                'nav_value' => $this->formatCurrency(15234.56, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-PLACEMENT-CROISSANCE',
                'name' => 'FCP PLACEMENT CROISSANCE',
                'company' => 'CGF Bourse (Sénégal)',
                'nav_numeric' => 12890.45,
                'nav_value' => $this->formatCurrency(12890.45, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'default',
            ],
            [
                'id' => 'ATTIJARI-ACTIONS',
                'name' => 'ATTIJARI ACTIONS',
                'company' => 'Attijari Asset Management',
                'nav_numeric' => 18567.89,
                'nav_value' => $this->formatCurrency(18567.89, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-BOA-ACTIONS',
                'name' => 'FCP BOA ACTIONS',
                'company' => 'BOA Asset Management',
                'nav_numeric' => 14234.12,
                'nav_value' => $this->formatCurrency(14234.12, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-CORIS-ACTION',
                'name' => 'FCP CORIS ACTION',
                'company' => 'Coris Asset Management (Burkina)',
                'nav_numeric' => 11456.78,
                'nav_value' => $this->formatCurrency(11456.78, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-AAM-EPARGNE-ACTION',
                'name' => 'FCP AAM EPARGNE ACTION',
                'company' => 'Africaine Asset Management (Bénin)',
                'nav_numeric' => 10890.34,
                'nav_value' => $this->formatCurrency(10890.34, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-OPTI-PLACEMENT',
                'name' => 'FCP-1 OPTI PLACEMENT',
                'company' => 'OPTI Gestion (Togo)',
                'nav_numeric' => 9876.54,
                'nav_value' => $this->formatCurrency(9876.54, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-ECOBANK-ACTIONS',
                'name' => 'FCP ECOBANK ACTIONS UEMOA',
                'company' => 'Ecobank Asset Management',
                'nav_numeric' => 16789.23,
                'nav_value' => $this->formatCurrency(16789.23, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Actions',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-BNDE-VALEURS',
                'name' => 'FCP BNDE VALEURS',
                'company' => 'BNDE (Sénégal)',
                'nav_numeric' => 10234.56,
                'nav_value' => $this->formatCurrency(10234.56, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-WALO',
                'name' => 'FCP WALO',
                'company' => 'Walo Asset Management',
                'nav_numeric' => 10567.89,
                'nav_value' => $this->formatCurrency(10567.89, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Obligations',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-SOGEDYNAMIQUE',
                'name' => 'FCP SOGEDYNAMIQUE',
                'company' => 'Société Générale CI',
                'nav_numeric' => 13456.78,
                'nav_value' => $this->formatCurrency(13456.78, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Mixte',
                'source' => 'default',
            ],
            [
                'id' => 'FCP-AL-BARAKA',
                'name' => 'FCP AL BARAKA',
                'company' => 'Al Baraka Finance',
                'nav_numeric' => 11234.56,
                'nav_value' => $this->formatCurrency(11234.56, 'FCFA'),
                'variation' => $this->formatVariation(0, 0),
                'variation_percentage' => 0.00,
                'currency' => 'FCFA',
                'date' => $today->format('Y-m-d'),
                'category' => 'Mixte',
                'source' => 'default',
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
