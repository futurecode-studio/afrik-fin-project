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
}
