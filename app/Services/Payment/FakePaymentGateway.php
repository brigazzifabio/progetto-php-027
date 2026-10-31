<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Gateway per sviluppo e test: approva sempre il pagamento.
 * Per simulare un rifiuto, usare "fail@example.com" come email al checkout.
 */
class FakePaymentGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'fake';
    }

    public function pay(Order $order): PaymentResult
    {
        if ($order->email === 'fail@example.com') {
            return PaymentResult::failed('Pagamento rifiutato (simulazione).');
        }

        return PaymentResult::paid('fake_'.Str::uuid());
    }
}
