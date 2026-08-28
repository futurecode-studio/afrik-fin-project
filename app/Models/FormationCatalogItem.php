<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationCatalogItem extends Model
{
    protected $fillable = [
        'formation_id',
        'image_path',
        'title',
        'caption',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (preg_match('#^https?://#i', $this->image_path) || str_starts_with($this->image_path, '//')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'assets/')) {
            return asset($this->image_path);
        }

        return asset('storage/'.$this->image_path);
    }
}
