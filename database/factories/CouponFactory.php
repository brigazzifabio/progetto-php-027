<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'type' => 'percent',
            'value' => 10,
            'min_subtotal' => 0,
            'max_uses' => null,
            'times_used' => 0,
            'starts_at' => null,
            'expires_at' => now()->addMonth(),
            'is_active' => true,
        ];
    }

    public function fixed(int $cents): static
    {
        return $this->state(fn () => ['type' => 'fixed', 'value' => $cents]);
    }

    public function expired(): static
    {
        return $this->state(fn () => ['expires_at' => now()->subDay()]);
    }
}
