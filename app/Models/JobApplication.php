<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position_applied',
        'city',
        'country',
        'cover_letter',
        'cv_path',
        'linkedin_url',
        'portfolio_url',
        'years_of_experience',
        'education_level',
        'current_company',
        'expected_salary',
        'availability',
        'status',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'expected_salary' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'reviewing' => 'En cours d\'examen',
            'shortlisted' => 'Présélectionné',
            'interviewed' => 'Entretien passé',
            'rejected' => 'Rejeté',
            'accepted' => 'Accepté',
            default => ucfirst($this->status),
        };
    }

    public function getAvailabilityLabelAttribute(): string
    {
        return match ($this->availability) {
            'immediate' => 'Immédiate',
            '1_month' => '1 mois',
            '2_months' => '2 mois',
            '3_months' => '3 mois ou plus',
            default => $this->availability,
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewing($query)
    {
        return $query->where('status', 'reviewing');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }
}
