<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOrderIntent extends Model
{
    protected $fillable = [
        'user_id', 'partner_id', 'stock_id', 'side', 'order_type', 'quantity', 'limit_price',
        'status', 'name', 'email', 'phone', 'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'limit_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'relayed' => 'Relayé SGI',
            'done' => 'Traité',
            'cancelled' => 'Annulé',
            default => $this->status,
        };
    }
}
