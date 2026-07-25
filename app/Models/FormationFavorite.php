<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationFavorite extends Model
{
    protected $fillable = ['user_id', 'module_lesson_id', 'article_id', 'label'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function lesson(): BelongsTo { return $this->belongsTo(ModuleLesson::class, 'module_lesson_id'); }
    public function article(): BelongsTo { return $this->belongsTo(Article::class); }
}
