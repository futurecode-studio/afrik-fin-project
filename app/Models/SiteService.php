<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SiteService extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'subtitle',
        'excerpt',
        'content',
        'features',
        'price_label',
        'duration_label',
        'image_url',
        'cta_label',
        'cta_url',
        'is_active',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (SiteService $service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order')->orderBy('title');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
