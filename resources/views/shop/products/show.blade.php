@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <nav class="mb-6 text-sm text-stone-500">
        <a href="{{ route('home') }}" class="hover:underline">Home</a> /
        @if ($product->category)
            <a href="{{ route('categories.show', $product->category) }}" class="hover:underline">{{ $product->category->name }}</a> /
        @endif
        <span class="text-stone-900">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-10 md:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-stone-200 bg-white">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="aspect-square w-full object-cover">
        </div>

        <div>
            <h1 class="text-3xl font-bold tracking-tight">{{ $product->name }}</h1>
            <p class="mt-1 text-sm text-stone-500">SKU: {{ $product->sku }}</p>

            <div class="mt-4 flex items-baseline gap-3">
                <span class="text-2xl font-semibold">@money($product->price)</span>
                @if ($product->isOnSale())
                    <span class="text-lg text-stone-400 line-through">@money($product->compare_at_price)</span>
                @endif
                <span class="text-sm text-stone-500">+ IVA</span>
            </div>

            @if ($product->short_description)
                <p class="mt-6 text-stone-700">{{ $product->short_description }}</p>
            @endif

            <div class="mt-8">
                @if ($product->isInStock())
                    <p class="mb-3 text-sm {{ $product->stock <= 5 ? 'text-orange-600' : 'text-green-700' }}">
                        {{ $product->stock <= 5 ? "Solo {$product->stock} rimasti" : 'Disponibile' }}
                    </p>
                    <form action="{{ route('cart.store', $product) }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="number" name="quantity" value="1" min="1" max="{{ min(99, $product->stock) }}"
                               class="w-20 rounded-lg border border-stone-300 px-3 py-2">
                        <button class="flex-1 rounded-lg bg-stone-900 px-5 py-3 font-medium text-white hover:bg-stone-700">
                            Aggiungi al carrello
                        </button>
                    </form>
                @else
                    <p class="rounded-lg bg-stone-100 px-4 py-3 text-stone-600">Prodotto esaurito</p>
                @endif
            </div>

            @if ($product->description)
                <div class="mt-10 border-t border-stone-200 pt-6">
                    <h2 class="mb-3 font-semibold">Descrizione</h2>
                    <div class="space-y-3 whitespace-pre-line text-stone-700">{{ $product->description }}</div>
                </div>
            @endif
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="mt-16">
            <h2 class="mb-4 text-xl font-semibold">Potrebbe interessarti</h2>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
