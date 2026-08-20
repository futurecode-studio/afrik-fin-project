<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id', 'user_id', 'first_name', 'last_name',
        'email', 'phone', 'institution_name', 'job_title', 't_shirt_size',
        'medical_notes', 'emergency_contact_name', 'emergency_contact_phone',
        'status', 'qr_code', 'checked_in_at', 'cancelled_at', 'cancellation_reason', 'source',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkIn()
    {
        return $this->hasOne(EventCheckIn::class, 'registration_id');
    }

    public function scopeConfirmed($query)
    {
        return $query->whereIn('status', ['confirmed', 'checked_in']);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'checked_in']);
    }

    public function scopeAwaitingPayment($query)
    {
        return $query->whereIn('status', ['pending_payment', 'registered']);
    }

    public function isConfirmed(): bool
    {
        return in_array($this->status, ['confirmed', 'checked_in'], true);
    }

    public function isAwaitingPayment(): bool
    {
        return in_array($this->status, ['pending_payment', 'registered'], true);
    }

    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in' && $this->checked_in_at !== null;
    }

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_payment', 'registered' => 'Paiement en attente',
            'confirmed' => 'Confirmé',
            'checked_in' => 'Présent',
            'cancelled' => 'Annulé',
            'no_show' => 'Absent',
            default => $this->status,
        };
    }

    public function statusColorClasses(): string
    {
        return match ($this->status) {
            'pending_payment', 'registered' => 'bg-amber-100 text-amber-900',
            'confirmed' => 'bg-emerald-100 text-emerald-800',
            'checked_in' => 'bg-primary/10 text-primary',
            'cancelled' => 'bg-red-100 text-red-800',
            'no_show' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
