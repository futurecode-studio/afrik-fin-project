<?php

namespace App\Support;

class Countries
{
    /** @var list<string> */
    private const PRIORITY = [
        'Bénin',
        'Burkina Faso',
        "Côte d'Ivoire",
        'Guinée-Bissau',
        'Mali',
        'Niger',
        'Sénégal',
        'Togo',
    ];

    /**
     * @return array<int, array{code: string, name: string}>
     */
    public static function frenchOptions(): array
    {
        static $options = null;
        if ($options !== null) {
            return $options;
        }

        $path = base_path('vendor/umpirsky/country-list/data/fr/country.php');
        /** @var array<string, string> $names */
        $names = is_file($path) ? require $path : [];

        $all = collect($names)
            ->map(fn (string $name, string $code) => ['code' => $code, 'name' => $name])
            ->values();

        $priority = collect(self::PRIORITY);

        $pinned = $all
            ->filter(fn (array $country) => $priority->contains($country['name']))
            ->sortBy(fn (array $country) => $priority->search($country['name']));

        $rest = $all
            ->reject(fn (array $country) => $priority->contains($country['name']))
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);

        $options = $pinned->concat($rest)->values()->all();

        return $options;
    }
}
