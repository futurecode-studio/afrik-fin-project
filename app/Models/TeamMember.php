<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'poste',
        'attributs',
        'description',
        'contact',
        'email',
        'photo',
        'is_active',
        'is_leadership',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_leadership' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeLeadership($query)
    {
        return $query->where('is_leadership', true);
    }

    public function getPhotoUrlAttribute()
    {
        if (! $this->photo) {
            return null;
        }

        // storeAs('team', …) enregistre déjà « team/fichier.ext »
        $path = str_starts_with($this->photo, 'team/')
            ? $this->photo
            : 'team/'.$this->photo;

        return asset('storage/'.$path);
    }

    public function getAttributsArrayAttribute()
    {
        if ($this->attributs) {
            return array_map('trim', explode(',', $this->attributs));
        }
        return [];
    }
}