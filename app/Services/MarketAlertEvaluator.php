<?php

namespace App\Services;

use App\Models\MarketAlert;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MarketAlertEvaluator
{
    /**
     * Évalue les alertes actives et bascule en « triggered » si la condition est remplie.
     *
     * @return Collection<int, MarketAlert> alertes nouvellement déclenchées
     */
    public function evaluate(?int $userId = null): Collection
    {
        $query = MarketAlert::query()
            ->with(['stock', 'user'])
            ->where('status', 'active')
            ->whereNotNull('stock_id');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $triggered = collect();

        foreach ($query->get() as $alert) {
            if ($this->conditionMet($alert)) {
                $this->markTriggered($alert);
                $triggered->push($alert->fresh(['stock', 'user']));
            }
        }

        return $triggered;
    }

    public function conditionMet(MarketAlert $alert): bool
    {
        $stock = $alert->stock;
        if (! $stock instanceof Stock) {
            return false;
        }

        $threshold = $alert->threshold !== null ? (float) $alert->threshold : null;
        $price = (float) $stock->current_price;
        $volume = (float) ($stock->volume ?? 0);
        $variation = (float) ($stock->variation_percent ?? 0);

        return match ($alert->trigger_type) {
            'price_above' => $threshold !== null && $price >= $threshold,
            'price_below' => $threshold !== null && $price <= $threshold,
            'volume' => $threshold !== null && $volume >= $threshold,
            'variation_above' => $threshold !== null && $variation >= $threshold,
            'variation_below' => $threshold !== null && $variation <= $threshold,
            default => false,
        };
    }

    public function markTriggered(MarketAlert $alert): void
    {
        $stock = $alert->stock;
        $message = $this->buildMessage($alert, $stock);

        $alert->update([
            'status' => 'triggered',
            'triggered_at' => now(),
            'notes' => $message,
        ]);

        if ($alert->channel === 'email' && $alert->user?->email) {
            $this->notifyEmail($alert->user, $alert, $message);
        }

        Log::info('Market alert triggered', [
            'alert_id' => $alert->id,
            'user_id' => $alert->user_id,
            'symbol' => $stock?->symbol,
            'trigger' => $alert->trigger_type,
        ]);
    }

    public function buildMessage(MarketAlert $alert, ?Stock $stock): string
    {
        $symbol = $stock?->symbol ?? $alert->asset_label ?? 'Titre';
        $price = $stock ? number_format((float) $stock->current_price, 2, ',', ' ') : '—';
        $label = MarketAlert::triggerLabel($alert->trigger_type);
        $threshold = $alert->threshold !== null
            ? number_format((float) $alert->threshold, 2, ',', ' ')
            : '—';

        return "Alerte {$symbol} : {$label} (seuil {$threshold}). Cours actuel : {$price} XOF.";
    }

    /**
     * Score 0–100 basé sur la largeur du marché (% de titres en hausse).
     *
     * @return array{score:int,label:string,up:int,down:int,flat:int,total:int}
     */
    public function marketSentiment(Collection $stocks): array
    {
        $total = $stocks->count();
        if ($total === 0) {
            return ['score' => 50, 'label' => 'Neutre', 'up' => 0, 'down' => 0, 'flat' => 0, 'total' => 0];
        }

        $up = $stocks->filter(fn (Stock $s) => (float) $s->variation_percent > 0.05)->count();
        $down = $stocks->filter(fn (Stock $s) => (float) $s->variation_percent < -0.05)->count();
        $flat = $total - $up - $down;
        $score = (int) round(($up / $total) * 100);

        $label = match (true) {
            $score >= 65 => 'Sentiment élevé',
            $score >= 45 => 'Sentiment neutre',
            default => 'Sentiment faible',
        };

        return compact('score', 'label', 'up', 'down', 'flat', 'total');
    }

    private function notifyEmail(User $user, MarketAlert $alert, string $message): void
    {
        try {
            Mail::raw($message."\n\n— Africaine des Finances (alertes in-app / e-mail)", function ($mail) use ($user, $alert) {
                $mail->to($user->email)
                    ->subject('Alerte marché BRVM — '.($alert->stock?->symbol ?? 'ADF'));
            });
        } catch (\Throwable $e) {
            Log::warning('Market alert email failed: '.$e->getMessage());
        }
    }
}
