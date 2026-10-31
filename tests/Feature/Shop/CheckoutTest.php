<?php

namespace Tests\Feature\Shop;

use App\Enums\OrderStatus;
use App\Mail\OrderConfirmation;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        config([
            'shop.payment_driver' => 'fake',
            'shop.tax_rate' => 22,
            'shop.shipping_flat_rate' => 690,
            'shop.free_shipping_threshold' => 5000,
        ]);
    }

    private function customer(array $overrides = []): array
    {
        return array_merge([
            'email' => 'mario@example.com',
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'address_line1' => 'Via Roma 1',
            'city' => 'Milano',
            'province' => 'mi',
            'postal_code' => '20121',
            'country' => 'IT',
            'accept_terms' => '1',
        ], $overrides);
    }

    public function test_guest_can_complete_checkout(): void
    {
        $product = Product::factory()->create(['stock' => 5, 'price' => 2000]);
        $this->post(route('cart.store', $product), ['quantity' => 2]);

        $response = $this->post(route('checkout.store'), $this->customer());

        $order = Order::firstOrFail();
        $response->assertRedirect(route('checkout.success', $order));

        // 40,00 + spedizione 6,90 + IVA 22% su 40,00 (8,80) = 55,70
        $this->assertSame(OrderStatus::Paid, $order->status);
        $this->assertSame(4000, $order->subtotal);
        $this->assertSame(690, $order->shipping);
        $this->assertSame(880, $order->tax);
        $this->assertSame(5570, $order->total);
        $this->assertSame('MI', $order->province);

        $this->assertSame(3, $product->fresh()->stock);
        $this->assertDatabaseCount('cart_items', 0);
        Mail::assertQueued(OrderConfirmation::class, fn ($mail) => $mail->hasTo('mario@example.com'));

        $this->get(route('checkout.success', $order))->assertOk()->assertSee($order->number);
    }

    public function test_free_shipping_and_coupon_are_applied(): void
    {
        $product = Product::factory()->create(['stock' => 5, 'price' => 6000]);
        $coupon = Coupon::factory()->fixed(1000)->create(['code' => 'DIECI']);

        $this->post(route('cart.store', $product));
        $this->post(route('cart.coupon.apply'), ['code' => 'DIECI']);
        $this->post(route('checkout.store'), $this->customer());

        $order = Order::firstOrFail();
        // 60,00 - 10,00 = 50,00 → spedizione gratuita; IVA 11,00
        $this->assertSame(1000, $order->discount);
        $this->assertSame(0, $order->shipping);
        $this->assertSame(1100, $order->tax);
        $this->assertSame(6100, $order->total);
        $this->assertSame(1, $coupon->fresh()->times_used);
    }

    public function test_checkout_fails_if_stock_changed_meanwhile(): void
    {
        $product = Product::factory()->create(['stock' => 3]);
        $this->post(route('cart.store', $product), ['quantity' => 3]);

        $product->update(['stock' => 1]); // un altro cliente ha comprato nel frattempo

        $this->post(route('checkout.store'), $this->customer())->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $product->fresh()->stock);
    }

    public function test_failed_payment_cancels_order_and_restores_stock(): void
    {
        $product = Product::factory()->create(['stock' => 5]);
        $this->post(route('cart.store', $product), ['quantity' => 2]);

        $this->post(route('checkout.store'), $this->customer(['email' => 'fail@example.com']))
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error');

        $this->assertSame(OrderStatus::Cancelled, Order::firstOrFail()->status);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_checkout_requires_terms_and_address(): void
    {
        $product = Product::factory()->create(['stock' => 5]);
        $this->post(route('cart.store', $product));

        $this->post(route('checkout.store'), $this->customer(['accept_terms' => null, 'city' => '']))
            ->assertSessionHasErrors(['accept_terms', 'city']);
    }

    public function test_logged_user_sees_own_orders_but_not_others(): void
    {
        $user = User::factory()->create();
        $mine = Order::factory()->for($user)->create();
        $other = Order::factory()->create();

        $this->actingAs($user)->get(route('account.orders.index'))->assertOk()->assertSee($mine->number);
        $this->actingAs($user)->get(route('account.orders.show', $mine))->assertOk();
        $this->actingAs($user)->get(route('account.orders.show', $other))->assertForbidden();
    }
}
