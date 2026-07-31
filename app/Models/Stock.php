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
        if ($this->market_cap >= 1000) {
            return number_format($this->market_cap / 1000, 1) . 'B';
        }
        return number_format($this->market_cap, 0) . 'M';
    }

    /**
     * Plus haut de séance utilisable (> 0), sinon dérivé des cours connus.
     */
    public function effectiveHigh(): ?float
    {
        if ($this->high_price !== null && (float) $this->high_price > 0) {
            return (float) $this->high_price;
        }

        return $this->derivedSessionExtreme(true);
    }

    /**
     * Plus bas de séance utilisable (> 0), sinon dérivé des cours connus.
     */
    public function effectiveLow(): ?float
    {
        if ($this->low_price !== null && (float) $this->low_price > 0) {
            return (float) $this->low_price;
        }

        return $this->derivedSessionExtreme(false);
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
