<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_quiz_id',
        'question',
        'type',
        'explication',
        'points',
        'ordre',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec le quiz
     */
    public function quiz()
    {
        return $this->belongsTo(ModuleQuiz::class, 'module_quiz_id');
    }

    /**
     * Relation avec les réponses
     */
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class)->orderBy('ordre');
    }

    /**
     * Obtenir les bonnes réponses
     */
    public function correctAnswers()
    {
        return $this->answers()->where('is_correct', true);
    }

    /**
     * Vérifier si une réponse est correcte
     */
    public function isCorrectAnswer($answerId)
    {
        return $this->answers()
            ->where('id', $answerId)
            ->where('is_correct', true)
            ->exists();
    }

    /**
     * Vérifier si les réponses données sont correctes (pour choix multiple)
     */
    public function checkAnswers(array $answerIds)
    {
        $correctIds = $this->correctAnswers()->pluck('id')->toArray();
        
        if ($this->type === 'choix_multiple') {
            // Pour choix multiple, toutes les bonnes réponses doivent être sélectionnées
            // et aucune mauvaise réponse ne doit être sélectionnée
            sort($correctIds);
            sort($answerIds);
            return $correctIds === $answerIds;
        }
        
        // Pour choix unique ou vrai/faux
        return count($answerIds) === 1 && in_array($answerIds[0], $correctIds);
    }

    /**
     * Scope pour les questions actives
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
