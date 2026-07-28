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
        'transcript',
        'consigne',
        'video_url',
        'audio_url',
        'pdf_url',
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

    public const TYPES = ['texte', 'video', 'audio', 'pdf', 'mixte', 'exercice'];

    public function isVideo(): bool
    {
        return in_array($this->type, ['video', 'mixte'], true) && ! empty($this->video_url);
    }

    public function isAudio(): bool
    {
        return $this->type === 'audio' || ! empty($this->audio_url);
    }

    public function isPdf(): bool
    {
        return $this->type === 'pdf' || ! empty($this->pdf_url);
    }

    public function isExercise(): bool
    {
        return $this->type === 'exercice';
    }

    public function icon(): string
    {
        return match ($this->type) {
            'video' => 'play_circle',
            'audio' => 'headphones',
            'pdf' => 'picture_as_pdf',
            'exercice' => 'assignment',
            'mixte' => 'layers',
            default => 'description',
        };
    }

    public function mediaUrl(): ?string
    {
        return match ($this->type) {
            'audio' => $this->audio_url,
            'pdf' => $this->pdf_url,
            'video', 'mixte' => $this->video_url,
            default => $this->video_url ?: $this->audio_url ?: $this->pdf_url,
        };
    }

    /**
     * Contenu pédagogique prêt pour l'affichage (HTML autorisé limité ou texte échappé).
     */
    public function renderedContent(): string
    {
        $raw = $this->contenu ?: $this->description;
        if (! filled($raw)) {
            return '';
        }

        $raw = (string) $raw;
        if (preg_match('/<\/?[a-z][\s\S]*>/i', $raw) || str_contains($raw, '&lt;')) {
            return rich_html($raw);
        }

        return nl2br(e($raw));
    }

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
