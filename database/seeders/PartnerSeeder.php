<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $renames = [
            'Apicassur' => 'Africabourse',
            'Africa Bourse' => 'Africabourse',
            'Africabourse Asset Management' => 'AAM',
            'Africa Bourse Asset Management' => 'AAM',
            'Africatitrisation' => 'AAT',
            'Africa Titrisation' => 'AAT',
        ];

        foreach ($renames as $oldName => $newName) {
            Partner::query()->where('nom', $oldName)->update(['nom' => $newName]);
        }

        foreach (Partner::CATALOG as $entry) {
            Partner::query()->updateOrCreate(
                ['nom' => $entry['nom']],
                [
                    'type' => $entry['type'],
                    'logo' => $entry['logo'],
                    'website' => $entry['website'] ?? null,
                    'country' => $entry['country'] ?? null,
                    'city' => $entry['city'] ?? null,
                    'agreement_number' => $entry['agreement_number'] ?? null,
                    'description' => $entry['description'] ?? 'Partenaire du marché financier régional UEMOA.',
                    'is_active' => true,
                    'is_featured' => true,
                    'order' => $entry['order'] ?? 0,
                ]
            );
        }

        $keep = collect(Partner::CATALOG)->pluck('nom')->all();
        Partner::query()->whereNotIn('nom', $keep)->update([
            'is_active' => false,
            'is_featured' => false,
        ]);

        Partner::query()->each(function (Partner $partner) {
            $logo = Partner::catalogLogoForName($partner->nom);
            if ($logo && $partner->logo !== $logo) {
                $partner->update(['logo' => $logo]);
            }
        });
    }
}
