<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioHolding extends Model
{
    protected $fillable = [
        'user_id', 'stock_id', 'label', 'asset_type',
        'quantity', 'avg_cost', 'currency', 'external_ref',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'avg_cost' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function marketValue(): float
    {
        if ($this->stock) {
            return (float) $this->quantity * (float) $this->stock->current_price;
        }

        return (float) $this->quantity * (float) ($this->avg_cost ?? 0);
    }

    public function costBasis(): float
    {
        return (float) $this->quantity * (float) ($this->avg_cost ?? 0);
    }
}
