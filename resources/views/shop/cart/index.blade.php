@extends('layouts.app')

@section('title', 'Carrello')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Il tuo carrello</h1>

    @if (! $cart || $cart->items->isEmpty())
        <div class="rounded-xl border border-stone-200 bg-white p-10 text-center">
            <p class="text-stone-600">Il carrello è vuoto.</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block rounded-lg bg-stone-900 px-5 py-2.5 text-white hover:bg-stone-700">
                Continua lo shopping
            </a>
        </div>
    @else
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="divide-y divide-stone-200 rounded-xl border border-stone-200 bg-white lg:col-span-2">
                @foreach ($cart->items as $item)
                    <div class="flex items-center gap-4 p-4">
                        <img src="{{ $item->product->image_url }}" alt="" class="h-20 w-20 rounded-lg object-cover">

                        <div class="flex-1">
                            <a href="{{ route('products.show', $item->product) }}" class="font-medium hover:underline">{{ $item->product->name }}</a>
                            <p class="text-sm text-stone-500">@money($item->product->price) cad.</p>
                        </div>

                        <form action="{{ route('cart.update', $item->product) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="{{ min(99, $item->product->stock) }}"
                                   class="w-16 rounded-lg border border-stone-300 px-2 py-1 text-sm">
                            <button class="text-sm text-stone-600 hover:underline">Aggiorna</button>
                        </form>

                        <p class="w-24 text-right font-medium">@money($item->lineTotal())</p>

                        <form action="{{ route('cart.destroy', $item->product) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-stone-400 hover:text-red-600" aria-label="Rimuovi">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <aside class="space-y-4">
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    @if ($cart->coupon)
                        <div class="mb-4 flex items-center justify-between rounded-lg bg-green-50 px-3 py-2 text-sm text-green-800">
                            <span>Coupon <strong>{{ $cart->coupon->code }}</strong></span>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="hover:underline">Rimuovi</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mb-4 flex gap-2">
                            @csrf
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="Codice sconto"
                                   class="flex-1 rounded-lg border border-stone-300 px-3 py-2 text-sm uppercase">
                            <button class="rounded-lg border border-stone-900 px-3 py-2 text-sm hover:bg-stone-100">Applica</button>
                        </form>
                    @endif

                    @include('partials.totals', ['totals' => $totals])

                    <a href="{{ route('checkout.create') }}"
                       class="mt-5 block rounded-lg bg-stone-900 px-5 py-3 text-center font-medium text-white hover:bg-stone-700">
                        Procedi al checkout
                    </a>
                </div>
            </aside>
        </div>
    @endif
@endsection
