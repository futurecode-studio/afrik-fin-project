<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDocument extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'title', 'file_path', 'file_type', 'file_size', 'is_downloadable', 'display_order'];

    protected $casts = [
        'is_downloadable' => 'boolean',
        'file_size' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getDownloadUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
