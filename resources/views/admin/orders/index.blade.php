@extends('layouts.admin')

@section('title', 'Ordini')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Ordini</h1>

    <form method="GET" class="mb-4 flex gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Numero, email o cognome"
               class="w-72 rounded-lg border border-stone-300 px-3 py-2 text-sm">
        <select name="status" class="rounded-lg border border-stone-300 px-3 py-2 text-sm">
            <option value="">Tutti gli stati</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <button class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm">Filtra</button>
    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-stone-100 text-left text-stone-500">
                <tr>
                    <th class="p-4 font-medium">Ordine</th>
                    <th class="p-4 font-medium">Data</th>
                    <th class="p-4 font-medium">Cliente</th>
                    <th class="p-4 font-medium">Stato</th>
                    <th class="p-4 text-right font-medium">Totale</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($orders as $order)
                    <tr class="hover:bg-stone-50">
                        <td class="p-4"><a href="{{ route('admin.orders.show', $order) }}" class="font-medium underline">{{ $order->number }}</a></td>
                        <td class="p-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4">{{ $order->customerName() }}<span class="block text-xs text-stone-400">{{ $order->email }}</span></td>
                        <td class="p-4"><span class="rounded-full px-2 py-0.5 text-xs {{ $order->status->color() }}">{{ $order->status->label() }}</span></td>
                        <td class="p-4 text-right">@money($order->total)</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-stone-500">Nessun ordine.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endsection
