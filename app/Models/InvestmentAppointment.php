<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investment_type',
        'name',
        'email',
        'phone',
        'company',
        'investment_amount',
        'message',
        'status',
        'preferred_date',
        'confirmed_date',
        'admin_notes',
    ];

    protected $casts = [
        'investment_amount' => 'decimal:2',
        'preferred_date' => 'datetime',
        'confirmed_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getInvestmentTypeLabel(): string
    {
        return match($this->investment_type) {
            'actions_brvm' => 'Actions BRVM',
            'obligations' => 'Obligations d\'États',
            'fcp' => 'Fonds Communs de Placement',
            'gestion_mandat' => 'Gestion sous mandat',
            'institutionnel' => 'Portail institutionnel',
            'mise_en_relation' => 'Mise en relation partenaire',
            default => $this->investment_type,
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'completed' => 'Complété',
            'cancelled' => 'Annulé',
            default => $this->status,
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
