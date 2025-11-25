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
