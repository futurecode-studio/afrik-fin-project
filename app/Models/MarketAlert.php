<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketAlert extends Model
{
    protected $fillable = [
        'user_id', 'stock_id', 'asset_label', 'asset_category', 'trigger_type',
        'threshold', 'severity', 'status', 'channel', 'notes', 'triggered_at',
    ];

    protected $casts = [
        'threshold' => 'decimal:4',
        'triggered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public static function triggerLabel(string $type): string
    {
        return match ($type) {
            'price_above' => 'Prix ≥ seuil',
            'price_below' => 'Prix ≤ seuil',
            'volume' => 'Volume ≥ seuil',
            'variation_above' => 'Variation ≥ seuil (%)',
            'variation_below' => 'Variation ≤ seuil (%)',
            'rsi' => 'RSI',
            'calendar' => 'Calendrier',
            default => str_replace('_', ' ', $type),
        };
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'active' => 'Active',
            'triggered' => 'Déclenchée',
            'paused' => 'En pause',
            'planned' => 'Planifiée',
            default => $status,
        };
    }

    public static function severityLabel(string $severity): string
    {
        return match ($severity) {
            'critique' => 'Critique',
            'faible' => 'Faible',
            default => 'Normale',
        };
    }

    public function triggerLabelInstance(): string
    {
        return self::triggerLabel($this->trigger_type);
    }

    public function statusBadgeClasses(): string
    {
        return match ($this->status) {
            'triggered' => 'bg-red-50 text-red-700',
            'paused' => 'bg-slate-100 text-slate-600',
            'planned' => 'bg-[#dae3f6] text-[#001a61]',
            default => 'bg-emerald-50 text-emerald-800',
        };
    }

    public function severityBadgeClasses(): string
    {
        return match ($this->severity) {
            'critique' => 'bg-red-50 text-red-700',
            'faible' => 'bg-slate-100 text-slate-600',
            default => 'bg-amber-50 text-amber-800',
        };
    }

    /**
     * Écart courant vs seuil (null si non applicable).
     */
    public function distanceToThreshold(): ?float
    {
        if (! $this->stock || $this->threshold === null) {
            return null;
        }

        $price = (float) $this->stock->current_price;
        $threshold = (float) $this->threshold;

        return match ($this->trigger_type) {
            'price_above' => $threshold - $price,
            'price_below' => $price - $threshold,
            'volume' => $threshold - (float) ($this->stock->volume ?? 0),
            'variation_above' => $threshold - (float) ($this->stock->variation_percent ?? 0),
            'variation_below' => (float) ($this->stock->variation_percent ?? 0) - $threshold,
            default => null,
        };
    }
}
