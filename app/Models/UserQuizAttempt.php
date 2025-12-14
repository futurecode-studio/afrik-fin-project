<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module_quiz_id',
        'score',
        'points_obtenus',
        'points_total',
        'reponses',
        'is_passed',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'reponses' => 'array',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le quiz
     */
    public function quiz()
    {
        return $this->belongsTo(ModuleQuiz::class, 'module_quiz_id');
    }

    /**
     * Durée de la tentative en minutes
     */
    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->completed_at);
    }

    /**
     * Vérifier si la tentative est terminée
     */
    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }
}
