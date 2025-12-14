<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_module_id',
        'titre',
        'description',
        'duree_minutes',
        'score_minimum',
        'tentatives_max',
        'is_active',
        'afficher_corrections',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'afficher_corrections' => 'boolean',
    ];

    /**
     * Relation avec le module
     */
    public function module()
    {
        return $this->belongsTo(FormationModule::class, 'formation_module_id');
    }

    /**
     * Relation avec les questions
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('ordre');
    }

    /**
     * Relation avec les tentatives des utilisateurs
     */
    public function attempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }

    /**
     * Nombre total de questions
     */
    public function getQuestionsCountAttribute()
    {
        return $this->questions()->count();
    }

    /**
     * Points total du quiz
     */
    public function getTotalPointsAttribute()
    {
        return $this->questions()->sum('points');
    }

    /**
     * Vérifier si un utilisateur a réussi le quiz
     */
    public function isPassedByUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('is_passed', true)
            ->exists();
    }

    /**
     * Nombre de tentatives d'un utilisateur
     */
    public function attemptsCountByUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Vérifier si l'utilisateur peut encore tenter le quiz
     */
    public function canAttempt($userId)
    {
        $attempts = $this->attemptsCountByUser($userId);
        return $attempts < $this->tentatives_max;
    }

    /**
     * Scope pour les quiz actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
