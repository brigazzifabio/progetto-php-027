<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Stripe Checkout via API REST (nessun SDK richiesto).
 * Il cliente viene reindirizzato alla pagina di pagamento Stripe;
 * l'ordine viene segnato come pagato dal webhook "checkout.session.completed".
 *
 * .env: STRIPE_SECRET, STRIPE_WEBHOOK_SECRET
 */
class StripePaymentGateway implements PaymentGateway
{
    public function __construct(private readonly string $secret) {}

    public function name(): string
    {
        return 'stripe';
    }

    public function pay(Order $order): PaymentResult
    {
        $order->loadMissing('items');

        // Una sola riga col totale ordine: IVA, sconti e spedizione sono già calcolati da noi.
        $payload = [
            'mode' => 'payment',
            'client_reference_id' => $order->number,
            'customer_email' => $order->email,
            'success_url' => route('checkout.success', $order).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', $order),
            'metadata' => ['order_number' => $order->number],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => strtolower($order->currency),
                    'unit_amount' => $order->total,
                    'product_data' => [
                        'name' => 'Ordine '.$order->number,
                        'description' => Str::limit(
                            $order->items->map(fn ($i) => "{$i->quantity}× {$i->product_name}")->implode(', '),
                            250,
                        ),
                    ],
                ],
            ]],
        ];

        $response = Http::withToken($this->secret)
            ->asForm()
            ->post('https://api.stripe.com/v1/checkout/sessions', $payload);

        if ($response->failed()) {
            return PaymentResult::failed($response->json('error.message', 'Errore di comunicazione con Stripe.'));
        }

        return PaymentResult::redirect($response->json('url'), $response->json('id'));
    }

    /** Verifica la firma dell'header Stripe-Signature. */
    public static function verifyWebhookSignature(string $payload, ?string $header, string $secret, int $tolerance = 300): bool
    {
        if (! $header) {
            return false;
        }

        $parts = collect(explode(',', $header))
            ->mapWithKeys(function (string $part) {
                [$key, $value] = array_pad(explode('=', $part, 2), 2, null);

                return [$key => $value];
            });

        $timestamp = (int) $parts->get('t');
        $signature = $parts->get('v1');

        if (! $timestamp || ! $signature || abs(time() - $timestamp) > $tolerance) {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        return hash_equals($expected, $signature);
    }
}
