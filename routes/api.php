<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\MutualFundsApiService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Market API (données locales — jamais Mansa côté client)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::get('/market/overview', [\App\Http\Controllers\Api\V1\MarketController::class, 'overview']);
    Route::get('/market/gainers', [\App\Http\Controllers\Api\V1\MarketController::class, 'gainers']);
    Route::get('/market/losers', [\App\Http\Controllers\Api\V1\MarketController::class, 'losers']);
    Route::get('/market/most-active', [\App\Http\Controllers\Api\V1\MarketController::class, 'mostActive']);

    Route::get('/stocks', [\App\Http\Controllers\Api\V1\MarketController::class, 'stocks']);
    Route::get('/stocks/{symbol}', [\App\Http\Controllers\Api\V1\MarketController::class, 'show']);
    Route::get('/stocks/{symbol}/history', [\App\Http\Controllers\Api\V1\MarketController::class, 'history']);

    Route::get('/indices', [\App\Http\Controllers\Api\V1\MarketController::class, 'indices']);
});

/*
|--------------------------------------------------------------------------
| Mutual Funds API Routes
|--------------------------------------------------------------------------
|
| Routes pour accéder aux données des valeurs liquidatives en JSON
|
*/

Route::prefix('mutual-funds')->group(function () {
    // Récupérer tous les fonds
    Route::get('/', function (MutualFundsApiService $service) {
        return response()->json([
            'success' => true,
            'data' => $service->getMutualFunds(),
            'timestamp' => now(),
        ]);
    })->name('api.mutual-funds.index');

    // Récupérer les fonds par catégorie
    Route::get('/category/{category}', function ($category, MutualFundsApiService $service) {
        return response()->json([
            'success' => true,
            'category' => $category,
            'data' => $service->getFundsByCategory($category),
            'timestamp' => now(),
        ]);
    })->name('api.mutual-funds.category');

    // Récupérer les catégories disponibles
    Route::get('/categories/list', function (MutualFundsApiService $service) {
        return response()->json([
            'success' => true,
            'categories' => $service->getCategories(),
            'timestamp' => now(),
        ]);
    })->name('api.mutual-funds.categories');

    // Récupérer un fonds spécifique
    Route::get('/{id}', function ($id, MutualFundsApiService $service) {
        $fund = $service->getFundById($id);
        
        if (!$fund) {
            return response()->json([
                'success' => false,
                'message' => 'Fonds non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $fund,
            'timestamp' => now(),
        ]);
    })->name('api.mutual-funds.show');
});
