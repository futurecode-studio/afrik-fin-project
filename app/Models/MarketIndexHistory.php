<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Historique quotidien des indices boursiers (BRVM Composite, BRVM 30, etc.)
 * Alimenté par la commande artisan `brvm:snapshot`.
 */
class MarketIndexHistory extends Model
{
    use HasFactory;

    protected $table = 'market_index_history';

    protected $fillable = [
        'index_name',
        'snapshot_date',
        'value',
        'variation_percent',
        'source',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'value' => 'decimal:4',
        'variation_percent' => 'decimal:4',
    ];

    /**
     * Scope : récupérer l'historique d'un indice sur N jours.
     */
    public function scopeForIndex($query, string $name, int $days = 30)
    {
        return $query
            ->where('index_name', $name)
            ->where('snapshot_date', '>=', now()->subDays($days)->toDateString())
            ->orderBy('snapshot_date');
    }
}
