<?php

namespace Tests\Feature\Shop;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10, 'price' => 1000]);

        $this->post(route('cart.store', $product), ['quantity' => 2])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee($product->name);

        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_adding_same_product_twice_increments_quantity(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->post(route('cart.store', $product), ['quantity' => 1]);
        $this->post(route('cart.store', $product), ['quantity' => 3]);

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 4]);
    }

    public function test_cannot_add_more_than_available_stock(): void
    {
        $product = Product::factory()->create(['stock' => 2]);

        $this->post(route('cart.store', $product), ['quantity' => 5])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_updating_quantity_to_zero_removes_item(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $this->post(route('cart.store', $product));

        $this->patch(route('cart.update', $product), ['quantity' => 0])
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_valid_coupon_is_applied_and_expired_is_rejected(): void
    {
        $product = Product::factory()->create(['stock' => 10, 'price' => 10000]);
        Coupon::factory()->create(['code' => 'VALIDO', 'type' => 'percent', 'value' => 10]);
        Coupon::factory()->expired()->create(['code' => 'SCADUTO']);

        $this->post(route('cart.store', $product));

        $this->post(route('cart.coupon.apply'), ['code' => 'scaduto'])
            ->assertSessionHasErrors('code');

        $this->post(route('cart.coupon.apply'), ['code' => 'valido'])
            ->assertSessionHas('success');

        // 100,00 - 10% = 90,00 → mostrato nel riepilogo
        $this->get(route('cart.index'))->assertSee('VALIDO');
    }

    public function test_guest_cart_is_merged_into_user_cart_on_login(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->post(route('cart.store', $product), ['quantity' => 2]);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect();

        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
        $this->assertDatabaseCount('carts', 1);
    }
}
