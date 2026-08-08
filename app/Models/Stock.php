<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'company_name',
        'exchange',
        'currency',
        'current_price',
        'open_price',
        'previous_price',
        'change_amount',
        'variation_percent',
        'volume',
        'market_cap',
        'shares_outstanding',
        'sector',
        'high_price',
        'low_price',
        'is_active',
        'source',
        'source_updated_at',
        'last_updated',
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'open_price' => 'decimal:2',
        'previous_price' => 'decimal:2',
        'change_amount' => 'decimal:4',
        'variation_percent' => 'decimal:2',
        'market_cap' => 'decimal:2',
        'high_price' => 'decimal:2',
        'low_price' => 'decimal:2',
        'is_active' => 'boolean',
        'last_updated' => 'datetime',
        'source_updated_at' => 'datetime',
    ];

    public function prices()
    {
        return $this->hasMany(StockPrice::class);
    }

    /**
     * Calculer la variation en pourcentage
     */
    public function calculateVariation()
    {
        if ($this->previous_price && $this->previous_price > 0) {
            $this->variation_percent = (($this->current_price - $this->previous_price) / $this->previous_price) * 100;
            $this->save();
        }
    }

    /**
     * Déterminer si la variation est positive
     */
    public function isPositiveVariation(): bool
    {
        return $this->variation_percent > 0;
    }

    /**
     * Déterminer si la variation est négative
     */
    public function isNegativeVariation(): bool
    {
        return $this->variation_percent < 0;
    }

    /**
     * Formater le prix avec le symbole FCFA
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->current_price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Formater la capitalisation boursière
     */
    public function getFormattedMarketCapAttribute(): string
    {
        $mrd = self::capToBillions($this->market_cap);
        if ($mrd === null) {
            return '—';
        }

        return number_format($mrd, $mrd >= 10 ? 1 : 2, ',', ' ').' Mrd';
    }

    /**
     * Capitalisation en milliards FCFA (null si absente).
     * Accepte le stockage historique en millions ou en FCFA absolus.
     */
    public static function capToBillions(null|float|int|string $marketCap): ?float
    {
        if ($marketCap === null || $marketCap === '') {
            return null;
        }

        $cap = (float) $marketCap;
        if ($cap <= 0) {
            return null;
        }

        // FCFA absolus (ex. feed Mansa fundamentals / metrics)
        if ($cap >= 1_000_000_000) {
            return $cap / 1_000_000_000;
        }

        // Stockage métier en millions de FCFA
        return $cap / 1000;
    }

    public static function formatCapMrd(null|float|int|string $marketCap, int $decimals = 2): string
    {
        $mrd = self::capToBillions($marketCap);
        if ($mrd === null) {
            return '—';
        }

        return number_format($mrd, $decimals, ',', ' ');
    }

    /**
     * Normalise une cap brute (FCFA absolus ou millions) vers millions FCFA.
     */
    public static function normalizeCapToMillions(null|float|int|string $raw): ?float
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        $cap = (float) $raw;
        if ($cap <= 0) {
            return null;
        }

        if ($cap >= 1_000_000_000) {
            return round($cap / 1_000_000, 2);
        }

        return round($cap, 2);
    }

    /**
     * Plus haut de séance utilisable (> 0), sinon dérivé des cours connus.
     * Prend le max entre high stocké et cours connus (évite les OHLC seed obsolètes).
     */
    public function effectiveHigh(): ?float
    {
        $candidates = [];
        if ($this->high_price !== null && (float) $this->high_price > 0) {
            $candidates[] = (float) $this->high_price;
        }
        $derived = $this->derivedSessionExtreme(true);
        if ($derived !== null) {
            $candidates[] = $derived;
        }

        return $candidates === [] ? null : max($candidates);
    }

    /**
     * Plus bas de séance utilisable (> 0), sinon dérivé des cours connus.
     */
    public function effectiveLow(): ?float
    {
        $candidates = [];
        if ($this->low_price !== null && (float) $this->low_price > 0) {
            $candidates[] = (float) $this->low_price;
        }
        $derived = $this->derivedSessionExtreme(false);
        if ($derived !== null) {
            $candidates[] = $derived;
        }

        return $candidates === [] ? null : min($candidates);
    }

    public function effectiveOpen(): ?float
    {
        if ($this->open_price !== null && (float) $this->open_price > 0) {
            return (float) $this->open_price;
        }

        return null;
    }

    public function formatMoney(?float $value, int $decimals = 0): string
    {
        if ($value === null || $value <= 0) {
            return '—';
        }

        return number_format($value, $decimals, ',', ' ');
    }

    private function derivedSessionExtreme(bool $high): ?float
    {
        $points = [];
        foreach ([$this->current_price, $this->previous_price, $this->open_price] as $v) {
            if ($v !== null && (float) $v > 0) {
                $points[] = (float) $v;
            }
        }

        if ($points === []) {
            return null;
        }

        return $high ? max($points) : min($points);
    }
}
