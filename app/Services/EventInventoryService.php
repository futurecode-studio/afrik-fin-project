<?php

namespace App\Services;

use App\Models\EventOrder;
use App\Models\EventProductVariant;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventInventoryService
{
    /**
     * Réserver du stock pour une durée limitée (panier).
     */
    public function reserve(EventProductVariant $variant, int $qty): bool
    {
        return DB::transaction(function () use ($variant, $qty) {
            $variant->lockForUpdate()->refresh();
            if ($variant->availableQuantity() < $qty) {
                return false;
            }
            $variant->increment('reserved_quantity', $qty);
            return true;
        });
    }

    /**
     * Libérer une réservation (panier abandonné).
     */
    public function release(EventProductVariant $variant, int $qty): void
    {
        DB::transaction(function () use ($variant, $qty) {
            $variant->lockForUpdate()->refresh();
            $newReserved = max(0, $variant->reserved_quantity - $qty);
            $variant->update(['reserved_quantity' => $newReserved]);
        });
    }

    /**
     * Déduire le stock définitivement (après paiement).
     */
    public function deduct(EventProductVariant $variant, int $qty): void
    {
        DB::transaction(function () use ($variant, $qty) {
            $variant->lockForUpdate()->refresh();
            $variant->decrement('reserved_quantity', $qty);
            $variant->decrement('stock_quantity', $qty);
        });
    }

    /**
     * Vérifier disponibilité.
     */
    public function isAvailable(EventProductVariant $variant, int $qty): bool
    {
        return $variant->availableQuantity() >= $qty;
    }

    /**
     * Créer une commande à partir d'un panier.
     */
    public function createOrderFromCart(User $user, EventRegistration $registration, array $cartItems): EventOrder
    {
        return DB::transaction(function () use ($user, $registration, $cartItems) {
            $subtotal = 0;
            $orderNumber = 'EVT-' . strtoupper(Str::random(8)) . '-' . date('Ymd');

            $order = EventOrder::create([
                'event_id' => $registration->event_id,
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'order_number' => $orderNumber,
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0,
                'currency' => 'XOF',
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $variant = EventProductVariant::find($item['variant_id'] ?? null);
                $product = $variant?->product;
                if (!$variant || !$product || !$this->reserve($variant, $item['quantity'])) {
                    throw new \Exception("Stock insuffisant pour {$product?->name}.");
                }

                $lineTotal = $product->price * $item['quantity'];
                $subtotal += $lineTotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'product_name' => $product->name . ' — ' . $variant->variant_name,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'total_price' => $lineTotal,
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            return $order;
        });
    }

    /**
     * Finaliser une commande après paiement.
     */
    public function finalizeOrder(EventOrder $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = EventProductVariant::find($item->variant_id);
                    if ($variant) {
                        $this->deduct($variant, $item->quantity);
                    }
                }
            }
            $order->update(['status' => 'paid', 'paid_at' => now()]);
        });
    }

    /**
     * Annuler une commande et libérer les réservations.
     */
    public function cancelOrder(EventOrder $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = EventProductVariant::find($item->variant_id);
                    if ($variant) {
                        $this->release($variant, $item->quantity);
                    }
                }
            }
            $order->update(['status' => 'cancelled']);
        });
    }
}
