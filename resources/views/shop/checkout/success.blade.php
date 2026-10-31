@extends('layouts.app')

@section('title', 'Ordine confermato')

@section('content')
    <div class="mb-8 rounded-2xl bg-green-50 p-8 text-center">
        <h1 class="text-2xl font-bold text-green-900">Grazie per il tuo ordine!</h1>
        <p class="mt-2 text-green-800">
            Ordine <strong>{{ $order->number }}</strong> ·
            {{ $order->status->label() }}
        </p>
        <p class="mt-1 text-sm text-green-700">
            @if ($order->status === \App\Enums\OrderStatus::Pending)
                Stiamo attendendo la conferma del pagamento: riceverai un'email appena arriva.
            @else
                Ti abbiamo inviato un'email di conferma a {{ $order->email }}.
            @endif
        </p>
    </div>

    @include('partials.order-summary', ['order' => $order])

    <div class="mt-8 text-center">
        <a href="{{ route('products.index') }}" class="rounded-lg bg-stone-900 px-5 py-3 text-white hover:bg-stone-700">Continua lo shopping</a>
    </div>
@endsection
