<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiIntegration extends Model
{
    protected $fillable = [
        'provider',
        'label',
        'is_enabled',
        'sandbox',
        'credentials',
        'meta',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'sandbox' => 'boolean',
        'credentials' => 'encrypted:array',
        'meta' => 'array',
    ];

    public function credential(string $key, mixed $default = null): mixed
    {
        $value = $this->credentials[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public function hasCredential(string $key): bool
    {
        $value = $this->credentials[$key] ?? null;

        return $value !== null && $value !== '';
    }
}
