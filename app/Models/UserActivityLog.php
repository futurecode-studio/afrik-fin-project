<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class UserActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'formation_id',
        'enrollment_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public const LOGIN = 'login';

    public const LOGOUT = 'logout';

    public const LESSON_VIEW = 'lesson_view';

    public const LESSON_COMPLETE = 'lesson_complete';

    public const QUIZ_SUBMIT = 'quiz_submit';

    public const EXERCISE_SUBMIT = 'exercise_submit';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public static function record(
        int $userId,
        string $action,
        ?string $description = null,
        ?int $formationId = null,
        ?int $enrollmentId = null,
        ?array $meta = null
    ): self {
        return static::create([
            'user_id' => $userId,
            'formation_id' => $formationId,
            'enrollment_id' => $enrollmentId,
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 500),
            'meta' => $meta,
            'created_at' => now(),
        ]);
    }

    public function label(): string
    {
        return match ($this->action) {
            self::LOGIN => 'Connexion',
            self::LOGOUT => 'Déconnexion',
            self::LESSON_VIEW => 'Consultation leçon',
            self::LESSON_COMPLETE => 'Leçon terminée',
            self::QUIZ_SUBMIT => 'Quiz soumis',
            self::EXERCISE_SUBMIT => 'Exercice soumis',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    public function icon(): string
    {
        return match ($this->action) {
            self::LOGIN => 'login',
            self::LOGOUT => 'logout',
            self::LESSON_VIEW => 'visibility',
            self::LESSON_COMPLETE => 'check_circle',
            self::QUIZ_SUBMIT => 'quiz',
            self::EXERCISE_SUBMIT => 'upload_file',
            default => 'history',
        };
    }
}
