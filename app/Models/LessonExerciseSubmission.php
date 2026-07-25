<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonExerciseSubmission extends Model
{
    protected $fillable = [
        'user_id', 'enrollment_id', 'module_lesson_id',
        'answer_text', 'file_path', 'file_name', 'status',
        'score', 'max_score', 'feedback', 'annotated_file_path',
        'submitted_at', 'corrected_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'submitted_at' => 'datetime',
        'corrected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(ModuleLesson::class, 'module_lesson_id');
    }

    public function isCorrected(): bool
    {
        return $this->status === 'corrected';
    }

    public function isPassed(): bool
    {
        if ($this->score === null) {
            return false;
        }

        return (float) $this->score >= ((float) $this->max_score * 0.7);
    }
}
