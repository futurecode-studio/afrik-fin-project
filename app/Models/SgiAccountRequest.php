<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SgiAccountRequest extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'source',
        'status',
        'admin_notes',
        'contacted_at',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'contacted' => 'Contacté',
            'in_progress' => 'En cours',
            'done' => 'Compte créé',
            'cancelled' => 'Annulé',
            default => $this->status,
        };
    }

    public function sourceLabel(): string
    {
        return match ($this->source) {
            'carnet' => 'Carnet d’ordres',
            'ordres' => 'Espace client',
            default => $this->source,
        };
    }
}
