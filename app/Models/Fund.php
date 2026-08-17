<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Fund extends Model
{
    use HasFactory;

    public const DEFAULT_VL_DATE = '2026-07-23';

    public const DEFAULT_SOURCE = 'Bulletin officiel BRVM — 23 juillet 2026 (p. 19)';

    public const CATEGORIES = [
        'Actions' => 'Actions',
        'Diversifié' => 'Diversifié',
        'Obligataire' => 'Obligataire',
        'Obligataire court terme' => 'Obligataire court terme',
        'Monétaire' => 'Monétaire',
    ];

    /**
     * FCP du Bénin extraits de la p. 19 du bulletin BRVM du 23 juillet 2026.
     *
     * @var array<int, array<string, mixed>>
     */
    public const CATALOG = [
        // Africa Bourse Asset Management
        ['slug' => 'aam-epargne-croissance', 'name' => 'AAM Épargne Croissance', 'company' => 'Africa Bourse Asset Management', 'company_short' => 'Africa Bourse', 'category' => 'Diversifié', 'origin_nav' => 5000, 'current_nav' => 13155.22, 'variation_origin' => 163.10, 'display_order' => 1],
        ['slug' => 'aam-obligatis', 'name' => 'AAM Obligatis', 'company' => 'Africa Bourse Asset Management', 'company_short' => 'Africa Bourse', 'category' => 'Obligataire', 'origin_nav' => 5000, 'current_nav' => 9546.42, 'variation_origin' => 90.93, 'display_order' => 2],
        ['slug' => 'aam-epargne-action', 'name' => 'AAM Épargne Action', 'company' => 'Africa Bourse Asset Management', 'company_short' => 'Africa Bourse', 'category' => 'Actions', 'origin_nav' => 5000, 'current_nav' => 12751.31, 'variation_origin' => 155.03, 'display_order' => 3],
        ['slug' => 'aam-serenitis', 'name' => 'AAM Sérénitis', 'company' => 'Africa Bourse Asset Management', 'company_short' => 'Africa Bourse', 'category' => 'Obligataire', 'origin_nav' => 10000, 'current_nav' => 13266.73, 'variation_origin' => 32.67, 'display_order' => 4],

        // AGI
        ['slug' => 'fcp-expansio', 'name' => 'FCP Expansio', 'company' => 'Africaine de Gestion d’Actifs', 'company_short' => 'AGI', 'category' => 'Diversifié', 'origin_nav' => 5000, 'current_nav' => 14253.05, 'variation_origin' => 185.06, 'display_order' => 5],
        ['slug' => 'fcp-securitas', 'name' => 'FCP Securitas', 'company' => 'Africaine de Gestion d’Actifs', 'company_short' => 'AGI', 'category' => 'Obligataire', 'origin_nav' => 5000, 'current_nav' => 8825.66, 'variation_origin' => 76.51, 'display_order' => 6],
        ['slug' => 'fcp-valoris', 'name' => 'FCP Valoris', 'company' => 'Africaine de Gestion d’Actifs', 'company_short' => 'AGI', 'category' => 'Actions', 'origin_nav' => 5000, 'current_nav' => 21447.73, 'variation_origin' => 328.95, 'display_order' => 7],
        ['slug' => 'fcp-capital-plus', 'name' => 'FCP Capital Plus', 'company' => 'Africaine de Gestion d’Actifs', 'company_short' => 'AGI', 'category' => 'Diversifié', 'origin_nav' => 1000, 'current_nav' => 1678.37, 'variation_origin' => null, 'display_order' => 8],
        ['slug' => 'fcp-confort-plus', 'name' => 'FCP Confort Plus', 'company' => 'Africaine de Gestion d’Actifs', 'company_short' => 'AGI', 'category' => 'Obligataire', 'origin_nav' => 1000, 'current_nav' => 1549.97, 'variation_origin' => null, 'display_order' => 9],

        // SOAGA
        ['slug' => 'soaga-epargne-active', 'name' => 'SOAGA Épargne Active', 'company' => 'SOAGA', 'company_short' => 'SOAGA', 'category' => 'Diversifié', 'origin_nav' => 10000, 'current_nav' => 16616.97, 'variation_origin' => 66.17, 'display_order' => 10],
        ['slug' => 'soaga-epargne-obligations', 'name' => 'SOAGA Épargne Obligations', 'company' => 'SOAGA', 'company_short' => 'SOAGA', 'category' => 'Obligataire', 'origin_nav' => 5000, 'current_nav' => 6751.35, 'variation_origin' => 35.03, 'display_order' => 11],
        ['slug' => 'soaga-epargne-actions', 'name' => 'SOAGA Épargne Actions', 'company' => 'SOAGA', 'company_short' => 'SOAGA', 'category' => 'Actions', 'origin_nav' => 5000, 'current_nav' => 15110.18, 'variation_origin' => 202.20, 'display_order' => 12],
        ['slug' => 'soaga-epargne-serenite', 'name' => 'SOAGA Épargne Sérénité', 'company' => 'SOAGA', 'company_short' => 'SOAGA', 'category' => 'Obligataire', 'origin_nav' => 10000, 'current_nav' => 17755.09, 'variation_origin' => 77.55, 'display_order' => 13],
        ['slug' => 'soaga-epargne-quietude', 'name' => 'SOAGA Épargne Quiétude', 'company' => 'SOAGA', 'company_short' => 'SOAGA', 'category' => 'Obligataire', 'origin_nav' => 5000, 'current_nav' => 6524.67, 'variation_origin' => 30.49, 'display_order' => 14],
        ['slug' => 'soaga-epargne-dynamique', 'name' => 'SOAGA Épargne Dynamique', 'company' => 'SOAGA', 'company_short' => 'SOAGA', 'category' => 'Actions', 'origin_nav' => 5000, 'current_nav' => 9496.20, 'variation_origin' => 89.92, 'display_order' => 15],
        ['slug' => 'soaga-tresorerie', 'name' => 'SOAGA Trésorerie', 'company' => 'SOAGA', 'company_short' => 'SOAGA', 'category' => 'Monétaire', 'origin_nav' => 10000, 'current_nav' => 10708.60, 'variation_origin' => 7.09, 'display_order' => 16],

        // Saphir
        ['slug' => 'saphir-dynamique', 'name' => 'Saphir Dynamique', 'company' => 'Saphir Asset Management', 'company_short' => 'Saphir', 'category' => 'Diversifié', 'origin_nav' => 5000, 'current_nav' => 8623.26, 'variation_origin' => 72.47, 'display_order' => 17],
        ['slug' => 'saphir-quietude', 'name' => 'Saphir Quiétude', 'company' => 'Saphir Asset Management', 'company_short' => 'Saphir', 'category' => 'Obligataire', 'origin_nav' => 5000, 'current_nav' => 6888.05, 'variation_origin' => 37.76, 'display_order' => 18],
        ['slug' => 'saphir-liquidite', 'name' => 'Saphir Liquidité', 'company' => 'Saphir Asset Management', 'company_short' => 'Saphir', 'category' => 'Obligataire court terme', 'origin_nav' => 10000, 'current_nav' => 10144.19, 'variation_origin' => 1.44, 'display_order' => 19],

        // WAFI / AGI Bénin
        [
            'slug' => 'sicav-wafi-capital',
            'name' => 'SICAV WAFI Capital',
            'company' => 'WAFI Capital (AGI Bénin)',
            'company_short' => 'AGI',
            'category' => 'Diversifié',
            'origin_nav' => 10000,
            'current_nav' => 10974.62,
            'variation_origin' => null,
            'display_order' => 20,
            'notes' => 'Dernière valeur disponible indiquée au 15/10/2025 ; variation origine ND dans le bulletin.',
        ],

        // NSIA
        ['slug' => 'nsia-fonds-diversifie', 'name' => 'NSIA Fonds Diversifié', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Diversifié', 'origin_nav' => 5000, 'current_nav' => 8211.28, 'variation_origin' => 64.23, 'display_order' => 21],
        ['slug' => 'aurore-opportunites', 'name' => 'Aurore Opportunités', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Actions', 'origin_nav' => 5000, 'current_nav' => 12170.52, 'variation_origin' => 143.41, 'display_order' => 22],
        ['slug' => 'aurore-securite', 'name' => 'Aurore Sécurité', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Obligataire', 'origin_nav' => 5000, 'current_nav' => 6795.51, 'variation_origin' => 35.91, 'display_order' => 23],
        ['slug' => 'nsia-assurances-optimum', 'name' => 'NSIA Assurances Optimum', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Diversifié', 'origin_nav' => 1000000, 'current_nav' => 1532817.97, 'variation_origin' => 53.28, 'display_order' => 24],
        ['slug' => 'aurore-monetaris', 'name' => 'Aurore Monetaris', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Monétaire', 'origin_nav' => 5000, 'current_nav' => 6072.38, 'variation_origin' => 21.45, 'display_order' => 25],
        ['slug' => 'tawfir-halal', 'name' => 'Tawfir Halal', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Diversifié', 'origin_nav' => 5000, 'current_nav' => 6783.89, 'variation_origin' => 35.68, 'display_order' => 26],
        ['slug' => 'aurore-securite-ii', 'name' => 'Aurore Sécurité II', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Obligataire', 'origin_nav' => 6133, 'current_nav' => 6170.38, 'variation_origin' => 0.61, 'display_order' => 27],
        ['slug' => 'aurore-obligations-souveraines', 'name' => 'Aurore Obligations Souveraines', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Obligataire', 'origin_nav' => 10000, 'current_nav' => 11307.41, 'variation_origin' => 13.07, 'display_order' => 28],
        ['slug' => 'obligations-premium', 'name' => 'Obligations Premium', 'company' => 'NSIA', 'company_short' => 'NSIA', 'category' => 'Obligataire', 'origin_nav' => 5000, 'current_nav' => 5804.28, 'variation_origin' => 16.09, 'display_order' => 29],
    ];

    protected $fillable = [
        'slug',
        'name',
        'company',
        'company_short',
        'category',
        'country',
        'origin_nav',
        'current_nav',
        'variation_origin',
        'vl_date',
        'source',
        'notes',
        'flyer',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'origin_nav' => 'decimal:2',
        'current_nav' => 'decimal:2',
        'variation_origin' => 'decimal:2',
        'vl_date' => 'date',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $fund) {
            if (empty($fund->slug) && $fund->name) {
                $fund->slug = Str::slug($fund->name);
            }
        });

        static::saved(fn () => static::forgetPublicCache());
        static::deleted(fn () => static::forgetPublicCache());
    }

    public static function forgetPublicCache(): void
    {
        cache()->forget('mutual_funds_data');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function flyerUrl(): ?string
    {
        if (! $this->flyer) {
            return null;
        }

        if (str_starts_with($this->flyer, 'http://') || str_starts_with($this->flyer, 'https://')) {
            return $this->flyer;
        }

        if (str_starts_with($this->flyer, 'assets/') || str_starts_with($this->flyer, '/assets/')) {
            return asset(ltrim($this->flyer, '/'));
        }

        return asset('storage/'.$this->flyer);
    }

    public function logoUrl(): ?string
    {
        return Partner::urlForLogo(
            Partner::catalogLogoForName($this->company_short ?: $this->company)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        $current = $this->current_nav !== null ? (float) $this->current_nav : null;
        $origin = $this->origin_nav !== null ? (float) $this->origin_nav : null;
        $variation = $this->variation_origin !== null ? (float) $this->variation_origin : null;
        $date = $this->vl_date?->format('Y-m-d') ?: self::DEFAULT_VL_DATE;

        return [
            'id' => $this->slug,
            'isin' => null,
            'name' => $this->name,
            'company' => $this->company,
            'company_short' => $this->company_short,
            'country' => $this->country ?: 'Bénin',
            'category' => $this->category,
            'origin_nav' => $origin,
            'origin_nav_value' => $origin !== null ? self::formatCurrency($origin) : '—',
            'nav_numeric' => $current ?? 0,
            'nav_value' => $current !== null ? self::formatCurrency($current) : '—',
            'variation_percentage' => $variation,
            'variation' => $variation !== null ? self::formatVariation($variation) : 'ND',
            'nav' => $current,
            'vl' => $current,
            'currency' => 'FCFA',
            'date' => $date,
            'source' => $this->source ?: self::DEFAULT_SOURCE,
            'source_url' => null,
            'source_note' => $this->notes,
            'flyer_url' => $this->flyerUrl(),
            'logo' => $this->logoUrl(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function publicList(): array
    {
        try {
            if (Schema::hasTable((new static)->getTable())) {
                $rows = static::query()
                    ->active()
                    ->orderBy('display_order')
                    ->orderBy('name')
                    ->get();

                if ($rows->isNotEmpty()) {
                    return $rows->map(fn (self $fund) => $fund->toPublicArray())->values()->all();
                }
            }
        } catch (\Throwable) {
            // Base indisponible : catalogue bulletin.
        }

        return static::catalogCollection()
            ->map(fn (self $fund) => $fund->toPublicArray())
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, self>
     */
    public static function catalogCollection(): Collection
    {
        return collect(self::CATALOG)->map(function (array $entry) {
            return new self([
                'slug' => $entry['slug'],
                'name' => $entry['name'],
                'company' => $entry['company'],
                'company_short' => $entry['company_short'] ?? null,
                'category' => $entry['category'],
                'country' => $entry['country'] ?? 'Bénin',
                'origin_nav' => $entry['origin_nav'] ?? null,
                'current_nav' => $entry['current_nav'] ?? null,
                'variation_origin' => $entry['variation_origin'] ?? null,
                'vl_date' => $entry['vl_date'] ?? self::DEFAULT_VL_DATE,
                'source' => $entry['source'] ?? self::DEFAULT_SOURCE,
                'notes' => $entry['notes'] ?? null,
                'flyer' => $entry['flyer'] ?? null,
                'is_active' => $entry['is_active'] ?? true,
                'display_order' => $entry['display_order'] ?? 0,
            ]);
        });
    }

    public static function formatCurrency(float $value): string
    {
        $decimals = $value >= 1000 && fmod($value, 1) === 0.0 ? 0 : 2;

        return number_format($value, $decimals, ',', ' ').' FCFA';
    }

    public static function formatVariation(float $percent): string
    {
        $sign = $percent > 0 ? '+' : '';

        return $sign.number_format($percent, 2, ',', ' ').' %';
    }
}
