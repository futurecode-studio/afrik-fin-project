<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentBond extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'issuer',
        'country',
        'isin_code',
        'external_code',
        'nominal_value',
        'currency',
        'interest_rate',
        'interest_type',
        'payment_frequency',
        'issue_date',
        'auction_date',
        'maturity_date',
        'maturity_years',
        'current_price',
        'yield_to_maturity',
        'rating',
        'description',
        'data_source',
        'source_url',
        'last_synced_at',
        'risk_level',
        'minimum_investment',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'auction_date' => 'date',
        'maturity_date' => 'date',
        'last_synced_at' => 'datetime',
        'is_active' => 'boolean',
        'nominal_value' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'current_price' => 'decimal:2',
        'yield_to_maturity' => 'decimal:2',
        'minimum_investment' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('maturity_date');
    }

    /**
     * Masque automatiquement les obligations dont l'échéance est passée.
     */
    public function scopeNotMatured($query)
    {
        return $query->where('maturity_date', '>=', now()->toDateString());
    }

    /**
     * Obligations émises dans les 60 derniers jours (en cours de souscription / récentes).
     */
    public function scopeRecent($query, int $days = 60)
    {
        return $query->where('issue_date', '>=', now()->subDays($days)->toDateString());
    }

    public function getRiskLevelLabelAttribute(): string
    {
        return match ($this->risk_level) {
            'low' => 'Faible',
            'medium' => 'Moyen',
            'high' => 'Élevé',
            default => ucfirst($this->risk_level),
        };
    }

    public function getInterestTypeLabelAttribute(): string
    {
        return match ($this->interest_type) {
            'fixed' => 'Fixe',
            'variable' => 'Variable',
            'zero_coupon' => 'Zéro coupon',
            default => ucfirst($this->interest_type),
        };
    }

    public function getPaymentFrequencyLabelAttribute(): string
    {
        return match ($this->payment_frequency) {
            'annual' => 'Annuel',
            'semi_annual' => 'Semestriel',
            'quarterly' => 'Trimestriel',
            'monthly' => 'Mensuel',
            default => ucfirst(str_replace('_', ' ', $this->payment_frequency)),
        };
    }

    public function getRemainingYearsAttribute(): float
    {
        $now = now();
        $maturity = $this->maturity_date;
        
        if ($maturity->isPast()) {
            return 0;
        }
        
        return round($now->diffInDays($maturity) / 365, 1);
    }
}
