@extends('layouts.app')

@section('content')
    <section class="mb-12 rounded-2xl bg-stone-900 px-8 py-16 text-white">
        <h1 class="max-w-xl text-4xl font-bold tracking-tight">Benvenuto su {{ config('shop.name') }}</h1>
        <p class="mt-4 max-w-lg text-stone-300">Spedizione gratuita sopra @money(config('shop.free_shipping_threshold')). Resi entro 30 giorni.</p>
        <a href="{{ route('products.index') }}" class="mt-8 inline-block rounded-lg bg-white px-5 py-3 font-medium text-stone-900 hover:bg-stone-200">
            Scopri il catalogo
        </a>
    </section>

    @if ($categories->isNotEmpty())
        <section class="mb-12">
            <h2 class="mb-4 text-xl font-semibold">Categorie</h2>
            <div class="flex flex-wrap gap-3">
                @foreach ($categories as $category)
                    <a href="{{ route('categories.show', $category) }}"
                       class="rounded-full border border-stone-300 bg-white px-4 py-2 text-sm hover:border-stone-900">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($featured->isNotEmpty())
        <section class="mb-12">
            <h2 class="mb-4 text-xl font-semibold">In evidenza</h2>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($featured as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <h2 class="mb-4 text-xl font-semibold">Novità</h2>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @forelse ($latest as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="text-stone-500">Nessun prodotto disponibile. Esegui <code>php artisan db:seed</code>.</p>
            @endforelse
        </div>
    </section>
@endsection
