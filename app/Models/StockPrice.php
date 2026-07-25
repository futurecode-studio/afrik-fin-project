<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'price',
        'open',
        'high',
        'low',
        'volume',
        'change_amount',
        'change_percent',
        'recorded_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'open' => 'decimal:2',
        'high' => 'decimal:2',
        'low' => 'decimal:2',
        'change_amount' => 'decimal:4',
        'change_percent' => 'decimal:4',
        'recorded_at' => 'datetime',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
