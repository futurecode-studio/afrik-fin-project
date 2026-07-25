<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketIndex extends Model
{
    use HasFactory;

    protected $table = 'market_indices';

    protected $fillable = [
        'code',
        'name',
        'exchange',
        'currency',
        'value',
        'change',
        'change_percent',
        'source',
        'source_updated_at',
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'change' => 'decimal:4',
        'change_percent' => 'decimal:4',
        'source_updated_at' => 'datetime',
    ];
}
