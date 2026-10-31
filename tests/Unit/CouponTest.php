<?php

namespace Tests\Unit;

use App\Models\Coupon;
use PHPUnit\Framework\TestCase;

class CouponTest extends TestCase
{
    private function coupon(array $attributes): Coupon
    {
        return (new Coupon)->forceFill(array_merge([
            'code' => 'TEST',
            'type' => 'percent',
            'value' => 10,
            'min_subtotal' => 0,
            'max_uses' => null,
            'times_used' => 0,
            'starts_at' => null,
            'expires_at' => null,
            'is_active' => true,
        ], $attributes));
    }

    public function test_percent_discount(): void
    {
        $this->assertSame(1500, $this->coupon(['value' => 15])->discountFor(10000));
    }

    public function test_fixed_discount_never_exceeds_subtotal(): void
    {
        $this->assertSame(800, $this->coupon(['type' => 'fixed', 'value' => 2000])->discountFor(800));
    }

    public function test_minimum_subtotal_is_enforced(): void
    {
        $coupon = $this->coupon(['min_subtotal' => 5000]);

        $this->assertSame(0, $coupon->discountFor(4999));
        $this->assertNotNull($coupon->invalidReason(4999));
        $this->assertNull($coupon->invalidReason(5000));
    }

    public function test_usage_limit_is_enforced(): void
    {
        $coupon = $this->coupon(['max_uses' => 3, 'times_used' => 3]);

        $this->assertSame(0, $coupon->discountFor(10000));
    }
}
