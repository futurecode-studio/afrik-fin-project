<?php

require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = new \App\Services\MutualFundsApiService();
$funds = $service->getMutualFunds();

echo "========== MOCK MUTUAL FUNDS DATA ==========\n";
echo date('Y-m-d H:i:s') . "\n\n";

foreach ($funds as $fund) {
    echo "ID: " . $fund['id'] . "\n";
    echo "Name: " . $fund['name'] . "\n";
    echo "NAV: " . $fund['nav_value'] . "\n";
    echo "Variation %: " . $fund['variation_percentage'] . "%\n";
    echo "Variation: " . $fund['variation'] . "\n";
    echo "Category: " . $fund['category'] . "\n";
    echo "---\n";
}

echo "\n✓ Mock data generated successfully with daily variations!\n";
