<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Exceptions\InsufficientStockException;
use App\Mail\OrderConfirmation;
use App\Mail\OrderShipped;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartTotals;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use RuntimeException;

class OrderService
{
    /**
     * Crea l'ordine dal carrello in modo atomico:
     * blocca le righe prodotto, verifica le giacenze, scala lo stock e svuota il carrello.
     *
     * @param  array<string, mixed>  $customer  dati validati da CheckoutRequest
     */
    public function createFromCart(Cart $cart, array $customer, ?User $user = null): Order
    {
        $cart->loadMissing('items', 'coupon');

        if ($cart->items->isEmpty()) {
            throw new RuntimeException('Il carrello è vuoto.');
        }

        return DB::transaction(function () use ($cart, $customer, $user) {
            // Lock pessimistico: evita di vendere più pezzi di quelli disponibili.
            $products = Product::whereIn('id', $cart->items->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $lines = [];
            $subtotal = 0;

            foreach ($cart->items as $item) {
                $product = $products->get($item->product_id);

                if (! $product) {
                    throw new RuntimeException('Un prodotto nel carrello non è più disponibile.');
                }

                if (! $product->is_active || ! $product->isInStock($item->quantity)) {
                    throw new InsufficientStockException($product, $item->quantity);
                }

                $lineTotal = $product->price * $item->quantity;
                $subtotal += $lineTotal;

                $lines[] = [
                    'product' => $product,
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                    'line_total' => $lineTotal,
                ];
            }

            $coupon = $cart->coupon ? Coupon::lockForUpdate()->find($cart->coupon_id) : null;
            $discount = $coupon?->discountFor($subtotal) ?? 0;
            $totals = CartTotals::calculate($subtotal, $discount, $cart->items->sum('quantity'));

            $order = Order::create([
                ...collect($customer)->only([
                    'email', 'first_name', 'last_name', 'phone', 'address_line1', 'address_line2',
                    'city', 'province', 'postal_code', 'country', 'notes',
                ])->all(),
                'user_id' => $user?->id,
                'coupon_id' => $discount > 0 ? $coupon?->id : null,
                'status' => OrderStatus::Pending,
                'subtotal' => $totals->subtotal,
                'discount' => $totals->discount,
                'shipping' => $totals->shipping,
                'tax' => $totals->tax,
                'total' => $totals->total,
                'currency' => config('shop.currency'),
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'product_name' => $line['product']->name,
                    'sku' => $line['product']->sku,
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'line_total' => $line['line_total'],
                ]);

                $line['product']->decrement('stock', $line['quantity']);
            }

            if ($discount > 0) {
                $coupon->increment('times_used');
            }

            $cart->items()->delete();
            $cart->update(['coupon_id' => null]);
            $cart->load(['items.product', 'coupon']); // evita relazioni "vecchie" in memoria

            return $order;
        });
    }

    public function markPaid(Order $order, string $provider, ?string $reference = null): void
    {
        if ($order->status !== OrderStatus::Pending) {
            return; // idempotente: i webhook possono arrivare più volte
        }

        $order->update([
            'status' => OrderStatus::Paid,
            'payment_provider' => $provider,
            'payment_reference' => $reference ?? $order->payment_reference,
            'paid_at' => now(),
        ]);

        Mail::to($order->email)->queue(new OrderConfirmation($order));
    }

    public function changeStatus(Order $order, OrderStatus $status, ?string $trackingNumber = null): void
    {
        if (! $order->status->canTransitionTo($status)) {
            throw new InvalidArgumentException(
                "Transizione non consentita: {$order->status->label()} → {$status->label()}."
            );
        }

        DB::transaction(function () use ($order, $status, $trackingNumber) {
            $data = ['status' => $status];

            if ($status === OrderStatus::Shipped) {
                $data['shipped_at'] = now();
                $data['tracking_number'] = $trackingNumber;
            }

            if (in_array($status, [OrderStatus::Cancelled, OrderStatus::Refunded], true)
                && $order->status !== OrderStatus::Delivered) {
                $this->restock($order);
            }

            $order->update($data);
        });

        if ($status === OrderStatus::Shipped) {
            Mail::to($order->email)->queue(new OrderShipped($order));
        }
    }

    private function restock(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::withTrashed()->whereKey($item->product_id)->increment('stock', $item->quantity);
            }
        }
    }
}
