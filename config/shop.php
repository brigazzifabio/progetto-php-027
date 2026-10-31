<?php

return [
    'name' => env('SHOP_NAME', env('APP_NAME', 'Laravel Shop')),

    'currency' => env('SHOP_CURRENCY', 'EUR'),

    // Aliquota IVA in percentuale, applicata al subtotale scontato.
    // I prezzi dei prodotti sono intesi IVA esclusa.
    'tax_rate' => (float) env('SHOP_TAX_RATE', 22),

    // Spedizione in centesimi e soglia per la spedizione gratuita.
    'shipping_flat_rate' => (int) env('SHOP_SHIPPING_CENTS', 690),
    'free_shipping_threshold' => (int) env('SHOP_FREE_SHIPPING_CENTS', 5000),

    'per_page' => 12,

    // Driver di pagamento: "fake" (sviluppo) oppure "stripe".
    'payment_driver' => env('SHOP_PAYMENT_DRIVER', 'fake'),

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'admin_email' => env('SHOP_ADMIN_EMAIL', 'admin@example.com'),
];
