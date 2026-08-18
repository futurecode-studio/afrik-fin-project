<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
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

        Partner::query()->each(function (Partner $partner) {
            $logo = Partner::catalogLogoForName($partner->nom);
            if ($logo && $partner->logo !== $logo) {
                $partner->update(['logo' => $logo]);
            }
        });
    }
}
