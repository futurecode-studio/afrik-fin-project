<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MarketIndex;
use App\Models\Stock;
use App\Models\StockPrice;
use App\Services\MarketsDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MarketController extends Controller
{
    public function __construct(private MarketsDataService $markets) {}

    public function overview(): JsonResponse
    {
        $data = Cache::remember('api.v1.market.overview', 1800, function () {
            $stocks = $this->markets->stocks();

            return [
                'exchange' => 'BRVM',
                'currency' => 'XOF',
                'count' => $stocks->count(),
                'total_volume' => $this->markets->totalVolume(),
                'gainers' => $this->markets->topGainers(5)->values(),
                'losers' => $this->markets->topLosers(5)->values(),
                'most_active' => $this->markets->topVolume(5)->values(),
                'indices' => MarketIndex::query()->where('exchange', 'BRVM')->orderBy('code')->get(),
                'updated_at' => optional($stocks->max('source_updated_at') ?? $stocks->max('last_updated')),
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function stocks(Request $request): JsonResponse
    {
        $data = Cache::remember('api.v1.stocks', 1800, function () {
            return Stock::query()
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->where('exchange', 'BRVM')->orWhereNull('exchange');
                })
                ->orderBy('symbol')
                ->get();
        });

        if ($request->filled('sector')) {
            $data = $data->where('sector', $request->string('sector')->toString())->values();
        }

        return response()->json([
            'success' => true,
            'data' => $data->values(),
            'meta' => ['count' => $data->count(), 'exchange' => 'BRVM'],
        ]);
    }

    public function show(string $symbol): JsonResponse
    {
        $stock = $this->markets->stockBySymbol($symbol);
        if (! $stock) {
            return response()->json(['success' => false, 'message' => 'Titre introuvable'], 404);
        }

        return response()->json(['success' => true, 'data' => $stock]);
    }

    public function history(string $symbol, Request $request): JsonResponse
    {
        $stock = $this->markets->stockBySymbol($symbol);
        if (! $stock) {
            return response()->json(['success' => false, 'message' => 'Titre introuvable'], 404);
        }

        $limit = min((int) $request->integer('limit', 100), 500);

        $rows = StockPrice::query()
            ->where('stock_id', $stock->id)
            ->orderByDesc('recorded_at')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'symbol' => $stock->symbol,
                'points' => $rows,
            ],
        ]);
    }

    public function indices(): JsonResponse
    {
        $data = Cache::remember('api.v1.indices', 1800, function () {
            return MarketIndex::query()->where('exchange', 'BRVM')->orderBy('code')->get();
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function gainers(Request $request): JsonResponse
    {
        $limit = min((int) $request->integer('limit', 10), 50);

        return response()->json([
            'success' => true,
            'data' => $this->markets->topGainers($limit)->values(),
        ]);
    }

    public function losers(Request $request): JsonResponse
    {
        $limit = min((int) $request->integer('limit', 10), 50);

        return response()->json([
            'success' => true,
            'data' => $this->markets->topLosers($limit)->values(),
        ]);
    }

    public function mostActive(Request $request): JsonResponse
    {
        $limit = min((int) $request->integer('limit', 10), 50);

        return response()->json([
            'success' => true,
            'data' => $this->markets->topVolume($limit)->values(),
        ]);
    }
}
