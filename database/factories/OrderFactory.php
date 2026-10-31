<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(2000, 30000);
        $tax = (int) round($subtotal * 0.22);

        return [
            'user_id' => User::factory(),
            'status' => OrderStatus::Paid,
            'email' => fake()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'address_line1' => fake()->streetAddress(),
            'city' => fake()->city(),
            'province' => 'MI',
            'postal_code' => fake()->numerify('20###'),
            'country' => 'IT',
            'subtotal' => $subtotal,
            'discount' => 0,
            'shipping' => 0,
            'tax' => $tax,
            'total' => $subtotal + $tax,
            'currency' => 'EUR',
            'payment_provider' => 'fake',
            'payment_reference' => 'fake_'.fake()->uuid(),
            'paid_at' => now(),
        ];
    }
}
