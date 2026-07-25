<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service de récupération des valeurs liquidatives (VL) des FCP/OPCVM UEMOA.
 *
 * Source unique : Sikafinance — https://www.sikafinance.com/opcvm/…
 * Scraping HTML public, gratuit, sans clé API. Cache 1h.
 *
 * 100% données réelles : aucun mock, aucun fallback statique.
 * Si Sikafinance est indisponible, la page doit afficher un état vide avec bouton de
 * retry — jamais de données synthétiques.
 */
class MutualFundsApiService
{
    private const CACHE_KEY = 'mutual_funds_data';
    private const SIKA_BASE_URL = 'https://www.sikafinance.com';

    private int $timeout;
    private int $cacheDuration;

    public function __construct()
    {
        $this->timeout = (int) config('services.mutual_funds.timeout', 20);
        $this->cacheDuration = (int) config('services.mutual_funds.cache_duration', 3600);
    }

    /**
     * Liste statique des OPCVM UEMOA suivis.
     *
     * ⚠️ Ce n'est PAS une seed : c'est un registre de références vers des pages
     * publiques Sikafinance. Les données (VL, variation, date) viennent exclusivement
     * du scraping en direct.
     *
     * Source du registre : https://www.sikafinance.com (page OPCVM listing)
     *
     * @return array<int, array{id: string, name: string, slug: string, company: string, category: string, isin: string, country: string}>
     */
    private function registry(): array
    {
        return [
            // Actions
            ['id' => 'FCP-SOGEVALOR',            'name' => 'FCP SOGEVALOR',            'slug' => 'fcp-sogevalor_CI0000001954',          'company' => 'Société Générale CI',         'category' => 'Actions',    'isin' => 'CI0000001954', 'country' => "Côte d'Ivoire"],
            ['id' => 'FCP-SOGEDYNAMIQUE',        'name' => 'FCP SOGEDYNAMIQUE',        'slug' => 'fcp-sogedynamique_CI0000001970',      'company' => 'Société Générale CI',         'category' => 'Mixte',      'isin' => 'CI0000001970', 'country' => "Côte d'Ivoire"],
            ['id' => 'FCP-PLACEMENT-CROISSANCE', 'name' => 'FCP PLACEMENT CROISSANCE', 'slug' => 'fcp-placement-croissancce_SN0000000274', 'company' => 'CGF Bourse (Sénégal)',       'category' => 'Actions',    'isin' => 'SN0000000274', 'country' => 'Sénégal'],
            ['id' => 'FCP-ACTION-PHARMACIE',     'name' => 'FCP ACTION PHARMACIE',     'slug' => 'fcp-action-pharmacie_SN0000000787',   'company' => 'CGF Bourse (Sénégal)',         'category' => 'Actions',    'isin' => 'SN0000000787', 'country' => 'Sénégal'],
            ['id' => 'FCP-OPTI-PLACEMENT',       'name' => 'FCP-1 OPTI PLACEMENT',     'slug' => 'fcp-1-opti-placement_TG0000000003',   'company' => 'OPTI Gestion (Togo)',          'category' => 'Actions',    'isin' => 'TG0000000003', 'country' => 'Togo'],
            ['id' => 'FCP-AAM-EPARGNE-ACTION',   'name' => 'FCP AAM EPARGNE ACTION',   'slug' => 'fcp-aam-epargne-action_BJ0000000931', 'company' => 'Africaine Asset Management',   'category' => 'Actions',    'isin' => 'BJ0000000931', 'country' => 'Bénin'],
            ['id' => 'FCP-SOAGA-EPARGNE',        'name' => 'FCP SOAGA EPARGNE ACTIONS','slug' => 'fcp-soaga-epargne-actions_BJ0000001467','company' => 'SOAGA (Bénin)',               'category' => 'Actions',    'isin' => 'BJ0000001467', 'country' => 'Bénin'],
            ['id' => 'FCP-CORIS-ACTION',         'name' => 'FCP CORIS ACTION',         'slug' => 'fcp-coris-action_BF0000000208',       'company' => 'Coris Asset Management',      'category' => 'Actions',    'isin' => 'BF0000000208', 'country' => 'Burkina Faso'],
            ['id' => 'FCP-VALORIS',              'name' => 'FCP VALORIS',              'slug' => 'fcp-valoris_SIKA00000003',            'company' => 'Valoris Asset Management',     'category' => 'Actions',    'isin' => 'SIKA00000003', 'country' => 'UEMOA'],
            ['id' => 'FCP-PAM-ACTIONS',          'name' => 'FCP PAM ACTIONS',          'slug' => 'fcp-pam-actions_AFRICA000005',        'company' => 'PAM Asset Management',         'category' => 'Actions',    'isin' => 'AFRICA000005', 'country' => 'UEMOA'],
            ['id' => 'ATTIJARI-ACTIONS',         'name' => 'ATTIJARI ACTIONS',         'slug' => 'attijari-actions_AFRICA000011',       'company' => 'Attijari Asset Management',    'category' => 'Actions',    'isin' => 'AFRICA000011', 'country' => 'UEMOA'],
            ['id' => 'FCP-ECOBANK-ACTIONS',      'name' => 'FCP ECOBANK ACTIONS UEMOA','slug' => 'fcp-ecobank-actions-uemoa_SIKA00000028','company' => 'Ecobank Asset Management',  'category' => 'Actions',    'isin' => 'SIKA00000028', 'country' => 'UEMOA'],
            ['id' => 'FCP-BOA-ACTIONS',          'name' => 'FCP BOA ACTIONS',          'slug' => 'fcp-boa-actions_AFRICA000014',        'company' => 'BOA Asset Management',         'category' => 'Actions',    'isin' => 'AFRICA000014', 'country' => 'UEMOA'],
            ['id' => 'FCP-ATLANTIQUE-ACTIONS',   'name' => 'FCP ATLANTIQUE ACTIONS',   'slug' => 'fcp-atlantique-actions_AFRICA000017', 'company' => 'Atlantique Finance',          'category' => 'Actions',    'isin' => 'AFRICA000017', 'country' => 'UEMOA'],
            // Mixte / Obligations
            ['id' => 'FCP-AL-BARAKA',            'name' => 'FCP AL BARAKA',            'slug' => 'fcp-al-baraka_SIKA00000011',          'company' => 'Al Baraka Finance',            'category' => 'Mixte',      'isin' => 'SIKA00000011', 'country' => 'UEMOA'],
            ['id' => 'FCP-BNDE-VALEURS',         'name' => 'FCP BNDE VALEURS',         'slug' => 'fcp-bnde-valeurs_SIKA00000013',       'company' => 'BNDE (Sénégal)',               'category' => 'Mixte',      'isin' => 'SIKA00000013', 'country' => 'Sénégal'],
            ['id' => 'FCP-WALO',                 'name' => 'FCP WALO',                 'slug' => 'fcp-walo_SIKA00000023',               'company' => 'Walo Asset Management',        'category' => 'Obligations','isin' => 'SIKA00000023', 'country' => 'UEMOA'],
        ];
    }

    /**
     * Retourne l'ensemble des fonds (mis en cache 1h).
     *
     * @return array<int, array<string,mixed>>
     */
    public function getMutualFunds(): array
    {
        return Cache::remember(self::CACHE_KEY, $this->cacheDuration, function () {
            return $this->scrapeAll();
        });
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return array<int, array<string,mixed>>
     */
    private function scrapeAll(): array
    {
        $funds = [];
        foreach ($this->registry() as $opcvm) {
            $parsed = $this->scrapeOne($opcvm);
            if ($parsed) {
                $funds[] = $parsed;
            }
            usleep(150_000); // 150ms entre chaque requête
        }
        return $funds;
    }

    /**
     * @param array<string,string> $opcvm
     * @return array<string,mixed>|null
     */
    private function scrapeOne(array $opcvm): ?array
    {
        $url = self::SIKA_BASE_URL . '/opcvm/' . $opcvm['slug'];

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
                ])
                ->get($url);

            if (!$response->successful()) {
                Log::debug("Sikafinance: HTTP {$response->status()} for {$opcvm['name']}");
                return null;
            }
        } catch (\Throwable $e) {
            Log::debug("Sikafinance fetch failed for {$opcvm['name']}: {$e->getMessage()}");
            return null;
        }

        return $this->parse($response->body(), $opcvm, $url);
    }

    /**
     * Parse une page OPCVM Sikafinance.
     *
     * Structure HTML publique :
     *   <div class="cot_v1a">VALEUR LIQUIDATIVE</div>
     *   <div class="cot_v1b">5&#xA0;694&nbsp;FCFA</div>
     *   <div class="quote_down4">-1,37%</div>
     *
     * @param array<string,string> $opcvm
     * @return array<string,mixed>|null
     */
    private function parse(string $html, array $opcvm, string $sourceUrl): ?array
    {
        $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // VL
        if (!preg_match('/class=["\']cot_v1b["\'][^>]*>([^<]+)FCFA/i', $decoded, $m)) {
            Log::debug("Sikafinance: no VL found for {$opcvm['name']}");
            return null;
        }
        $nav = $this->toFloat($m[1]);
        if ($nav <= 0) {
            return null;
        }

        // Variation (quote_up* ou quote_down*)
        $variation = 0.0;
        if (preg_match('/class=["\']quote_(up|down)\d*["\'][^>]*>([+\-]?[\d,\.]+)%/i', $decoded, $vm)) {
            $value = $this->toFloat($vm[2]);
            $variation = $vm[1] === 'down' && $value > 0 ? -$value : $value;
        }

        // Date (format JJ/MM/AAAA si présent dans la page détail)
        $date = now()->format('Y-m-d');
        if (preg_match('/(\d{2})[\/\-](\d{2})[\/\-](\d{4})/', $html, $dm)) {
            $date = "{$dm[3]}-{$dm[2]}-{$dm[1]}";
        }

        return [
            'id' => $opcvm['id'],
            'isin' => $opcvm['isin'],
            'name' => $opcvm['name'],
            'company' => $opcvm['company'],
            'country' => $opcvm['country'],
            'category' => $opcvm['category'],
            'nav_numeric' => $nav,
            'nav_value' => $this->formatCurrency($nav),
            'variation_percentage' => round($variation, 2),
            'variation' => $this->formatVariation($variation),
            'nav' => $nav,
            'vl' => $nav,
            'currency' => 'FCFA',
            'date' => $date,
            'source' => 'sikafinance',
            'source_url' => $sourceUrl,
            'scraped_at' => now()->toIso8601String(),
        ];
    }

    // ─── Helpers de parsing ──────────────────────────────────────────────

    private function toFloat(string $raw): float
    {
        $s = trim($raw);
        // Supprimer espaces, non-breaking spaces et caractères HTML de mise en forme
        $s = str_replace(["\xc2\xa0", "\xe2\x80\xaf", ' ', ',', '+'], ['', '', '', '.', ''], $s);
        $s = preg_replace('/[^\d\.\-]/', '', $s ?? '');
        return (float) $s;
    }

    private function formatCurrency(float $value): string
    {
        return number_format($value, 2, ',', ' ') . ' FCFA';
    }

    private function formatVariation(float $percent): string
    {
        $sign = $percent > 0 ? '+' : ($percent < 0 ? '' : '+');
        return $sign . number_format($percent, 2, ',', ' ') . '%';
    }

    // ─── API publique utilisée par les composants ─────────────────────────

    /**
     * @return array<int,string>
     */
    public function getCategories(): array
    {
        $funds = $this->getMutualFunds();
        $cats = array_unique(array_column($funds, 'category'));
        sort($cats);
        return array_values($cats);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public function getFundsByCategory(string $category): array
    {
        return array_values(array_filter(
            $this->getMutualFunds(),
            fn($f) => $f['category'] === $category
        ));
    }

    public function getFundById(string $id): ?array
    {
        foreach ($this->getMutualFunds() as $fund) {
            if ($fund['id'] === $id) {
                return $fund;
            }
        }
        return null;
    }
}
