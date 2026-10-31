@extends('layouts.admin')

@section('title', 'Ordine '.$order->number)

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-stone-500 hover:underline">← Ordini</a>

    <div class="mb-6 mt-2 flex flex-wrap items-center gap-3">
        <h1 class="text-2xl font-bold">Ordine {{ $order->number }}</h1>
        <span class="rounded-full px-2 py-0.5 text-xs {{ $order->status->color() }}">{{ $order->status->label() }}</span>
    </div>

    <div class="mb-6 grid gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-5 text-sm shadow-sm">
            <h2 class="mb-2 font-semibold">Cliente</h2>
            <p>{{ $order->customerName() }}</p>
            <p><a href="mailto:{{ $order->email }}" class="underline">{{ $order->email }}</a></p>
            @if ($order->phone)<p>{{ $order->phone }}</p>@endif
            <p class="mt-2 text-stone-500">{{ $order->user ? 'Cliente registrato' : 'Ospite' }}</p>
            @if ($order->notes)
                <p class="mt-3"><span class="text-stone-500">Note:</span> {{ $order->notes }}</p>
            @endif
        </div>

        <div class="rounded-xl bg-white p-5 text-sm shadow-sm">
            <h2 class="mb-2 font-semibold">Pagamento</h2>
            <p>Provider: {{ $order->payment_provider ?? '—' }}</p>
            <p class="break-all">Riferimento: {{ $order->payment_reference ?? '—' }}</p>
            <p>Pagato il: {{ $order->paid_at?->format('d/m/Y H:i') ?? '—' }}</p>
            @if ($order->coupon)
                <p class="mt-2">Coupon: <strong>{{ $order->coupon->code }}</strong></p>
            @endif
        </div>

        <div class="rounded-xl bg-white p-5 text-sm shadow-sm">
            <h2 class="mb-2 font-semibold">Aggiorna stato</h2>
            @if (count($order->status->allowedTransitions()) > 0)
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full rounded-lg border border-stone-300 px-3 py-2">
                        @foreach ($order->status->allowedTransitions() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}"
                           placeholder="Numero di tracking (obbligatorio se spedito)"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2">
                    <button class="w-full rounded-lg bg-stone-900 px-4 py-2 font-medium text-white hover:bg-stone-700">Aggiorna</button>
                </form>
            @else
                <p class="text-stone-500">Stato finale: nessuna azione disponibile.</p>
            @endif
        </div>
    </div>

    @include('partials.order-summary', ['order' => $order])
@endsection
