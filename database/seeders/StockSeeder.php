<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stocks = [
            [
                'symbol' => 'SNTS',
                'company_name' => 'Sonatel',
                'current_price' => 16500,
                'previous_price' => 16100,
                'volume' => 1245,
                'market_cap' => 2500000,
                'sector' => 'Télécommunications',
                'high_price' => 16800,
                'low_price' => 16200,
                'is_active' => true,
            ],
            [
                'symbol' => 'BOAB',
                'company_name' => 'BOA Bénin',
                'current_price' => 5200,
                'previous_price' => 5110,
                'volume' => 892,
                'market_cap' => 850000,
                'sector' => 'Banque',
                'high_price' => 5300,
                'low_price' => 5150,
                'is_active' => true,
            ],
            [
                'symbol' => 'ORAC',
                'company_name' => 'Orange CI',
                'current_price' => 9800,
                'previous_price' => 9850,
                'volume' => 1567,
                'market_cap' => 1900000,
                'sector' => 'Télécommunications',
                'high_price' => 9900,
                'low_price' => 9750,
                'is_active' => true,
            ],
            [
                'symbol' => 'ETIT',
                'company_name' => 'Ecobank TG',
                'current_price' => 25,
                'previous_price' => 24.22,
                'volume' => 2134,
                'market_cap' => 450000,
                'sector' => 'Banque',
                'high_price' => 26,
                'low_price' => 24.5,
                'is_active' => true,
            ],
            [
                'symbol' => 'SIVC',
                'company_name' => 'SIVOM',
                'current_price' => 3800,
                'previous_price' => 3846,
                'volume' => 456,
                'market_cap' => 320000,
                'sector' => 'Distribution',
                'high_price' => 3900,
                'low_price' => 3750,
                'is_active' => true,
            ],
            [
                'symbol' => 'SPHC',
                'company_name' => 'SAPH Côte d\'Ivoire',
                'current_price' => 4100,
                'previous_price' => 4068,
                'volume' => 678,
                'market_cap' => 510000,
                'sector' => 'Agriculture',
                'high_price' => 4150,
                'low_price' => 4050,
                'is_active' => true,
            ],
            [
                'symbol' => 'SGBC',
                'company_name' => 'Société Générale',
                'current_price' => 12300,
                'previous_price' => 12117,
                'volume' => 934,
                'market_cap' => 1450000,
                'sector' => 'Banque',
                'high_price' => 12500,
                'low_price' => 12200,
                'is_active' => true,
            ],
            [
                'symbol' => 'PALC',
                'company_name' => 'Palm CI',
                'current_price' => 6500,
                'previous_price' => 6520,
                'volume' => 523,
                'market_cap' => 680000,
                'sector' => 'Agriculture',
                'high_price' => 6600,
                'low_price' => 6450,
                'is_active' => true,
            ],
        ];

        foreach ($stocks as $stockData) {
            $stock = Stock::create($stockData);
            $stock->calculateVariation();
        }
    }
}
