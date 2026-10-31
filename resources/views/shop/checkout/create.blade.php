@extends('layouts.app')

@section('title', 'Checkout')

@php
    $field = fn (string $name) => old($name, $defaults[$name] ?? null);
    $input = 'w-full rounded-lg border border-stone-300 px-3 py-2 focus:border-stone-500 focus:outline-none';
@endphp

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST" class="grid gap-8 lg:grid-cols-3">
        @csrf

        <div class="space-y-6 lg:col-span-2">
            @guest
                <p class="rounded-lg bg-stone-100 px-4 py-3 text-sm">
                    Hai già un account? <a href="{{ route('login') }}" class="font-medium underline">Accedi</a> per ritrovare i tuoi ordini.
                </p>
            @endguest

            <fieldset class="rounded-xl border border-stone-200 bg-white p-6">
                <legend class="px-2 font-semibold">Contatti</legend>
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="sm:col-span-2">
                        <span class="mb-1 block text-sm">Email *</span>
                        <input type="email" name="email" value="{{ $field('email') }}" required class="{{ $input }}">
                    </label>
                    <label>
                        <span class="mb-1 block text-sm">Nome *</span>
                        <input type="text" name="first_name" value="{{ $field('first_name') }}" required class="{{ $input }}">
                    </label>
                    <label>
                        <span class="mb-1 block text-sm">Cognome *</span>
                        <input type="text" name="last_name" value="{{ $field('last_name') }}" required class="{{ $input }}">
                    </label>
                    <label class="sm:col-span-2">
                        <span class="mb-1 block text-sm">Telefono</span>
                        <input type="tel" name="phone" value="{{ $field('phone') }}" class="{{ $input }}">
                    </label>
                </div>
            </fieldset>

            <fieldset class="rounded-xl border border-stone-200 bg-white p-6">
                <legend class="px-2 font-semibold">Indirizzo di spedizione</legend>
                <div class="grid gap-4 sm:grid-cols-6">
                    <label class="sm:col-span-6">
                        <span class="mb-1 block text-sm">Indirizzo *</span>
                        <input type="text" name="address_line1" value="{{ $field('address_line1') }}" required class="{{ $input }}">
                    </label>
                    <label class="sm:col-span-6">
                        <span class="mb-1 block text-sm">Presso, scala, interno</span>
                        <input type="text" name="address_line2" value="{{ $field('address_line2') }}" class="{{ $input }}">
                    </label>
                    <label class="sm:col-span-3">
                        <span class="mb-1 block text-sm">Città *</span>
                        <input type="text" name="city" value="{{ $field('city') }}" required class="{{ $input }}">
                    </label>
                    <label class="sm:col-span-1">
                        <span class="mb-1 block text-sm">Prov.</span>
                        <input type="text" name="province" value="{{ $field('province') }}" maxlength="4" class="{{ $input }} uppercase">
                    </label>
                    <label class="sm:col-span-2">
                        <span class="mb-1 block text-sm">CAP *</span>
                        <input type="text" name="postal_code" value="{{ $field('postal_code') }}" required class="{{ $input }}">
                    </label>
                    <label class="sm:col-span-3">
                        <span class="mb-1 block text-sm">Paese *</span>
                        <select name="country" class="{{ $input }}">
                            @foreach (['IT' => 'Italia', 'SM' => 'San Marino', 'CH' => 'Svizzera', 'FR' => 'Francia', 'DE' => 'Germania', 'ES' => 'Spagna', 'AT' => 'Austria'] as $code => $label)
                                <option value="{{ $code }}" @selected($field('country') === $code)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="sm:col-span-6">
                        <span class="mb-1 block text-sm">Note per il corriere</span>
                        <textarea name="notes" rows="2" class="{{ $input }}">{{ old('notes') }}</textarea>
                    </label>
                </div>
            </fieldset>
        </div>

        <aside>
            <div class="sticky top-6 rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="mb-4 font-semibold">Riepilogo</h2>
                <ul class="mb-4 space-y-2 text-sm">
                    @foreach ($cart->items as $item)
                        <li class="flex justify-between gap-3">
                            <span>{{ $item->quantity }}× {{ $item->product->name }}</span>
                            <span class="shrink-0">@money($item->lineTotal())</span>
                        </li>
                    @endforeach
                </ul>

                @include('partials.totals', ['totals' => $totals])

                <label class="mt-5 flex items-start gap-2 text-sm">
                    <input type="checkbox" name="accept_terms" value="1" @checked(old('accept_terms')) class="mt-1">
                    <span>Accetto i termini e le condizioni di vendita e l'informativa privacy *</span>
                </label>

                <button class="mt-5 w-full rounded-lg bg-stone-900 px-5 py-3 font-medium text-white hover:bg-stone-700">
                    Paga @money($totals->total)
                </button>
            </div>
        </aside>
    </form>
@endsection
