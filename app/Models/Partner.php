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