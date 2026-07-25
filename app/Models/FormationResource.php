<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationResource extends Model
{
    protected $fillable = [
        'formation_id', 'formation_module_id', 'title', 'type', 'url', 'file_size', 'sort_order', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function formation(): BelongsTo { return $this->belongsTo(Formation::class); }
    public function module(): BelongsTo { return $this->belongsTo(FormationModule::class, 'formation_module_id'); }

    public function scopePublished($q) { return $q->where('is_published', true); }
}
