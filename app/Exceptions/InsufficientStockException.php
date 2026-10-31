<?php

namespace App\Exceptions;

use App\Models\Product;
use Exception;
use Illuminate\Http\RedirectResponse;

class InsufficientStockException extends Exception
{
    public function __construct(public readonly Product $product, public readonly int $requested)
    {
        parent::__construct(sprintf(
            'Disponibilità insufficiente per "%s": richiesti %d, disponibili %d.',
            $product->name,
            $requested,
            max(0, $product->stock),
        ));
    }

    /** Laravel chiama render() automaticamente: torna indietro con un messaggio. */
    public function render(): RedirectResponse
    {
        return back()->with('error', $this->getMessage());
    }
}
