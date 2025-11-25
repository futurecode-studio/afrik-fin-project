<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\MutualFundsApiService;
use Illuminate\Support\Facades\Cache;

class MutualFundsApiServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(MutualFundsApiService::class);
        Cache::flush();
    }

    public function test_get_mutual_funds_returns_array()
    {
        $funds = $this->service->getMutualFunds();
        
        $this->assertIsArray($funds);
        $this->assertNotEmpty($funds);
    }

    public function test_mutual_fund_structure()
    {
        $funds = $this->service->getMutualFunds();
        $fund = $funds[0];

        $this->assertArrayHasKey('id', $fund);
        $this->assertArrayHasKey('name', $fund);
        $this->assertArrayHasKey('company', $fund);
        $this->assertArrayHasKey('nav_value', $fund);
        $this->assertArrayHasKey('nav_numeric', $fund);
        $this->assertArrayHasKey('variation', $fund);
        $this->assertArrayHasKey('variation_percentage', $fund);
        $this->assertArrayHasKey('currency', $fund);
        $this->assertArrayHasKey('date', $fund);
        $this->assertArrayHasKey('category', $fund);
    }

    public function test_get_fund_by_id()
    {
        $funds = $this->service->getMutualFunds();
        $fundId = $funds[0]['id'];

        $fund = $this->service->getFundById($fundId);

        $this->assertNotNull($fund);
        $this->assertEquals($fundId, $fund['id']);
    }

    public function test_get_funds_by_category()
    {
        $actionsFunds = $this->service->getFundsByCategory('Actions');

        $this->assertIsArray($actionsFunds);
        foreach ($actionsFunds as $fund) {
            $this->assertEquals('Actions', $fund['category']);
        }
    }

    public function test_get_categories()
    {
        $categories = $this->service->getCategories();

        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);
        $this->assertContains('Actions', $categories);
    }

    public function test_cache_functionality()
    {
        // Premier appel - remplit le cache
        $funds1 = $this->service->getMutualFunds();
        $cachedValue = Cache::get('mutual_funds_data');
        $this->assertNotNull($cachedValue);

        // Deuxième appel - utilise le cache
        $funds2 = $this->service->getMutualFunds();
        $this->assertEquals($funds1, $funds2);
    }

    public function test_clear_cache()
    {
        // Remplir le cache
        $this->service->getMutualFunds();
        $this->assertNotNull(Cache::get('mutual_funds_data'));

        // Effacer le cache
        $this->service->clearCache();
        $this->assertNull(Cache::get('mutual_funds_data'));
    }

    public function test_variation_formatting()
    {
        $funds = $this->service->getMutualFunds();
        $fund = $funds[0];

        // Vérifier que la variation est formatée correctement
        $this->assertStringContainsString('%', $fund['variation']);
        $this->assertIsNumeric($fund['variation_percentage']);
    }

    public function test_currency_formatting()
    {
        $funds = $this->service->getMutualFunds();
        $fund = $funds[0];

        // Vérifier que la devise est présente
        $this->assertStringContainsString($fund['currency'], $fund['nav_value']);
    }

    public function test_default_mutual_funds()
    {
        $funds = $this->service->getDefaultMutualFunds();

        $this->assertIsArray($funds);
        $this->assertCount(8, $funds); // 8 fonds par défaut
        
        // Vérifier que tous les fonds ont les propriétés requises
        foreach ($funds as $fund) {
            $this->assertNotNull($fund['id']);
            $this->assertNotNull($fund['name']);
            $this->assertNotNull($fund['company']);
        }
    }
}
