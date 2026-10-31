<?php

namespace App\Support;

/**
 * Tutti i prezzi sono salvati in centesimi (int) per evitare errori di arrotondamento.
 */
final class Money
{
    public static function format(int $cents, ?string $currency = null): string
    {
        $currency ??= config('shop.currency', 'EUR');
        $symbol = match ($currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $currency.' ',
        };

        return $symbol.' '.number_format($cents / 100, 2, ',', '.');
    }

    public static function toCents(string|float|int $amount): int
    {
        $normalized = str_replace(',', '.', (string) $amount);

        return (int) round(((float) $normalized) * 100);
    }
}
