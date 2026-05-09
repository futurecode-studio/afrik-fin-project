<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProgramItem extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'title', 'description', 'starts_at', 'ends_at', 'display_order', 'location_detail'];

    protected $casts = [
        'starts_at' => 'datetime:H:i',
        'ends_at' => 'datetime:H:i',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
