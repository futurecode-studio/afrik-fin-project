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
        'price_label',
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

    public function catalogItems()
    {
        return $this->hasMany(FormationCatalogItem::class)->orderBy('display_order');
    }

    public function activeCatalogItems()
    {
        return $this->catalogItems()->where('is_active', true);
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
     * Formation sans prix fixe en ligne (ex. catalogue partenaire).
     */
    public function isCatalogOnly(): bool
    {
        return filled($this->price_label);
    }

    /**
     * Libellé prix pour l'affichage public.
     */
    public function priceDisplay(): string
    {
        if ($this->isCatalogOnly()) {
            return (string) $this->price_label;
        }

        if ($this->isFree()) {
            return 'Gratuit';
        }

        return number_format((float) $this->prix, 0, ',', ' ').' FCFA';
    }

    /**
     * Vérifier si la formation est gratuite
     */
    public function isFree(): bool
    {
        return (bool) $this->is_free;
    }

    public function getImageUrlAttribute(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (preg_match('#^https?://#i', $value) || str_starts_with($value, '//')) {
            return $value;
        }

        return asset(ltrim($value, '/'));
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
        return $query->where('is_free', true);
    }

    /**
     * Scope pour les formations payantes
     */
    public function scopePayante($query)
    {
        return $query->where('is_free', false)
            ->whereNull('price_label')
            ->where('prix', '>', 0);
    }

    /**
     * Relation avec les inscriptions
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relation avec les utilisateurs inscrits (via enrollments)
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot(['status', 'amount_paid', 'enrolled_at', 'completed_at', 'progress'])
            ->withTimestamps();
    }

    /**
     * Nombre d'étudiants inscrits (actifs)
     */
    public function getStudentsCountAttribute()
    {
        return $this->enrollments()->whereIn('status', ['active', 'completed'])->count();
    }

    /**
     * Relation avec les paiements
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
