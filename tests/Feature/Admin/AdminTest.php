<?php

namespace Tests\Feature\Admin;

use App\Enums\OrderStatus;
use App\Mail\OrderShipped;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_area(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_sees_dashboard(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_admin_can_create_product_with_euro_price_and_image(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.products.store'), [
                'name' => 'Maglia di lana',
                'sku' => 'MAG-001',
                'category_id' => $category->id,
                'price' => '49,90',
                'stock' => 12,
                'is_active' => '1',
                'image' => UploadedFile::fake()->image('maglia.jpg'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $product = Product::where('sku', 'MAG-001')->firstOrFail();
        $this->assertSame(4990, $product->price);
        $this->assertTrue($product->is_active);
        $this->assertNotEmpty($product->slug);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_admin_can_ship_order_with_tracking(): void
    {
        Mail::fake();
        $order = Order::factory()->create(['status' => OrderStatus::Paid]);

        $this->actingAs(User::factory()->admin()->create())
            ->patch(route('admin.orders.update', $order), [
                'status' => 'shipped',
                'tracking_number' => 'BRT123456',
            ])
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame(OrderStatus::Shipped, $order->status);
        $this->assertSame('BRT123456', $order->tracking_number);
        $this->assertNotNull($order->shipped_at);
        Mail::assertQueued(OrderShipped::class);
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::Cancelled]);

        $this->actingAs(User::factory()->admin()->create())
            ->patch(route('admin.orders.update', $order), ['status' => 'shipped', 'tracking_number' => 'X'])
            ->assertSessionHas('error');

        $this->assertSame(OrderStatus::Cancelled, $order->fresh()->status);
    }
}
