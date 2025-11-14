<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titre',
        'slug',
        'description_courte',
        'description_complete',
        'niveau',
        'duree',
        'prix',
        'image_url',
        'statut',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'prix' => 'decimal:2',
    ];

    /**
     * Relation avec l'utilisateur (auteur de la formation)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer les formations publiées
     */
    public function scopePublie($query)
    {
        return $query->where('statut', 'publie');
    }

    /**
     * Scope pour filtrer par niveau
     */
    public function scopeNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }
}
