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
}
