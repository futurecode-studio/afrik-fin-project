<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'sku', 'variant_name', 'price', 'size', 'color', 'stock_quantity', 'reserved_quantity'];

    protected $casts = [
        'stock_quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(EventProduct::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(EventOrderItem::class, 'variant_id');
    }

    public function availableQuantity(): int
    {
        return max(0, $this->stock_quantity - $this->reserved_quantity);
    }

    public function isAvailable(int $qty = 1): bool
    {
        return $this->availableQuantity() >= $qty;
    }

    public function effectivePrice(): float
    {
        return (float) ($this->price ?? $this->product?->price ?? 0);
    }
}
