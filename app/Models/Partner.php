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
        [
            'nom' => 'BRVM',
            'type' => 'Autre',
            'logo' => 'assets/images/brvm.jpeg',
            'website' => 'https://www.brvm.org',
            'country' => 'Côte d’Ivoire',
            'city' => 'Abidjan',
            'description' => 'Bourse Régionale des Valeurs Mobilières de l’UEMOA. Place de cotation des actions et obligations de la zone.',
            'order' => 1,
        ],
        [
            'nom' => 'Africabourse',
            'type' => 'SGI',
            'logo' => 'assets/images/africa-bourse.png',
            'website' => 'https://africabourse.com',
            'aliases' => ['Africa Bourse', 'Apicassur'],
            'description' => 'SGI agréée AMF-UMOA, membre de la BRVM. Intermédiation, conseil et ingénierie financière en UEMOA.',
            'order' => 2,
        ],
        [
            'nom' => 'AAM',
            'type' => 'SGO',
            'logo' => 'assets/images/africabource-asset-managment.png',
            'website' => 'https://africabourse-am.com',
            'country' => 'Bénin',
            'city' => 'Cotonou',
            'agreement_number' => 'SG/2012-03',
            'aliases' => ['Africabourse Asset Management', 'Africa Bourse Asset Management'],
            'description' => 'Africabourse Asset Management. Société de gestion d’OPCVM agréée AMF-UMOA.',
            'order' => 3,
        ],
        [
            'nom' => 'AAT',
            'type' => 'Autre',
            'logo' => 'assets/images/africaboursetitrisation.jpeg',
            'website' => 'https://africabourse.com',
            'country' => 'Côte d’Ivoire',
            'city' => 'Abidjan',
            'agreement_number' => 'AMF-UMOA 099-2025',
            'aliases' => ['Africatitrisation', 'Africa Titrisation'],
            'description' => 'Africatitrisation. Société de gestion de fonds communs de titrisation de créances (SG-FCTC).',
            'order' => 4,
        ],
        ['nom' => 'SOAGA', 'type' => 'SGO', 'logo' => 'assets/images/soaga.png', 'order' => 5],
        ['nom' => 'NSIA', 'type' => 'SGO', 'logo' => 'assets/images/nsia.png', 'order' => 6],
        [
            'nom' => 'SGI Bénin',
            'type' => 'SGI',
            'logo' => 'assets/images/sgi-benin.jpeg',
            'website' => 'https://sgibenin.com',
            'country' => 'Bénin',
            'city' => 'Cotonou',
            'agreement_number' => '15/12/001/97',
            'description' => 'Société de Gestion et d’Intermédiation de droit béninois, pionnière du marché financier régional depuis 1997.',
            'order' => 7,
        ],
        ['nom' => 'Saphir', 'type' => 'SGO', 'logo' => 'assets/images/saphir.png', 'order' => 8],
        ['nom' => 'AGI', 'type' => 'SGO', 'logo' => 'assets/images/agi.png', 'order' => 9],
        ['nom' => 'AGA', 'type' => 'SGO', 'logo' => 'assets/images/aga.png', 'order' => 10],
        ['nom' => 'UCA', 'type' => 'SGI', 'logo' => 'assets/images/uca.png', 'order' => 11],
        ['nom' => 'BFS', 'type' => 'SGI', 'logo' => 'assets/images/bfs.png', 'order' => 12],
        ['nom' => 'BOA', 'type' => 'SGI', 'logo' => 'assets/images/boa.png', 'order' => 13],
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
            $names = array_merge([$entry['nom']], $entry['aliases'] ?? []);
            foreach ($names as $name) {
                if (self::normalizeName($name) === $needle) {
                    return $entry['logo'];
                }
            }
        }

        foreach (self::CATALOG as $entry) {
            $names = array_merge([$entry['nom']], $entry['aliases'] ?? []);
            foreach ($names as $name) {
                $label = self::normalizeName($name);
                if ($label !== '' && (str_contains($needle, $label) || str_contains($label, $needle))) {
                    return $entry['logo'];
                }
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
