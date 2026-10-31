<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\Payment\StripePaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, OrderService $orders): Response
    {
        $secret = (string) config('shop.stripe.webhook_secret');
        $payload = $request->getContent();

        if (! StripePaymentGateway::verifyWebhookSignature($payload, $request->header('Stripe-Signature'), $secret)) {
            return response('Firma non valida', 400);
        }

        $event = json_decode($payload, true);
        $session = $event['data']['object'] ?? [];
        $order = Order::where('number', $session['client_reference_id'] ?? null)->first();

        if (! $order) {
            return response('Ordine non trovato', 200); // 200: non far ritentare Stripe
        }

        match ($event['type'] ?? null) {
            'checkout.session.completed' => ($session['payment_status'] ?? null) === 'paid'
                ? $orders->markPaid($order, 'stripe', $session['payment_intent'] ?? $session['id'])
                : null,
            'checkout.session.expired' => $order->status === OrderStatus::Pending
                ? $orders->changeStatus($order, OrderStatus::Cancelled)
                : null,
            default => null,
        };

        return response('ok', 200);
    }
}
