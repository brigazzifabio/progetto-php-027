@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Dashboard</h1>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Fatturato del mese', \App\Support\Money::format((int) $stats['revenue_month'])],
            ['Ordini del mese', $stats['orders_month']],
            ['Da spedire', $stats['to_ship']],
            ['Clienti registrati', $stats['customers']],
        ] as [$label, $value])
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">{{ $label }}</p>
                <p class="mt-1 text-2xl font-semibold">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-xl bg-white shadow-sm lg:col-span-2">
            <h2 class="border-b border-stone-100 px-5 py-4 font-semibold">Ultimi ordini</h2>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-stone-100">
                    @forelse ($latestOrders as $order)
                        <tr>
                            <td class="px-5 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-medium underline">{{ $order->number }}</a></td>
                            <td class="px-5 py-3">{{ $order->customerName() }}</td>
                            <td class="px-5 py-3"><span class="rounded-full px-2 py-0.5 text-xs {{ $order->status->color() }}">{{ $order->status->label() }}</span></td>
                            <td class="px-5 py-3 text-right">@money($order->total)</td>
                        </tr>
                    @empty
                        <tr><td class="px-5 py-6 text-stone-500">Nessun ordine.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="rounded-xl bg-white shadow-sm">
            <h2 class="border-b border-stone-100 px-5 py-4 font-semibold">Scorte basse</h2>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($lowStock as $product)
                    <li class="flex justify-between px-5 py-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="hover:underline">{{ $product->name }}</a>
                        <span class="{{ $product->stock === 0 ? 'text-red-600' : 'text-orange-600' }}">{{ $product->stock }}</span>
                    </li>
                @empty
                    <li class="px-5 py-6 text-stone-500">Tutto in ordine.</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection
