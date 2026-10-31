@props(['product'])

<article class="group flex flex-col overflow-hidden rounded-xl border border-stone-200 bg-white">
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-square overflow-hidden bg-stone-100">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"
             class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        @if ($product->isOnSale())
            <span class="absolute left-3 top-3 rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">Offerta</span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-4">
        <a href="{{ route('products.show', $product) }}" class="font-medium leading-snug hover:underline">{{ $product->name }}</a>

        <div class="mt-2 flex items-baseline gap-2">
            <span class="font-semibold">@money($product->price)</span>
            @if ($product->isOnSale())
                <span class="text-sm text-stone-400 line-through">@money($product->compare_at_price)</span>
            @endif
        </div>

        <div class="mt-auto pt-4">
            @if ($product->isInStock())
                <form action="{{ route('cart.store', $product) }}" method="POST">
                    @csrf
                    <button class="w-full rounded-lg bg-stone-900 px-3 py-2 text-sm font-medium text-white hover:bg-stone-700">
                        Aggiungi al carrello
                    </button>
                </form>
            @else
                <p class="rounded-lg bg-stone-100 px-3 py-2 text-center text-sm text-stone-500">Esaurito</p>
            @endif
        </div>
    </div>
</article>
