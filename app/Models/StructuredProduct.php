<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructuredProduct extends Model
{
    protected $fillable = [
        'name', 'slug', 'isin', 'mnemonic', 'product_type', 'underlying',
        'current_price', 'variation_percent', 'strike', 'barrier', 'cap',
        'distance_to_barrier_pct', 'coupon_memorized', 'maturity_date',
        'next_autocall_date', 'autocall_threshold_pct', 'risk_level',
        'description', 'is_published',
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'variation_percent' => 'decimal:2',
        'strike' => 'decimal:2',
        'barrier' => 'decimal:2',
        'cap' => 'decimal:2',
        'distance_to_barrier_pct' => 'decimal:2',
        'coupon_memorized' => 'decimal:2',
        'autocall_threshold_pct' => 'decimal:2',
        'maturity_date' => 'date',
        'next_autocall_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
