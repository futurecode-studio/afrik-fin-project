<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationReview extends Model
{
    protected $fillable = [
        'user_id', 'formation_id', 'rating_overall', 'rating_content',
        'rating_instructor', 'rating_difficulty', 'rating_materials', 'comment',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function formation(): BelongsTo { return $this->belongsTo(Formation::class); }
}
