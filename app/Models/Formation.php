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
        'is_free',
        'image_url',
        'statut',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'prix' => 'decimal:2',
        'is_free' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur (auteur de la formation)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les modules
     */
    public function modules()
    {
        return $this->hasMany(FormationModule::class)->orderBy('ordre');
    }

    /**
     * Nombre total de modules
     */
    public function getModulesCountAttribute()
    {
        return $this->modules()->count();
    }

    /**
     * Nombre total de leçons dans tous les modules
     */
    public function getTotalLessonsAttribute()
    {
        return $this->modules()->withCount('lessons')->get()->sum('lessons_count');
    }

    /**
     * Vérifier si la formation est gratuite
     */
    public function isFree()
    {
        return $this->is_free || $this->prix == 0;
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

    /**
     * Scope pour les formations gratuites
     */
    public function scopeGratuite($query)
    {
        return $query->where(function ($q) {
            $q->where('is_free', true)->orWhere('prix', 0);
        });
    }

    /**
     * Scope pour les formations payantes
     */
    public function scopePayante($query)
    {
        return $query->where('is_free', false)->where('prix', '>', 0);
    }
}
