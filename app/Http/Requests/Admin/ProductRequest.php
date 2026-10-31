<?php

namespace App\Http\Requests\Admin;

use App\Support\Money;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('products')->ignore($product)],
            'sku' => ['required', 'string', 'max:64', Rule::unique('products')->ignore($product)],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'gt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // I prezzi arrivano in euro con virgola ("19,90"): normalizziamo il separatore.
        $this->merge([
            'price' => str_replace(',', '.', (string) $this->input('price')),
            'compare_at_price' => $this->filled('compare_at_price')
                ? str_replace(',', '.', (string) $this->input('compare_at_price'))
                : null,
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    /** Dati pronti per il model, con i prezzi convertiti in centesimi. */
    public function productData(): array
    {
        $data = collect($this->validated())->except('image')->all();
        $data['price'] = Money::toCents($data['price']);
        $data['compare_at_price'] = isset($data['compare_at_price']) ? Money::toCents($data['compare_at_price']) : null;

        return $data;
    }
}
