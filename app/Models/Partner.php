<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Partner extends Model
{
    use HasFactory;

    const TYPES = [
        'SGO' => "Sociétés de Gestion d'OPCVM (SGO)",
        'SGI' => "Sociétés de Gestion et d'Intermédiation (SGI)",
        'Autre' => "Autres Partenaires",
    ];

    /**
     * Logos officiels affichés partout sur le site.
     *
     * @var array<int, array<string, mixed>>
     */
    public const CATALOG = [
        ['nom' => 'AASCOT', 'type' => 'SGI', 'logo' => 'assets/images/AASCOT.png', 'order' => 1],
        ['nom' => 'Africa Bourse', 'type' => 'SGI', 'logo' => 'assets/images/africa-bourse.png', 'order' => 2],
        ['nom' => 'AGA', 'type' => 'SGO', 'logo' => 'assets/images/aga.png', 'order' => 3],
        ['nom' => 'AGI', 'type' => 'SGO', 'logo' => 'assets/images/agi.png', 'order' => 4],
        ['nom' => 'BOA', 'type' => 'SGI', 'logo' => 'assets/images/boa.png', 'order' => 5],
        ['nom' => 'BFS', 'type' => 'SGI', 'logo' => 'assets/images/bfs.png', 'order' => 6],
        ['nom' => 'NSIA', 'type' => 'SGO', 'logo' => 'assets/images/nsia.png', 'order' => 7],
        ['nom' => 'Saphir', 'type' => 'SGO', 'logo' => 'assets/images/saphir.png', 'order' => 8],
        ['nom' => 'SOAGA', 'type' => 'SGO', 'logo' => 'assets/images/soaga.png', 'order' => 9],
        ['nom' => 'UCA', 'type' => 'SGI', 'logo' => 'assets/images/uca.png', 'order' => 10],
    ];

    protected $fillable = [
        'nom',
        'type',
        'country',
        'city',
        'agreement_number',
        'contact',
        'email',
        'website',
        'logo',
        'description',
        'admin_notes',
        'is_active',
        'is_featured',
        'order',
    ];

    protected $appends = [
        'logo_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeSgi($query)
    {
        return $query->where('type', 'SGI');
    }

    public function scopeSgo($query)
    {
        return $query->where('type', 'SGO');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getLogoUrlAttribute(): ?string
    {
        return self::urlForLogo($this->logo ?: self::catalogLogoForName($this->nom));
    }

    public function getLogoUrl(): string
    {
        return $this->logo_url ?? '';
    }

    public static function urlForLogo(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'assets/') || str_starts_with($path, '/assets/')) {
            return asset(ltrim($path, '/'));
        }

        return asset('storage/'.$path);
    }

    public static function catalogLogoForName(?string $name): ?string
    {
        if (! $name) {
            return null;
        }

        $needle = self::normalizeName($name);

        foreach (self::CATALOG as $entry) {
            if (self::normalizeName($entry['nom']) === $needle) {
                return $entry['logo'];
            }
        }

        foreach (self::CATALOG as $entry) {
            $label = self::normalizeName($entry['nom']);
            if ($label !== '' && (str_contains($needle, $label) || str_contains($label, $needle))) {
                return $entry['logo'];
            }
        }

        return null;
    }

    /**
     * @return Collection<int, Partner>
     */
    public static function catalogCollection(): Collection
    {
        return collect(self::CATALOG)->map(function (array $entry) {
            return new self([
                'nom' => $entry['nom'],
                'type' => $entry['type'],
                'logo' => $entry['logo'],
                'website' => $entry['website'] ?? null,
                'description' => $entry['description'] ?? null,
                'is_active' => true,
                'order' => $entry['order'] ?? 0,
            ]);
        });
    }

    private static function normalizeName(string $name): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/', ' ', $name) ?? $name));
    }
}
