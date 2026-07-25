<?php

namespace App\Services\Mansa;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Client HTTP Mansa API (mansaapi.com) — jamais appelé depuis le frontend.
 */
class MansaMarketsClient
{
    public function isConfigured(): bool
    {
        return filled(config('services.mansa.api_key'));
    }

    public function stocks(string $exchange = 'BRVM', int $limit = 200, int $offset = 0): array
    {
        return $this->get("/api/v1/markets/exchanges/{$exchange}/stocks", [
            'limit' => min($limit, 200),
            'offset' => max($offset, 0),
        ]);
    }

    public function allStocks(string $exchange = 'BRVM'): array
    {
        $all = [];
        $offset = 0;
        $limit = 200;

        do {
            $payload = $this->stocks($exchange, $limit, $offset);
            $rows = $payload['data'] ?? [];
            if (! is_array($rows) || $rows === []) {
                break;
            }
            $all = array_merge($all, $rows);
            $pagination = $payload['pagination'] ?? [];
            $hasMore = (bool) ($pagination['has_more'] ?? false);
            $offset += $limit;
        } while ($hasMore && $offset < 1000);

        return [
            'success' => true,
            'data' => $all,
            'meta' => $payload['meta'] ?? [],
        ];
    }

    public function indices(string $exchange = 'BRVM'): array
    {
        return $this->get("/api/v1/markets/exchanges/{$exchange}/indices");
    }

    public function movers(string $exchange = 'BRVM', int $limit = 10, string $type = 'both'): array
    {
        return $this->get("/api/v1/markets/exchanges/{$exchange}/movers", [
            'limit' => min($limit, 20),
            'type' => $type,
        ]);
    }

    public function search(string $q, ?string $exchange = null, int $limit = 20): array
    {
        $params = ['q' => $q, 'limit' => min($limit, 50)];
        if ($exchange) {
            $params['exchange'] = $exchange;
        }

        return $this->get('/api/v1/markets/search', $params);
    }

    private function get(string $path, array $query = []): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Clé Mansa manquante (Config API admin ou MANSA_API_KEY).');
        }

        $response = $this->http()->get(rtrim(config('services.mansa.base_url'), '/').$path, $query);

        if (! $response->successful()) {
            Log::warning('Mansa API error', [
                'path' => $path,
                'status' => $response->status(),
                'body' => mb_substr($response->body(), 0, 500),
            ]);

            throw new RuntimeException('Mansa API HTTP '.$response->status());
        }

        $json = $response->json();
        if (! is_array($json)) {
            throw new RuntimeException('Mansa API: réponse JSON invalide');
        }

        if (($json['success'] ?? true) === false) {
            $msg = $json['error']['message'] ?? 'Mansa API success=false';
            throw new RuntimeException($msg);
        }

        return $json;
    }

    private function http(): PendingRequest
    {
        return Http::timeout((int) config('services.mansa.timeout', 20))
            ->acceptJson()
            ->withToken((string) config('services.mansa.api_key'));
    }
}
