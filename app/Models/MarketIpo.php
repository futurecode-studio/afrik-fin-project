<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketIpo extends Model
{
    protected $fillable = [
        'company_name', 'symbol', 'sector', 'exchange', 'status',
        'offer_price_min', 'offer_price_max', 'shares_offered',
        'subscription_start', 'subscription_end', 'listing_date',
        'description', 'prospectus_url', 'is_published',
    ];

    protected $casts = [
        'offer_price_min' => 'decimal:2',
        'offer_price_max' => 'decimal:2',
        'subscription_start' => 'date',
        'subscription_end' => 'date',
        'listing_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'annonce' => 'Annoncée',
            'souscription' => 'Souscription ouverte',
            'cloture' => 'Clôturée',
            'cote' => 'Cotée',
            default => $this->status,
        };
    }
}
