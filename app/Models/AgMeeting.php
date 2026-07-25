<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgMeeting extends Model
{
    protected $fillable = [
        'stock_id', 'company_name', 'title', 'closes_at', 'location',
        'quorum_percent', 'report_url', 'is_published',
    ];

    protected $casts = [
        'closes_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function resolutions(): HasMany
    {
        return $this->hasMany(AgResolution::class)->orderBy('sort_order')->orderBy('number');
    }
}
