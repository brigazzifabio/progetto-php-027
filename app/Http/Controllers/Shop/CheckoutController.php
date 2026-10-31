<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\OrderService;
use App\Services\Payment\PaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly OrderService $orders,
        private readonly PaymentGateway $gateway,
    ) {}

    public function create(Request $request): View|RedirectResponse
    {
        $cart = $this->cart->current();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Il carrello è vuoto.');
        }

        $user = $request->user();
        $lastOrder = $user?->orders()->latest()->first();

        return view('shop.checkout.create', [
            'cart' => $cart,
            'totals' => $this->cart->totals(),
            // Precompila con i dati dell'ultimo ordine dell'utente.
            'defaults' => [
                'email' => $user?->email,
                'first_name' => $lastOrder?->first_name ?? str($user?->name)->before(' ')->toString(),
                'last_name' => $lastOrder?->last_name,
                'phone' => $lastOrder?->phone,
                'address_line1' => $lastOrder?->address_line1,
                'address_line2' => $lastOrder?->address_line2,
                'city' => $lastOrder?->city,
                'province' => $lastOrder?->province,
                'postal_code' => $lastOrder?->postal_code,
                'country' => $lastOrder?->country ?? 'IT',
            ],
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = $this->cart->current();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Il carrello è vuoto.');
        }

        try {
            $order = $this->orders->createFromCart($cart, $request->validated(), $request->user());
        } catch (RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        // Permette all'ospite di vedere la pagina di conferma del proprio ordine.
        $request->session()->put('last_order', $order->number);

        $result = $this->gateway->pay($order);

        if ($result->isPaid()) {
            $this->orders->markPaid($order, $this->gateway->name(), $result->reference);

            return redirect()->route('checkout.success', $order);
        }

        if ($result->needsRedirect()) {
            $order->update([
                'payment_provider' => $this->gateway->name(),
                'payment_reference' => $result->reference,
            ]);

            return redirect()->away($result->redirectUrl);
        }

        // Pagamento fallito: annulla l'ordine e ripristina le giacenze.
        $this->orders->changeStatus($order, OrderStatus::Cancelled);

        return redirect()->route('cart.index')
            ->with('error', 'Pagamento non riuscito: '.$result->error.' Riprova.');
    }

    public function success(Request $request, Order $order): View
    {
        $this->authorizeOrderAccess($request, $order);

        return view('shop.checkout.success', ['order' => $order->load('items')]);
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrderAccess($request, $order);

        if ($order->status === OrderStatus::Pending) {
            $this->orders->changeStatus($order, OrderStatus::Cancelled);
        }

        return redirect()->route('cart.index')->with('error', 'Pagamento annullato. L\'ordine non è stato completato.');
    }

    private function authorizeOrderAccess(Request $request, Order $order): void
    {
        $ownsBySession = $request->session()->get('last_order') === $order->number;
        $ownsByUser = $request->user()?->can('view', $order) ?? false;

        abort_unless($ownsBySession || $ownsByUser, 403);
    }
}
