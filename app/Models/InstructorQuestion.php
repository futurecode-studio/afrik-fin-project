<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorQuestion extends Model
{
    protected $fillable = [
        'user_id', 'formation_id', 'subject', 'body', 'status', 'answer', 'answered_at',
    ];

    protected $casts = ['answered_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function formation(): BelongsTo { return $this->belongsTo(Formation::class); }
}
