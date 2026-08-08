<?php

namespace App\Services;

/**
 * Catalogue du nombre d'actions en circulation (pages émetteurs BRVM).
 * Permet de recalculer market_cap = shares × cours.
 */
class BrvmSharesCatalog
{
    /** Plafond de plausibilité d'une cap individuelle (FCFA). */
    private const MAX_SINGLE_CAP_FCFA = 25_000_000_000_000;

    /**
     * @return array<string, int> ticker => shares
     */
    public function all(): array
    {
        $path = database_path('data/brvm_shares.json');
        if (! is_file($path)) {
            return [];
        }

        $raw = json_decode((string) file_get_contents($path), true);
        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $ticker => $shares) {
            if (! is_string($ticker) || str_starts_with($ticker, '_')) {
                continue;
            }
            $n = (int) $shares;
            if ($n > 0) {
                $out[strtoupper(trim($ticker))] = $n;
            }
        }

        return $out;
    }

    public function sharesFor(string $ticker): ?int
    {
        $all = $this->all();
        $ticker = strtoupper(trim($ticker));

        return $all[$ticker] ?? null;
    }

    /**
     * Capitalisation en millions FCFA à partir du nombre d'actions et du cours.
     */
    public function marketCapMillions(int $shares, float $price): ?float
    {
        if ($shares <= 0 || $price <= 0) {
            return null;
        }

        $capFcfa = $shares * $price;

        // Corrige les typos BRVM du type "100 000 0000" (zéro en trop)
        if ($capFcfa > self::MAX_SINGLE_CAP_FCFA && $shares % 10 === 0) {
            $shares = intdiv($shares, 10);
            $capFcfa = $shares * $price;
        }

        if ($capFcfa <= 0) {
            return null;
        }

        return round($capFcfa / 1_000_000, 2);
    }
}
