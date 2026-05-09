<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventWaitlist extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'user_id', 'email', 'phone', 'status', 'position', 'notified_at'];

    protected $casts = [
        'notified_at' => 'datetime',
        'position' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting')->orderBy('position');
    }
}
