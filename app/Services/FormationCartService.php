<?php

namespace App\Services;

use App\Models\Formation;
use Illuminate\Support\Collection;

/**
 * Panier formations (session).
 */
class FormationCartService
{
    public const SESSION_KEY = 'formation_cart';

    /** @return array<int, array{formation_id:int, quantity:int}> */
    public function items(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return collect($this->items())->sum('quantity');
    }

    public function add(int $formationId, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);
        $items = $this->items();

        foreach ($items as &$item) {
            if ((int) $item['formation_id'] === $formationId) {
                $item['quantity'] = (int) $item['quantity'] + $quantity;
                session([self::SESSION_KEY => array_values($items)]);

                return;
            }
        }

        $items[] = ['formation_id' => $formationId, 'quantity' => $quantity];
        session([self::SESSION_KEY => $items]);
    }

    public function update(int $formationId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->remove($formationId);

            return;
        }

        $items = $this->items();
        foreach ($items as &$item) {
            if ((int) $item['formation_id'] === $formationId) {
                $item['quantity'] = $quantity;
                session([self::SESSION_KEY => array_values($items)]);

                return;
            }
        }
    }

    public function remove(int $formationId): void
    {
        $items = array_values(array_filter(
            $this->items(),
            fn ($item) => (int) $item['formation_id'] !== $formationId
        ));
        session([self::SESSION_KEY => $items]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * @return Collection<int, object{formation: Formation, quantity: int, line_total: float}>
     */
    public function resolved(): Collection
    {
        $ids = collect($this->items())->pluck('formation_id')->all();
        if ($ids === []) {
            return collect();
        }

        $formations = Formation::publie()->whereIn('id', $ids)->get()->keyBy('id');

        return collect($this->items())
            ->map(function (array $item) use ($formations) {
                $formation = $formations->get($item['formation_id']);
                if (! $formation) {
                    return null;
                }
                $qty = (int) $item['quantity'];
                $unit = $formation->isFree() ? 0.0 : (float) $formation->prix;

                return (object) [
                    'formation' => $formation,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'line_total' => $unit * $qty,
                ];
            })
            ->filter()
            ->values();
    }

    public function subtotal(): float
    {
        return (float) $this->resolved()->sum('line_total');
    }
}
