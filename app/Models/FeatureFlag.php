<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FeatureFlag extends Model
{
    protected $fillable = [
        'key', 'label', 'description', 'enabled', 'group', 'sort_order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function isEnabled(string $key, bool $default = false): bool
    {
        $flags = Cache::remember('feature_flags.map', 60, function () {
            return static::query()->pluck('enabled', 'key')->all();
        });

        if (! array_key_exists($key, $flags)) {
            return $default;
        }

        return (bool) $flags[$key];
    }

    public static function forgetCache(): void
    {
        Cache::forget('feature_flags.map');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::forgetCache());
        static::deleted(fn () => static::forgetCache());
    }
}
