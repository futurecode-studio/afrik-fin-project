<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'enrollment_id',
        'formation_id',
        'transaction_id',
        'reference',
        'amount',
        'currency',
        'provider',
        'status',
        'provider_response',
        'payment_method',
        'phone',
        'failure_reason',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'provider_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Boot method pour générer la référence automatiquement
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->reference)) {
                $payment->reference = 'PAY-' . strtoupper(Str::random(10)) . '-' . time();
            }
        });
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'inscription
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Relation avec la formation
     */
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Vérifier si le paiement est complété
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Vérifier si le paiement est en attente
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Marquer le paiement comme complété
     */
    public function markAsCompleted($transactionId, $providerResponse = null)
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'provider_response' => $providerResponse,
            'paid_at' => now(),
        ]);

        // Activer l'inscription associée
        if ($this->enrollment) {
            $this->enrollment->activate();
        }
    }

    /**
     * Marquer le paiement comme échoué
     */
    public function markAsFailed($reason = null, $providerResponse = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'provider_response' => $providerResponse,
        ]);
    }

    /**
     * Scope pour les paiements complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope pour les paiements par provider
     */
    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }
}
