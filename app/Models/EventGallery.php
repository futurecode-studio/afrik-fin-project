<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGallery extends Model
{
    use HasFactory;

    protected $table = 'event_galleries';

    protected $fillable = ['event_id', 'image_path', 'caption', 'is_featured', 'display_order'];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'assets/')) {
            return asset($this->image_path);
        }

        return asset('storage/' . $this->image_path);
    }
}
