<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormationModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id',
        'titre',
        'slug',
        'description',
        'ordre',
        'duree_estimee',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot du modèle pour générer le slug automatiquement
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($module) {
            if (empty($module->slug)) {
                $module->slug = Str::slug($module->titre);
            }
        });
    }

    /**
     * Relation avec la formation
     */
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Relation avec les leçons
     */
    public function lessons()
    {
        return $this->hasMany(ModuleLesson::class)->orderBy('ordre');
    }

    /**
     * Relation avec le quiz
     */
    public function quiz()
    {
        return $this->hasOne(ModuleQuiz::class);
    }

    /**
     * Nombre total de leçons
     */
    public function getLessonsCountAttribute()
    {
        return $this->lessons()->count();
    }

    /**
     * Durée totale estimée du module
     */
    public function getTotalDurationAttribute()
    {
        return $this->duree_estimee ?? 'Non définie';
    }

    /**
     * Vérifier si le module a un quiz
     */
    public function hasQuiz()
    {
        return $this->quiz()->exists();
    }

    /**
     * Scope pour les modules actifs
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
