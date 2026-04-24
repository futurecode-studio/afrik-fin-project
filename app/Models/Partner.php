<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'contact',
        'email',
        'website',
        'logo',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/partners/' . $this->logo);
        }
        return null;
    }

    public function getLogoUrl(): string
    {
        return $this->logo_url ?? $this->getLogoUrlAttribute();
    }
}