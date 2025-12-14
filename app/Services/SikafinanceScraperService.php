<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service de scraping pour récupérer les données des OPCVM/FCP
 * depuis Sikafinance.com - Source fiable pour les données UEMOA
 */
class SikafinanceScraperService
{
    private $baseUrl = 'https://www.sikafinance.com';
    private $timeout;
    private $cacheDuration;

    public function __construct()
    {
        $this->timeout = config('services.mutual_funds.timeout', 30);
        $this->cacheDuration = config('services.mutual_funds.cache_duration', 3600);
    }

    /**
     * Liste des OPCVM disponibles sur Sikafinance avec leurs URLs
     */
    private function getOPCVMList(): array
    {
        return [
            // FCP Actions
            [
                'id' => 'FCP-OPTI-PLACEMENT',
                'name' => 'FCP-1 OPTI PLACEMENT',
                'url' => '/opcvm/fcp-1-opti-placement_TG0000000003',
                'company' => 'OPTI Gestion (Togo)',
                'category' => 'Actions',
                'isin' => 'TG0000000003',
            ],
            [
                'id' => 'FCP-VALORIS',
                'name' => 'FCP VALORIS',
                'url' => '/opcvm/fcp-valoris_SIKA00000003',
                'company' => 'Valoris Asset Management',
                'category' => 'Actions',
                'isin' => 'SIKA00000003',
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
                'id' => 'FCP-AAM-EPARGNE-ACTION',
                'name' => 'FCP AAM EPARGNE ACTION',
                'url' => '/opcvm/fcp-aam-epargne-action_BJ0000000931',
                'company' => 'Africaine Asset Management (Bénin)',
                'category' => 'Actions',
                'isin' => 'BJ0000000931',
            ],
            [
                'id' => 'ATTIJARI-ACTIONS',
                'name' => 'ATTIJARI ACTIONS',
                'url' => '/opcvm/attijari-actions_AFRICA000011',
                'company' => 'Attijari Asset Management',
                'category' => 'Actions',
                'isin' => 'AFRICA000011',
            ],
            [
                'id' => 'FCP-CORIS-ACTION',
                'name' => 'FCP CORIS ACTION',
                'url' => '/opcvm/fcp-coris-action_BF0000000208',
                'company' => 'Coris Asset Management (Burkina)',
                'category' => 'Actions',
                'isin' => 'BF0000000208',
            ],
            [
                'id' => 'FCP-PLACEMENT-CROISSANCE',
                'name' => 'FCP PLACEMENT CROISSANCE',
                'url' => '/opcvm/fcp-placement-croissancce_SN0000000274',
                'company' => 'CGF Bourse (Sénégal)',
                'category' => 'Actions',
                'isin' => 'SN0000000274',
            ],
            [
                'id' => 'FCP-ECOBANK-ACTIONS',
                'name' => 'FCP ECOBANK ACTIONS UEMOA',
                'url' => '/opcvm/fcp-ecobank-actions-uemoa_SIKA00000028',
                'company' => 'Ecobank Asset Management',
                'category' => 'Actions',
                'isin' => 'SIKA00000028',
            ],
            [
                'id' => 'FCP-SOGEVALOR',
                'name' => 'FCP SOGEVALOR',
                'url' => '/opcvm/fcp-sogevalor_CI0000001954',
                'company' => 'Société Générale CI',
                'category' => 'Actions',
                'isin' => 'CI0000001954',
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
                'id' => 'FCP-SOAGA-EPARGNE',
                'name' => 'FCP SOAGA EPARGNE ACTIONS',
                'url' => '/opcvm/fcp-soaga-epargne-actions_BJ0000001467',
                'company' => 'SOAGA (Bénin)',
                'category' => 'Actions',
                'isin' => 'BJ0000001467',
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
                'category' => 'Mixte',
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
                'id' => 'FCP-ACTION-PHARMACIE',
                'name' => 'FCP ACTION PHARMACIE',
                'url' => '/opcvm/fcp-action-pharmacie_SN0000000787',
                'company' => 'CGF Bourse (Sénégal)',
                'category' => 'Actions',
                'isin' => 'SN0000000787',
            ],
            [
                'id' => 'FCP-SOGEDYNAMIQUE',
                'name' => 'FCP SOGEDYNAMIQUE',
                'url' => '/opcvm/fcp-sogedynamique_CI0000001970',
                'company' => 'Société Générale CI',
                'category' => 'Mixte',
                'isin' => 'CI0000001970',
            ],
        ];
    }

    /**
     * Récupérer les données d'un OPCVM depuis Sikafinance
     */
    public function scrapeOPCVM(array $opcvm): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
                ])
                ->get($this->baseUrl . $opcvm['url']);

            if (!$response->successful()) {
                Log::debug("Sikafinance: Failed to fetch {$opcvm['name']}: HTTP {$response->status()}");
                return null;
            }

            $html = $response->body();
            
            // Parser le HTML pour extraire les données
            return $this->parseOPCVMPage($html, $opcvm);

        } catch (\Exception $e) {
            Log::debug("Sikafinance scraping error for {$opcvm['name']}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Parser la page HTML d'un OPCVM pour extraire les données
     */
    private function parseOPCVMPage(string $html, array $opcvm): ?array
    {
        try {
            // Rechercher la valeur liquidative dans le HTML
            // Pattern typique: "Valeur liquidative" suivi d'un nombre
            $navValue = null;
            $variation = 0;
            $date = now()->format('Y-m-d');

            // Pattern pour la VL (différents formats possibles)
            if (preg_match('/(?:VL|Valeur\s*liquidative)[^\d]*(\d[\d\s,\.]+)/i', $html, $matches)) {
                $navValue = $this->parseNumber($matches[1]);
            }

            // Pattern alternatif pour les tableaux
            if (!$navValue && preg_match('/<td[^>]*>(\d[\d\s,\.]+)\s*(?:FCFA|XOF)?<\/td>/i', $html, $matches)) {
                $navValue = $this->parseNumber($matches[1]);
            }

            // Rechercher la variation
            if (preg_match('/(?:variation|performance)[^\d-]*([+-]?\d[\d,\.]*)\s*%/i', $html, $matches)) {
                $variation = $this->parseNumber($matches[1]);
            }

            // Rechercher la date
            if (preg_match('/(\d{2})[\/\-](\d{2})[\/\-](\d{4})/', $html, $matches)) {
                $date = "{$matches[3]}-{$matches[2]}-{$matches[1]}";
            }

            if ($navValue) {
                return [
                    'id' => $opcvm['id'],
                    'name' => $opcvm['name'],
                    'company' => $opcvm['company'],
                    'nav_value' => $this->formatCurrency($navValue),
                    'nav_numeric' => $navValue,
                    'variation' => $this->formatVariation($variation),
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
            Log::debug("Parse error for {$opcvm['name']}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer tous les OPCVM
     */
    public function getAllOPCVM(): array
    {
        $funds = [];
        $opcvmList = $this->getOPCVMList();

        foreach ($opcvmList as $opcvm) {
            $fund = $this->scrapeOPCVM($opcvm);
            if ($fund) {
                $funds[] = $fund;
            }
            // Délai pour éviter de surcharger le serveur
            usleep(200000); // 200ms
        }

        return $funds;
    }

    /**
     * Parser un nombre depuis une chaîne
     */
    private function parseNumber(string $value): float
    {
        // Nettoyer la chaîne
        $value = trim($value);
        // Supprimer les espaces
        $value = str_replace(' ', '', $value);
        // Remplacer la virgule par un point
        $value = str_replace(',', '.', $value);
        // Supprimer tout sauf les chiffres et le point
        $value = preg_replace('/[^\d.]/', '', $value);
        
        return (float) $value;
    }

    /**
     * Formater une valeur en devise FCFA
     */
    private function formatCurrency(float $value): string
    {
        return number_format($value, 2, ',', ' ') . ' FCFA';
    }

    /**
     * Formater la variation
     */
    private function formatVariation(float $percentage): string
    {
        $sign = $percentage >= 0 ? '+' : '';
        return $sign . number_format($percentage, 2, ',', ' ') . '%';
    }
}
