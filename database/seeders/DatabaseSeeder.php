<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => config('shop.admin_email'),
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@example.com',
            'password' => 'password',
        ]);

        $categories = collect(['Abbigliamento', 'Scarpe', 'Accessori', 'Casa', 'Elettronica'])
            ->map(fn (string $name, int $i) => Category::factory()->create([
                'name' => $name,
                'slug' => (string) str($name)->slug(),
                'position' => $i,
            ]));

        $categories->each(function (Category $category) {
            Product::factory()->count(8)->for($category)->create();
        });

        Coupon::factory()->create(['code' => 'BENVENUTO10', 'type' => 'percent', 'value' => 10]);
        Coupon::factory()->fixed(500)->create(['code' => 'SCONTO5', 'min_subtotal' => 3000]);
    }
}
