<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledOrder extends Model
{
    protected $fillable = [
        'user_id', 'stock_id', 'condition_type', 'side', 'quantity',
        'target_price', 'stop_loss', 'take_profit', 'protection_active', 'status', 'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'target_price' => 'decimal:2',
        'stop_loss' => 'decimal:2',
        'take_profit' => 'decimal:2',
        'protection_active' => 'boolean',
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
