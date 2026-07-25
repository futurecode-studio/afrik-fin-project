<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgVote extends Model
{
    protected $fillable = ['user_id', 'ag_resolution_id', 'choice'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolution(): BelongsTo
    {
        return $this->belongsTo(AgResolution::class, 'ag_resolution_id');
    }
}
