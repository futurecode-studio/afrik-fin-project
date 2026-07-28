<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    const TYPES = [
        'SGO' => "Sociétés de Gestion d'OPCVM (SGO)",
        'SGI' => "Sociétés de Gestion et d'Intermédiation (SGI)",
        'Autre' => "Autres Partenaires",
    ];

    protected $fillable = [
        'nom',
        'type',
        'country',
        'city',
        'agreement_number',
        'contact',
        'email',
        'website',
        'logo',
        'description',
        'admin_notes',
        'is_active',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeSgi($query)
    {
        return $query->where('type', 'SGI');
    }

    public function scopeSgo($query)
    {
        return $query->where('type', 'SGO');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    public function getLogoUrl(): string
    {
        return $this->logo_url ?? $this->getLogoUrlAttribute();
    }
}