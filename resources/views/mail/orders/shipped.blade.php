<x-mail::message>
# Il tuo ordine è in viaggio!

Ciao {{ $order->first_name }}, l'ordine **{{ $order->number }}** è stato spedito.

@if ($order->tracking_number)
Numero di tracking: **{{ $order->tracking_number }}**
@endif

@if ($order->user_id)
<x-mail::button :url="route('account.orders.show', $order)">
Segui il tuo ordine
</x-mail::button>
@endif

Grazie,<br>
{{ config('shop.name') }}
</x-mail::message>
