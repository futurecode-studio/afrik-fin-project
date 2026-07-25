<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgResolution extends Model
{
    protected $fillable = [
        'ag_meeting_id', 'number', 'title', 'kind', 'description', 'sort_order',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(AgMeeting::class, 'ag_meeting_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(AgVote::class);
    }
}
