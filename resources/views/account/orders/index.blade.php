@extends('layouts.app')

@section('title', 'I miei ordini')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">I miei ordini</h1>

    @if ($orders->isEmpty())
        <p class="text-stone-600">Non hai ancora effettuato ordini.</p>
    @else
        <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
            <table class="w-full text-sm">
                <thead class="border-b border-stone-200 text-left text-stone-500">
                    <tr>
                        <th class="p-4 font-medium">Ordine</th>
                        <th class="p-4 font-medium">Data</th>
                        <th class="p-4 font-medium">Articoli</th>
                        <th class="p-4 font-medium">Stato</th>
                        <th class="p-4 text-right font-medium">Totale</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-stone-50">
                            <td class="p-4"><a href="{{ route('account.orders.show', $order) }}" class="font-medium underline">{{ $order->number }}</a></td>
                            <td class="p-4">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="p-4">{{ $order->items_count }}</td>
                            <td class="p-4"><span class="rounded-full px-2 py-0.5 text-xs {{ $order->status->color() }}">{{ $order->status->label() }}</span></td>
                            <td class="p-4 text-right">@money($order->total)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
@endsection
