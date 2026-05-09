<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'variant_id', 'product_name', 'unit_price', 'quantity', 'total_price'];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(EventOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(EventProduct::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(EventProductVariant::class, 'variant_id');
    }
}
