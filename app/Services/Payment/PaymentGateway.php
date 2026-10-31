<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentGateway
{
    /** Nome del provider salvato sull'ordine (es. "fake", "stripe"). */
    public function name(): string;

    /**
     * Avvia il pagamento di un ordine.
     * Può completarlo subito (PaymentResult::paid) oppure richiedere un redirect
     * verso una pagina esterna (PaymentResult::redirect), con conferma via webhook.
     */
    public function pay(Order $order): PaymentResult;
}
