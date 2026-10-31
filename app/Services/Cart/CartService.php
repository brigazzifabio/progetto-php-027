<?php

namespace App\Services\Cart;

use App\Exceptions\InsufficientStockException;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Gestisce il carrello corrente: dell'utente loggato oppure dell'ospite (token in sessione).
 */
class CartService
{
    public const SESSION_KEY = 'cart_token';

    private ?Cart $cart = null;

    public function __construct(private readonly Session $session) {}

    /** Carrello corrente; lo crea solo se $create è true. */
    public function current(bool $create = false): ?Cart
    {
        if ($this->cart) {
            return $this->cart;
        }

        $user = Auth::user();

        if ($user) {
            $this->cart = $create
                ? Cart::firstOrCreate(['user_id' => $user->id])
                : Cart::where('user_id', $user->id)->first();
        } else {
            $token = $this->session->get(self::SESSION_KEY);
            $this->cart = $token ? Cart::where('token', $token)->first() : null;

            if (! $this->cart && $create) {
                $token = Str::random(40);
                $this->session->put(self::SESSION_KEY, $token);
                $this->cart = Cart::create(['token' => $token]);
            }
        }

        $this->cart?->load(['items.product', 'coupon']);

        return $this->cart;
    }

    public function add(Product $product, int $quantity = 1): void
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('La quantità deve essere almeno 1.');
        }

        $cart = $this->current(create: true);
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $newQuantity = ($item->exists ? $item->quantity : 0) + $quantity;

        $this->ensureStock($product, $newQuantity);

        $item->quantity = $newQuantity;
        $item->save();

        $this->refresh();
    }

    public function update(Product $product, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($product);

            return;
        }

        $cart = $this->current();
        if (! $cart) {
            return;
        }

        $this->ensureStock($product, $quantity);
        $cart->items()->where('product_id', $product->id)->update(['quantity' => $quantity]);

        $this->refresh();
    }

    public function remove(Product $product): void
    {
        $this->current()?->items()->where('product_id', $product->id)->delete();
        $this->refresh();
    }

    public function clear(): void
    {
        $cart = $this->current();
        if (! $cart) {
            return;
        }

        $cart->items()->delete();
        $cart->update(['coupon_id' => null]);
        $this->refresh();
    }

    /** @return string|null messaggio d'errore, null se applicato correttamente */
    public function applyCoupon(string $code): ?string
    {
        $cart = $this->current();
        if (! $cart || $cart->items->isEmpty()) {
            return 'Il carrello è vuoto.';
        }

        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();
        if (! $coupon) {
            return 'Codice coupon non valido.';
        }

        if ($reason = $coupon->invalidReason($this->subtotal())) {
            return $reason;
        }

        $cart->update(['coupon_id' => $coupon->id]);
        $this->refresh();

        return null;
    }

    public function removeCoupon(): void
    {
        $this->current()?->update(['coupon_id' => null]);
        $this->refresh();
    }

    public function subtotal(): int
    {
        return (int) ($this->current()?->items->sum(fn ($item) => $item->lineTotal()) ?? 0);
    }

    public function count(): int
    {
        return (int) ($this->current()?->items->sum('quantity') ?? 0);
    }

    public function totals(): CartTotals
    {
        $subtotal = $this->subtotal();
        $discount = $this->current()?->coupon?->discountFor($subtotal) ?? 0;

        return CartTotals::calculate($subtotal, $discount, $this->count());
    }

    /**
     * Al login unisce il carrello ospite a quello dell'utente.
     */
    public function mergeGuestCartInto(User $user): void
    {
        $token = $this->session->pull(self::SESSION_KEY);
        if (! $token) {
            return;
        }

        $guestCart = Cart::with('items.product')->where('token', $token)->first();
        if (! $guestCart) {
            return;
        }

        DB::transaction(function () use ($guestCart, $user) {
            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

            foreach ($guestCart->items as $guestItem) {
                $item = $userCart->items()->firstOrNew(['product_id' => $guestItem->product_id]);
                $wanted = ($item->exists ? $item->quantity : 0) + $guestItem->quantity;
                $item->quantity = min($wanted, max(1, $guestItem->product->stock));
                $item->save();
            }

            if ($guestCart->coupon_id && ! $userCart->coupon_id) {
                $userCart->update(['coupon_id' => $guestCart->coupon_id]);
            }

            $guestCart->delete();
        });

        $this->cart = null;
    }

    private function ensureStock(Product $product, int $quantity): void
    {
        if (! $product->is_active || ! $product->isInStock($quantity)) {
            throw new InsufficientStockException($product, $quantity);
        }
    }

    private function refresh(): void
    {
        $this->cart?->refresh()->load(['items.product', 'coupon']);
    }
}
