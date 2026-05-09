<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id', 'user_id', 'registration_id', 'order_number',
        'subtotal', 'tax', 'total', 'currency', 'status',
        'payment_provider', 'payment_transaction_id', 'paid_at', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function items()
    {
        return $this->hasMany(EventOrderItem::class, 'order_id');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['paid','processing','shipped','delivered']);
    }
}
