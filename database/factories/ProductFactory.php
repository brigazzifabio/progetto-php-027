<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->words(3, true));
        $price = fake()->numberBetween(500, 25000);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(5)),
            'sku' => strtoupper(Str::random(3)).'-'.fake()->unique()->numerify('#####'),
            'short_description' => fake()->sentence(14),
            'description' => fake()->paragraphs(3, true),
            'price' => $price,
            'compare_at_price' => fake()->boolean(25) ? (int) round($price * 1.3) : null,
            'stock' => fake()->numberBetween(0, 100),
            'image_path' => null,
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
