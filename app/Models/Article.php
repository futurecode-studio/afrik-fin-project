<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titre',
        'slug',
        'extrait',
        'contenu',
        'image_url',
        'categorie',
        'statut',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->titre);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('titre') && empty($article->slug)) {
                $article->slug = Str::slug($article->titre);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('statut', 'publie')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
