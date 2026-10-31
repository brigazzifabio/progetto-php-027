<?php

namespace App\Services\Cart;

final readonly class CartTotals
{
    public function __construct(
        public int $subtotal,
        public int $discount,
        public int $shipping,
        public int $tax,
        public int $total,
        public int $itemCount,
    ) {}

    public static function calculate(int $subtotal, int $discount, int $itemCount): self
    {
        $discounted = max(0, $subtotal - $discount);

        $shipping = ($itemCount === 0 || $discounted >= config('shop.free_shipping_threshold'))
            ? 0
            : (int) config('shop.shipping_flat_rate');

        $tax = (int) round($discounted * ((float) config('shop.tax_rate')) / 100);

        return new self(
            subtotal: $subtotal,
            discount: $discount,
            shipping: $shipping,
            tax: $tax,
            total: $discounted + $shipping + $tax,
            itemCount: $itemCount,
        );
    }
}
