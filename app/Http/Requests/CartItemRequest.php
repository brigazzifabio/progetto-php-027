<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // In aggiornamento la quantità 0 significa "rimuovi".
        $min = $this->isMethod('patch') ? 0 : 1;

        return [
            'quantity' => ['required', 'integer', "min:{$min}", 'max:99'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('quantity')) {
            $this->merge(['quantity' => 1]);
        }
    }
}
