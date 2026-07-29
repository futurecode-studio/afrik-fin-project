<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SgiRequiredDocument extends Model
{
    protected $fillable = [
        'title',
        'description',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order')->orderBy('id');
    }
}
