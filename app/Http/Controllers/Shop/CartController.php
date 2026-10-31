<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartItemRequest;
use App\Models\Product;
use App\Services\Cart\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        return view('shop.cart.index', [
            'cart' => $this->cart->current(),
            'totals' => $this->cart->totals(),
        ]);
    }

    public function store(CartItemRequest $request, Product $product): RedirectResponse
    {
        $this->cart->add($product, $request->integer('quantity'));

        return back()->with('success', "\"{$product->name}\" aggiunto al carrello.");
    }

    public function update(CartItemRequest $request, Product $product): RedirectResponse
    {
        $this->cart->update($product, $request->integer('quantity'));

        return redirect()->route('cart.index')->with('success', 'Carrello aggiornato.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return redirect()->route('cart.index')->with('success', 'Prodotto rimosso.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'max:50']]);

        if ($error = $this->cart->applyCoupon($request->input('code'))) {
            return back()->withErrors(['code' => $error])->withInput();
        }

        return back()->with('success', 'Coupon applicato.');
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->cart->removeCoupon();

        return back()->with('success', 'Coupon rimosso.');
    }
}
