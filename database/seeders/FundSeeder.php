<?php

namespace Database\Seeders;

use App\Models\Fund;
use Illuminate\Database\Seeder;

class FundSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Fund::CATALOG as $entry) {
            Fund::updateOrCreate(
                ['slug' => $entry['slug']],
                [
                    'name' => $entry['name'],
                    'company' => $entry['company'],
                    'company_short' => $entry['company_short'] ?? null,
                    'category' => $entry['category'],
                    'country' => $entry['country'] ?? 'Bénin',
                    'origin_nav' => $entry['origin_nav'] ?? null,
                    'current_nav' => $entry['current_nav'] ?? null,
                    'variation_origin' => $entry['variation_origin'] ?? null,
                    'vl_date' => $entry['vl_date'] ?? Fund::DEFAULT_VL_DATE,
                    'source' => $entry['source'] ?? Fund::DEFAULT_SOURCE,
                    'notes' => $entry['notes'] ?? null,
                    'is_active' => $entry['is_active'] ?? true,
                    'display_order' => $entry['display_order'] ?? 0,
                ]
            );
        }

        Fund::forgetPublicCache();
    }
}
