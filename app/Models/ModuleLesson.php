<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModuleLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_module_id',
        'titre',
        'slug',
        'description',
        'contenu',
        'video_url',
        'duree_estimee',
        'ordre',
        'type',
        'is_active',
        'ressources',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ressources' => 'array',
    ];

    /**
     * Boot du modèle pour générer le slug automatiquement
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->titre);
            }
        });
    }

    /**
     * Relation avec le module
     */
    public function module()
    {
        return $this->belongsTo(FormationModule::class, 'formation_module_id');
    }

    /**
     * Obtenir la formation via le module
     */
    public function getFormationAttribute()
    {
        return $this->module->formation;
    }

    /**
     * Vérifier si la leçon a une vidéo
     */
    public function hasVideo()
    {
        return !empty($this->video_url);
    }

    /**
     * Obtenir l'ID YouTube de la vidéo
     */
    public function getYoutubeIdAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches);
        
        return $matches[1] ?? null;
    }

    /**
     * Scope pour les leçons actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour trier par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }
}
