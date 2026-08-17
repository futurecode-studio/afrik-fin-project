<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Fund;
use App\Services\MutualFundsApiService;
use Illuminate\Support\Facades\Cache;

class MutualFundsApiServiceTest extends TestCase
{
    private MutualFundsApiService $service;

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
        $this->assertGreaterThanOrEqual(count(Fund::CATALOG), count($funds));
    }

    public function test_mutual_fund_structure()
    {
        $fund = $this->service->getMutualFunds()[0];

        $this->assertArrayHasKey('id', $fund);
        $this->assertArrayHasKey('name', $fund);
        $this->assertArrayHasKey('company', $fund);
        $this->assertArrayHasKey('nav_value', $fund);
        $this->assertArrayHasKey('nav_numeric', $fund);
        $this->assertArrayHasKey('origin_nav', $fund);
        $this->assertArrayHasKey('variation', $fund);
        $this->assertArrayHasKey('variation_percentage', $fund);
        $this->assertArrayHasKey('currency', $fund);
        $this->assertArrayHasKey('date', $fund);
        $this->assertArrayHasKey('category', $fund);
    }

    public function test_get_fund_by_id()
    {
        $fund = $this->service->getFundById('aam-epargne-croissance');

        $this->assertNotNull($fund);
        $this->assertSame('AAM Épargne Croissance', $fund['name']);
        $this->assertEquals(13155.22, $fund['nav_numeric']);
        $this->assertEquals(163.10, $fund['variation_percentage']);
    }

    public function test_nd_variation_stays_null()
    {
        $fund = $this->service->getFundById('fcp-capital-plus');

        $this->assertNotNull($fund);
        $this->assertNull($fund['variation_percentage']);
        $this->assertSame('ND', $fund['variation']);
    }

    public function test_get_funds_by_category()
    {
        $actionsFunds = $this->service->getFundsByCategory('Actions');

        $this->assertNotEmpty($actionsFunds);
        foreach ($actionsFunds as $fund) {
            $this->assertEquals('Actions', $fund['category']);
        }
    }

    public function test_get_categories()
    {
        $categories = $this->service->getCategories();

        $this->assertIsArray($categories);
        $this->assertContains('Actions', $categories);
        $this->assertContains('Obligataire', $categories);
        $this->assertContains('Diversifié', $categories);
    }

    public function test_cache_functionality()
    {
        $funds1 = $this->service->getMutualFunds();
        $this->assertNotNull(Cache::get('mutual_funds_data'));

        $funds2 = $this->service->getMutualFunds();
        $this->assertEquals($funds1, $funds2);
    }

    public function test_clear_cache()
    {
        $this->service->getMutualFunds();
        $this->assertNotNull(Cache::get('mutual_funds_data'));

        $this->service->clearCache();
        $this->assertNull(Cache::get('mutual_funds_data'));
    }

    public function test_variation_formatting()
    {
        $fund = $this->service->getFundById('aam-epargne-croissance');

        $this->assertStringContainsString('%', $fund['variation']);
        $this->assertIsNumeric($fund['variation_percentage']);
    }

    public function test_currency_formatting()
    {
        $fund = $this->service->getFundById('aam-epargne-croissance');

        $this->assertStringContainsString($fund['currency'], $fund['nav_value']);
    }

    public function test_default_mutual_funds()
    {
        $funds = $this->service->getDefaultMutualFunds();

        $this->assertIsArray($funds);
        $this->assertCount(29, $funds);

        foreach ($funds as $fund) {
            $this->assertNotNull($fund['id']);
            $this->assertNotNull($fund['name']);
            $this->assertNotNull($fund['company']);
        }
    }
}
