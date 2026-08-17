<?php

namespace App\Services;

use App\Models\Fund;
use Illuminate\Support\Facades\Cache;

/**
 * Catalogue public des FCP/OPCVM.
 *
 * Source : bulletin officiel BRVM (p. 19, 23 juillet 2026), éditable dans l’admin.
 */
class MutualFundsApiService
{
    private const CACHE_KEY = 'mutual_funds_data';

    private int $cacheDuration;

    public function __construct()
    {
        $this->cacheDuration = (int) config('services.mutual_funds.cache_duration', 3600);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getMutualFunds(): array
    {
        return Cache::remember(self::CACHE_KEY, $this->cacheDuration, function () {
            return Fund::publicList();
        });
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getDefaultMutualFunds(): array
    {
        return Fund::catalogCollection()
            ->map(fn (Fund $fund) => $fund->toPublicArray())
            ->values()
            ->all();
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return array<int, string>
     */
    public function getCategories(): array
    {
        $cats = array_unique(array_column($this->getMutualFunds(), 'category'));
        sort($cats);

        return array_values($cats);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getFundsByCategory(string $category): array
    {
        return array_values(array_filter(
            $this->getMutualFunds(),
            fn ($f) => ($f['category'] ?? '') === $category
        ));
    }

    public function getFundById(string $id): ?array
    {
        foreach ($this->getMutualFunds() as $fund) {
            if (($fund['id'] ?? '') === $id) {
                return $fund;
            }
        }

        return null;
    }
}
