<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id', 'user_id', 'ticket_type_id', 'first_name', 'last_name',
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

    public function ticketType()
    {
        return $this->belongsTo(EventTicketType::class);
    }

    public function checkIn()
    {
        return $this->hasOne(EventCheckIn::class, 'registration_id');
    }

    public function order()
    {
        return $this->hasOne(EventOrder::class, 'registration_id');
    }

    public function scopeConfirmed($query)
    {
        return $query->whereIn('status', ['registered','confirmed','checked_in']);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled','no_show']);
    }

    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in' && $this->checked_in_at !== null;
    }

    public function fullName(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
