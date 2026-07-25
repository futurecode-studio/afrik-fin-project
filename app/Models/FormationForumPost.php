<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormationForumPost extends Model
{
    protected $fillable = [
        'formation_id', 'user_id', 'module_lesson_id', 'parent_id',
        'title', 'body', 'is_pinned',
    ];

    protected $casts = ['is_pinned' => 'boolean'];

    public function formation(): BelongsTo { return $this->belongsTo(Formation::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function lesson(): BelongsTo { return $this->belongsTo(ModuleLesson::class, 'module_lesson_id'); }
    public function parent(): BelongsTo { return $this->belongsTo(self::class, 'parent_id'); }
    public function replies(): HasMany { return $this->hasMany(self::class, 'parent_id')->latest(); }
}
