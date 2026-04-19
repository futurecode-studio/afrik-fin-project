<?php

namespace App\Services;

use App\Models\GovernmentBond;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service de synchronisation des obligations / bons du Trésor
 * depuis UMOA-Titres (agence officielle des titres publics UEMOA).
 *
 * Pipeline :
 *   1. Fetch https://www.umoatitres.org/fr/calendrier-des-emissions-2/
 *   2. Extrait les URLs des adjudications (pattern /fr/emission/...)
 *   3. Fetch chaque page d'adjudication pour en extraire les tables :
 *      - Table "header"   : Date de l'opération, Montant mis en adjudication, Date de valeur
 *      - Table "titre(s)" : Durée, Échéance, Taux d'intérêt, Valeur nominale
 *      - Table "résultats" (si disponibles) : Taux moyen pondéré, Rendement moyen pondéré, etc.
 *
 * 100% scraping HTML. Aucune clé API. Les URLs sont mises en cache (1h).
 */
class UMOATitresService
{
    private const CACHE_TTL = 3600;
    private const HTTP_TIMEOUT = 20;
    private const MAX_EMISSIONS_PER_RUN = 30;

    private string $baseUrl = 'https://www.umoatitres.org';
    private string $calendarUrl = 'https://www.umoatitres.org/fr/calendrier-des-emissions-2/';

    /**
     * Mapping pays UEMOA (slug URL → code ISO + nom FR).
     */
    private array $countrySlugs = [
        'benin' => ['code' => 'BJ', 'name' => 'Bénin', 'article' => 'du'],
        'burkina' => ['code' => 'BF', 'name' => 'Burkina Faso', 'article' => 'du'],
        'cote-divoire' => ['code' => 'CI', 'name' => "Côte d'Ivoire", 'article' => 'de la'],
        'guinee-bissau' => ['code' => 'GW', 'name' => 'Guinée-Bissau', 'article' => 'de'],
        'mali' => ['code' => 'ML', 'name' => 'Mali', 'article' => 'du'],
        'niger' => ['code' => 'NE', 'name' => 'Niger', 'article' => 'du'],
        'senegal' => ['code' => 'SN', 'name' => 'Sénégal', 'article' => 'du'],
        'togo' => ['code' => 'TG', 'name' => 'Togo', 'article' => 'du'],
    ];

    /**
     * Récupère toutes les adjudications parsées depuis UMOA-Titres (cache 1h).
     */
    public function fetchAuctions(): array
    {
        return Cache::remember('umoa_titres_auctions_v2', self::CACHE_TTL, function () {
            $urls = $this->fetchEmissionUrls();
            if (empty($urls)) {
                Log::warning('UMOA-Titres: aucune URL d\'émission trouvée sur le calendrier.');
                return [];
            }

            $auctions = [];
            foreach (array_slice($urls, 0, self::MAX_EMISSIONS_PER_RUN) as $url) {
                $parsed = $this->fetchEmissionDetail($url);
                if ($parsed) {
                    // Une page = potentiellement plusieurs titres (BAT + OAT)
                    foreach ($parsed as $p) {
                        $auctions[] = $p;
                    }
                }
            }
            Log::info('UMOA-Titres: ' . count($auctions) . ' titre(s) parsé(s) depuis ' . count($urls) . ' émission(s).');
            return $auctions;
        });
    }

    /**
     * Extrait la liste des URLs d'émissions depuis la page calendrier.
     */
    private function fetchEmissionUrls(): array
    {
        try {
            $response = Http::timeout(self::HTTP_TIMEOUT)
                ->withOptions(['verify' => false])
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; AfrikFinBot/1.0)'])
                ->get($this->calendarUrl);

            if (!$response->successful()) {
                Log::debug('UMOA-Titres calendar: HTTP ' . $response->status());
                return [];
            }

            $html = $response->body();
            if (preg_match_all('#https?://www\.umoatitres\.org/fr/emission/[a-z0-9\-]+/#i', $html, $matches)) {
                return array_values(array_unique($matches[0]));
            }
        } catch (\Throwable $e) {
            Log::warning('UMOA-Titres calendar fetch failed: ' . $e->getMessage());
        }
        return [];
    }

    /**
     * Fetch + parse la page d'une émission. Peut produire 1 à N titres.
     * @return array<int,array> Payloads compatibles GovernmentBond.
     */
    private function fetchEmissionDetail(string $url): array
    {
        try {
            $response = Http::timeout(self::HTTP_TIMEOUT)
                ->withOptions(['verify' => false])
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; AfrikFinBot/1.0)'])
                ->get($url);

            if (!$response->successful()) {
                return [];
            }

            return $this->parseEmissionHtml($response->body(), $url);
        } catch (\Throwable $e) {
            Log::debug("UMOA-Titres: échec fetch {$url} — " . $e->getMessage());
            return [];
        }
    }

    /**
     * Parse le HTML d'une page d'émission et renvoie la liste des titres.
     */
    private function parseEmissionHtml(string $html, string $url): array
    {
        // 1) Métadonnées communes issues de l'URL (pays + date)
        $meta = $this->parseMetaFromUrl($url);
        if (!$meta) {
            return [];
        }

        // 2) Extraire la table "header" : Date opération / Montant / Date valeur
        $header = $this->extractHeaderTable($html);

        // 3) Extraire TOUTES les tables de type "titre" (class="tableau-single-emission" avec <h3>BAT|OAT|OATR</h3>)
        $titles = $this->extractTitleTables($html);
        if (empty($titles)) {
            return [];
        }

        // 4) Construire un payload par titre
        $payloads = [];
        foreach ($titles as $idx => $t) {
            $payload = $this->buildPayload($meta, $header, $t, $url, $idx);
            if ($payload) {
                $payloads[] = $payload;
            }
        }
        return $payloads;
    }

    /**
     * Extrait (pays + date) depuis le slug URL, ex :
     *   /fr/emission/emission-simultanee-du-tresor-du-benin-du-16-04-2026/
     *   → ['country' => 'benin', 'date' => Carbon('2026-04-16')]
     */
    private function parseMetaFromUrl(string $url): ?array
    {
        $slug = basename(rtrim($url, '/'));
        // Date finale : "du-DD-MM-YYYY"
        if (!preg_match('/du-(\d{2})-(\d{2})-(\d{4})$/', $slug, $dm)) {
            return null;
        }
        try {
            $date = Carbon::createFromFormat('d-m-Y', "{$dm[1]}-{$dm[2]}-{$dm[3]}");
        } catch (\Throwable) {
            return null;
        }

        // Pays
        $country = null;
        foreach ($this->countrySlugs as $slugKey => $info) {
            if (strpos($slug, '-' . $slugKey . '-') !== false) {
                $country = array_merge(['slug' => $slugKey], $info);
                break;
            }
        }
        // Cas "emission-simultanee-de-bons-et-dobligations-du-tresor-du-21-04-2026" (sans pays = multi-pays)
        if (!$country) {
            $country = ['slug' => 'uemoa', 'code' => 'UEMOA', 'name' => 'UEMOA (multi-pays)', 'article' => 'de'];
        }

        return ['country' => $country, 'auction_date' => $date];
    }

    /**
     * Table générique en haut de page avec Date opération / Montant / Date valeur.
     */
    private function extractHeaderTable(string $html): array
    {
        $data = [];
        if (preg_match('/<table class="tableau-single-emission"><tbody>(.*?)<\/tbody><\/table>/is', $html, $m)) {
            $rows = $m[1];
            if (preg_match('/Date de l[\'’]op[eé]ration:?<\/td>\s*<td[^>]*>([^<]+)</iu', $rows, $d)) {
                $data['auction_date'] = trim($d[1]);
            }
            if (preg_match('/Montant mis en adjudication:?<\/td>\s*<td[^>]*>([^<]+)</i', $rows, $d)) {
                $data['amount'] = trim($d[1]);
            }
            if (preg_match('/Date de valeur:?<\/td>\s*<td[^>]*>([^<]+)</i', $rows, $d)) {
                $data['issue_date'] = trim($d[1]);
            }
        }
        return $data;
    }

    /**
     * Récupère chaque titre (BAT / OAT / OATR / OATI) en fusionnant la table
     * "caractéristiques" (durée, échéance, taux nominal) avec la table "résultats"
     * (taux moyen pondéré, rendement moyen pondéré) qui la suit, par ordre.
     */
    private function extractTitleTables(string $html): array
    {
        $titles = [];
        if (!preg_match_all('/<table class="tableau-single-emission">(.*?)<\/table>/is', $html, $mm)) {
            return $titles;
        }

        // On parcourt dans l'ordre d'apparition : chaque table qui a une "Durée"
        // crée un nouveau titre. Les tables suivantes ayant "Taux moyen pondéré"
        // enrichissent le dernier titre du même type.
        foreach ($mm[1] as $block) {
            if (!preg_match('/<h3>\s*(BAT|OAT|OATR|OATI)\s*<\/h3>/i', $block, $typeM)) {
                continue;
            }
            $type = strtoupper($typeM[1]);

            $isCharacteristics = (bool) preg_match('/Dur[eé]e:?/iu', $block);
            $isResults = (bool) preg_match('/Taux moyen pond[eé]r[eé]:?/iu', $block);

            if ($isCharacteristics) {
                $row = ['type' => $type];
                if (preg_match('/Dur[eé]e:?<\/td>\s*<td[^>]*>([^<]+)</iu', $block, $m)) $row['duration'] = trim($m[1]);
                if (preg_match('/[EÉ]ch[eé]ance:?<\/td>\s*<td[^>]*>([^<]+)</iu', $block, $m)) $row['maturity'] = trim($m[1]);
                if (preg_match('/Taux d[\'’]int[eé]r[eê]t:?<\/td>\s*<td[^>]*>([^<]*)</iu', $block, $m)) $row['rate'] = trim($m[1]);
                if (preg_match('/Valeur nominale unitaire:?<\/td>\s*<td[^>]*>([^<]+)</iu', $block, $m)) $row['nominal'] = trim($m[1]);
                $titles[] = $row;
            } elseif ($isResults) {
                // Associe les résultats au dernier titre de même type qui n'a pas encore de taux pondéré
                for ($i = count($titles) - 1; $i >= 0; $i--) {
                    if ($titles[$i]['type'] === $type && !isset($titles[$i]['weighted_rate'])) {
                        if (preg_match('/Taux moyen pond[eé]r[eé]:?<\/td>\s*<td[^>]*>([^<]+)</iu', $block, $m)) $titles[$i]['weighted_rate'] = trim($m[1]);
                        if (preg_match('/Rendement moyen pond[eé]r[eé]:?<\/td>\s*<td[^>]*>([^<]+)</iu', $block, $m)) $titles[$i]['weighted_yield'] = trim($m[1]);
                        if (preg_match('/Montant global des soumissions:?<\/td>\s*<td[^>]*>([^<]+)</iu', $block, $m)) $titles[$i]['total_bids'] = trim($m[1]);
                        if (preg_match('/Soumissions retenues:?<\/td>\s*<td[^>]*>([^<]+)</iu', $block, $m)) $titles[$i]['accepted'] = trim($m[1]);
                        break;
                    }
                }
            }
        }
        return $titles;
    }

    /**
     * Construit le payload GovernmentBond.
     */
    private function buildPayload(array $meta, array $header, array $title, string $url, int $idx): ?array
    {
        $country = $meta['country'];
        $auctionDate = $meta['auction_date'];
        $type = $title['type'];

        // Taux : préfère weighted_rate (taux moyen pondéré finalisé) s'il existe, sinon taux d'intérêt affiché
        $rate = $this->parseNumber($title['weighted_rate'] ?? '');
        if ($rate <= 0) {
            $rate = $this->parseNumber($title['rate'] ?? '');
        }
        $yield = $this->parseNumber($title['weighted_yield'] ?? '') ?: $rate;

        // Maturité
        $maturityYears = $this->extractMaturityYears($title['duration'] ?? '');
        $issueDate = $this->parseFrenchDate($header['issue_date'] ?? '') ?? $auctionDate->copy()->addDay();
        $maturityDate = $this->parseFrenchDate($title['maturity'] ?? '');
        if (!$maturityDate && $maturityYears) {
            $maturityDate = $issueDate->copy()->addDays((int) round($maturityYears * 365));
        }
        if (!$maturityDate) {
            return null;
        }

        $nominal = $this->parseNumber($title['nominal'] ?? '') ?: 10000;

        // ISIN synthétique si non disponible
        $isin = $country['code'] . strtoupper(substr(md5($url . $type . $idx), 0, 10));

        $isBat = $type === 'BAT';
        $label = match ($type) {
            'BAT' => 'Bon Assimilable du Trésor',
            'OAT' => 'Obligation Assimilable du Trésor',
            'OATR' => 'Obligation Assimilable du Trésor (reouverture)',
            'OATI' => 'Obligation Assimilable du Trésor Islamique (Sukuk)',
            default => 'Titre public',
        };

        return [
            'name' => "{$type} {$country['name']} — {$title['duration']} — Adjudication du " . $auctionDate->format('d/m/Y'),
            'issuer' => $country['code'] === 'UEMOA'
                ? 'Trésors de la zone UEMOA'
                : "République {$country['article']} {$country['name']}",
            'country' => $country['name'],
            'isin_code' => $isin,
            'external_code' => 'UMOA-' . $auctionDate->format('Ymd') . '-' . $country['code'] . '-' . $type . '-' . $idx,
            'nominal_value' => $nominal,
            'currency' => 'FCFA',
            'interest_rate' => $rate ?: 0,
            'interest_type' => $isBat ? 'zero_coupon' : 'fixed',
            'payment_frequency' => $isBat ? 'annual' : 'semi_annual',
            'issue_date' => $issueDate->toDateString(),
            'auction_date' => $auctionDate->toDateString(),
            'maturity_date' => $maturityDate->toDateString(),
            'maturity_years' => max(1, (int) ceil($maturityYears ?? 1)),
            'current_price' => $nominal,
            'yield_to_maturity' => $yield ?: null,
            'rating' => null,
            'description' => "{$label} — Durée {$title['duration']}. Émission primaire via adjudication UMOA-Titres du " . $auctionDate->format('d/m/Y') . ".",
            'data_source' => 'umoa_titres',
            'source_url' => $url,
            'last_synced_at' => now(),
            'risk_level' => $this->riskFromCountry($country['code']),
            'minimum_investment' => $nominal,
            'is_active' => true,
            'display_order' => 0,
        ];
    }

    private function parseFrenchDate(string $raw): ?Carbon
    {
        $raw = trim($raw);
        try { return Carbon::createFromFormat('d/m/Y', $raw); } catch (\Throwable) {}
        try { return Carbon::createFromFormat('d-m-Y', $raw); } catch (\Throwable) {}
        return null;
    }

    private function extractMaturityYears(string $duration): ?float
    {
        $d = strtolower(trim($duration));
        if (preg_match('/(\d+)\s*ans?/', $d, $m)) return (float) $m[1];
        if (preg_match('/(\d+)\s*mois/', $d, $m)) return round(((int) $m[1]) / 12, 2);
        if (preg_match('/(\d+)\s*jours?/', $d, $m)) return round(((int) $m[1]) / 365, 2);
        return null;
    }

    private function parseNumber(string $raw): float
    {
        $v = str_replace([' ', "\u{00A0}", '%'], '', trim($raw));
        $v = str_replace(',', '.', $v);
        return (float) preg_replace('/[^\d.\-]/', '', $v);
    }

    private function riskFromCountry(string $code): string
    {
        return match ($code) {
            'CI', 'SN', 'BJ' => 'low',
            'TG', 'BF', 'NE' => 'medium',
            'ML', 'GW' => 'high',
            default => 'medium',
        };
    }

    /**
     * Synchronise la base locale.
     * @return array{created:int, updated:int, deactivated:int, total:int}
     */
    public function syncBonds(bool $purgeMissing = false): array
    {
        $auctions = $this->fetchAuctions();
        $stats = ['created' => 0, 'updated' => 0, 'deactivated' => 0, 'total' => count($auctions)];

        $seenIsins = [];
        foreach ($auctions as $payload) {
            $seenIsins[] = $payload['isin_code'];
            $existed = GovernmentBond::where('isin_code', $payload['isin_code'])->exists();
            GovernmentBond::updateOrCreate(
                ['isin_code' => $payload['isin_code']],
                $payload
            );
            $existed ? $stats['updated']++ : $stats['created']++;
        }

        if ($purgeMissing && !empty($seenIsins)) {
            $stats['deactivated'] = GovernmentBond::where('data_source', 'umoa_titres')
                ->whereNotIn('isin_code', $seenIsins)
                ->update(['is_active' => false]);
        }

        return $stats;
    }

    public function clearCache(): void
    {
        Cache::forget('umoa_titres_auctions_v2');
    }
}
