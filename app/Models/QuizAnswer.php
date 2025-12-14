<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_question_id',
        'reponse',
        'is_correct',
        'ordre',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Relation avec la question
     */
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}
