@extends('layouts.admin')

@section('title', $product->exists ? 'Modifica prodotto' : 'Nuovo prodotto')

@php
    $input = 'w-full rounded-lg border border-stone-300 px-3 py-2';
    $euros = fn (?int $cents) => $cents === null ? null : number_format($cents / 100, 2, ',', '');
@endphp

@section('content')
    <a href="{{ route('admin.products.index') }}" class="text-sm text-stone-500 hover:underline">← Prodotti</a>
    <h1 class="mb-6 mt-2 text-2xl font-bold">{{ $product->exists ? $product->name : 'Nuovo prodotto' }}</h1>

    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <div class="space-y-5 rounded-xl bg-white p-6 shadow-sm lg:col-span-2">
            <label class="block">
                <span class="mb-1 block text-sm font-medium">Nome *</span>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="{{ $input }}">
            </label>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">SKU *</span>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="{{ $input }}">
                </label>
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Slug <span class="font-normal text-stone-400">(auto se vuoto)</span></span>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="{{ $input }}">
                </label>
            </div>
            <label class="block">
                <span class="mb-1 block text-sm font-medium">Descrizione breve</span>
                <textarea name="short_description" rows="2" class="{{ $input }}">{{ old('short_description', $product->short_description) }}</textarea>
            </label>
            <label class="block">
                <span class="mb-1 block text-sm font-medium">Descrizione</span>
                <textarea name="description" rows="8" class="{{ $input }}">{{ old('description', $product->description) }}</textarea>
            </label>
        </div>

        <div class="space-y-6">
            <div class="space-y-4 rounded-xl bg-white p-6 shadow-sm">
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> Pubblicato
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> In evidenza in home
                </label>
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Categoria</span>
                    <select name="category_id" class="{{ $input }}">
                        <option value="">— Nessuna —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="space-y-4 rounded-xl bg-white p-6 shadow-sm">
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Prezzo (€, IVA esclusa) *</span>
                    <input type="text" inputmode="decimal" name="price" value="{{ old('price', $euros($product->price)) }}" required class="{{ $input }}">
                </label>
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Prezzo barrato (€)</span>
                    <input type="text" inputmode="decimal" name="compare_at_price" value="{{ old('compare_at_price', $euros($product->compare_at_price)) }}" class="{{ $input }}">
                </label>
                <label class="block">
                    <span class="mb-1 block text-sm font-medium">Quantità in magazzino *</span>
                    <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" required class="{{ $input }}">
                </label>
            </div>

            <div class="space-y-3 rounded-xl bg-white p-6 shadow-sm">
                <span class="block text-sm font-medium">Immagine</span>
                @if ($product->exists)
                    <img src="{{ $product->image_url }}" alt="" class="aspect-square w-full rounded-lg object-cover">
                @endif
                <input type="file" name="image" accept="image/*" class="text-sm">
            </div>

            <button class="w-full rounded-lg bg-stone-900 px-5 py-3 font-medium text-white hover:bg-stone-700">Salva</button>
        </div>
    </form>
@endsection
