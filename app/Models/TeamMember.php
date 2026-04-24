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

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/team/' . $this->photo);
        }
        return null;
    }

    public function getAttributsArrayAttribute()
    {
        if ($this->attributs) {
            return array_map('trim', explode(',', $this->attributs));
        }
        return [];
    }
}