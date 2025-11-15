<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Les attributs assignables en masse.
     */
    protected $fillable = [
        'agregateur',
        'external_transaction_id',
        'status',
        'amount',
        'currency',
        'description',
        'mode',
        'callback_url',
        'fullname',
        'phone',
        'email',
        'account',
        'person',
        'type_paiement',
        'performed_at',
        'received_at',
        'type',
        'source',
        'source_common_name',
        'fees',
        'isFeesBorneByMerchant',
        'net',
        'paymentlink',
        'country',
        'reason',
        'state',
        'before_balance',
        'after_balance',
        'is_payout',
        'is_counted',
        'wallet',
        'meta_data',
        'isNewGeneration',
        'transactionId',
        'performedAt',
        'user_id',
        'course_id',
        'enrollment_id',
        'raw_response',
        'webhook_data',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'fees' => 'decimal:2',
        'net' => 'decimal:2',
        'before_balance' => 'decimal:2',
        'after_balance' => 'decimal:2',
        'isFeesBorneByMerchant' => 'boolean',
        'is_payout' => 'boolean',
        'is_counted' => 'boolean',
        'isNewGeneration' => 'boolean',
        'performed_at' => 'datetime',
        'received_at' => 'datetime',
        'performedAt' => 'datetime',
    ];

    /**
     * Statuts possibles pour une transaction
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_CANCELED = 'canceled';
    const STATUS_DECLINED = 'declined';
    const STATUS_FAILED = 'failed';

    /**
     * Agrégateurs de paiement supportés
     */
    const AGREGATEUR_KKIAPAY = 'kkiapay';
    const AGREGATEUR_FEDAPAY = 'fedapay';

    /**
     * Vérifier si la transaction est réussie
     */
    public function isSuccessful(): bool
    {
        return in_array($this->status, [self::STATUS_SUCCEEDED, self::STATUS_APPROVED]);
    }

    /**
     * Vérifier si la transaction est en attente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifier si la transaction a échoué
     */
    public function isFailed(): bool
    {
        return in_array($this->status, [self::STATUS_FAILED, self::STATUS_DECLINED, self::STATUS_CANCELED]);
    }

    /**
     * Calculer le montant net (après frais)
     */
    public function calculateNet(): float
    {
        $fees = $this->isFeesBorneByMerchant ? $this->fees : 0;
        return $this->amount - $fees;
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la formation (course)
     */
    public function course()
    {
        return $this->belongsTo(Formation::class, 'course_id');
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour les transactions réussies
     */
    public function scopeSuccessful($query)
    {
        return $query->whereIn('status', [self::STATUS_SUCCEEDED, self::STATUS_APPROVED]);
    }

    /**
     * Scope pour les transactions en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope pour les transactions échouées
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', [self::STATUS_FAILED, self::STATUS_DECLINED, self::STATUS_CANCELED]);
    }

    /**
     * Scope pour filtrer par agrégateur
     */
    public function scopeAgregateur($query, $agregateur)
    {
        return $query->where('agregateur', $agregateur);
    }

    /**
     * Obtenir le montant formaté
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Obtenir les frais formatés
     */
    public function getFormattedFeesAttribute(): string
    {
        return number_format($this->fees ?? 0, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Obtenir le net formaté
     */
    public function getFormattedNetAttribute(): string
    {
        return number_format($this->net ?? $this->calculateNet(), 0, ',', ' ') . ' ' . $this->currency;
    }
}
