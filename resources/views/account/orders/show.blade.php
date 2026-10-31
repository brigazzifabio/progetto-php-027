@extends('layouts.app')

@section('title', 'Ordine '.$order->number)

@section('content')
    <a href="{{ route('account.orders.index') }}" class="text-sm text-stone-500 hover:underline">← I miei ordini</a>

    <div class="mb-6 mt-2 flex flex-wrap items-center gap-3">
        <h1 class="text-2xl font-bold">Ordine {{ $order->number }}</h1>
        <span class="rounded-full px-2 py-0.5 text-xs {{ $order->status->color() }}">{{ $order->status->label() }}</span>
        <span class="text-sm text-stone-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
    </div>

    @include('partials.order-summary', ['order' => $order])
@endsection
