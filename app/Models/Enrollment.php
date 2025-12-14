<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'formation_id',
        'status',
        'amount_paid',
        'enrolled_at',
        'completed_at',
        'progress',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'progress' => 'integer',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la formation
     */
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Relation avec les paiements
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Vérifier si l'inscription est active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Vérifier si l'inscription est en attente de paiement
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Scope pour les inscriptions actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour les inscriptions en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Activer l'inscription après paiement
     */
    public function activate()
    {
        $this->update([
            'status' => 'active',
            'enrolled_at' => now(),
        ]);
    }
}
