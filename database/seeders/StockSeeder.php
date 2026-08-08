<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

/**
 * Seed de secours (dev / DB vide uniquement).
 * Ne doit PAS écraser les cotations live en production.
 */
class StockSeeder extends Seeder
{
    public function run(): void
    {
        if (Stock::query()->exists()) {
            $this->command?->warn('StockSeeder ignoré : des titres existent déjà (préserve les données live).');

            return;
        }

        $stocks = [
            [
                'symbol' => 'SNTS',
                'company_name' => 'Sonatel',
                'exchange' => 'BRVM',
                'currency' => 'XOF',
                'current_price' => 16500,
                'previous_price' => 16100,
                'volume' => 1245,
                'market_cap' => null,
                'shares_outstanding' => 100000000,
                'sector' => 'Télécommunications',
                'is_active' => true,
            ],
            [
                'symbol' => 'BOAB',
                'company_name' => 'BOA Bénin',
                'exchange' => 'BRVM',
                'currency' => 'XOF',
                'current_price' => 5200,
                'previous_price' => 5110,
                'volume' => 892,
                'market_cap' => null,
                'shares_outstanding' => 1007268,
                'sector' => 'Banque',
                'is_active' => true,
            ],
            [
                'symbol' => 'ORAC',
                'company_name' => 'Orange CI',
                'exchange' => 'BRVM',
                'currency' => 'XOF',
                'current_price' => 9800,
                'previous_price' => 9850,
                'volume' => 1567,
                'market_cap' => null,
                'sector' => 'Télécommunications',
                'is_active' => true,
            ],
            [
                'symbol' => 'ETIT',
                'company_name' => 'Ecobank TG',
                'exchange' => 'BRVM',
                'currency' => 'XOF',
                'current_price' => 25,
                'previous_price' => 24.22,
                'volume' => 2134,
                'market_cap' => null,
                'shares_outstanding' => 18032598730,
                'sector' => 'Banque',
                'is_active' => true,
            ],
            [
                'symbol' => 'SGBC',
                'company_name' => 'Société Générale',
                'exchange' => 'BRVM',
                'currency' => 'XOF',
                'current_price' => 12300,
                'previous_price' => 12117,
                'volume' => 934,
                'market_cap' => null,
                'sector' => 'Banque',
                'is_active' => true,
            ],
        ];

        foreach ($stocks as $stockData) {
            $stock = Stock::create($stockData);
            $stock->calculateVariation();
        }
    }
}
