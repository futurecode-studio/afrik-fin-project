<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';

    protected $fillable = [
        'user_id',
        'module_lesson_id',
        'enrollment_id',
        'video_position',
        'watched_seconds',
        'duration_seconds',
        'last_watched_at',
    ];

    protected $casts = [
        'last_watched_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(ModuleLesson::class, 'module_lesson_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public static function upsertPosition(
        int $userId,
        int $lessonId,
        int $position,
        ?int $enrollmentId = null,
        ?int $duration = null
    ): self {
        $row = static::firstOrNew([
            'user_id' => $userId,
            'module_lesson_id' => $lessonId,
        ]);
        $row->enrollment_id = $enrollmentId ?? $row->enrollment_id;
        $row->video_position = max(0, $position);
        $row->watched_seconds = max((int) $row->watched_seconds, $position);
        if ($duration !== null) {
            $row->duration_seconds = $duration;
        }
        $row->last_watched_at = now();
        $row->save();

        return $row;
    }
}
