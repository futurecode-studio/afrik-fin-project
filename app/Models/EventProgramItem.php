<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProgramItem extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'title', 'description', 'starts_at', 'ends_at', 'display_order', 'location_detail'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getStartsAtFormattedAttribute(): string
    {
        return $this->formatTimeValue($this->attributes['starts_at'] ?? null);
    }

    public function getEndsAtFormattedAttribute(): string
    {
        return $this->formatTimeValue($this->attributes['ends_at'] ?? null);
    }

    private function formatTimeValue($value): string
    {
        if (!$value) {
            return '';
        }

        return substr((string) $value, 0, 5);
    }
}
