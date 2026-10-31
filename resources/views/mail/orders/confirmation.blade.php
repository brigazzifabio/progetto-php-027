<x-mail::message>
# Grazie per il tuo ordine, {{ $order->first_name }}!

Abbiamo ricevuto il pagamento dell'ordine **{{ $order->number }}**.

<x-mail::table>
| Prodotto | Qtà | Totale |
|:---------|:---:|-------:|
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ \App\Support\Money::format($item->line_total) }} |
@endforeach
| **Totale (IVA inclusa)** | | **{{ \App\Support\Money::format($order->total) }}** |
</x-mail::table>

**Spedizione a:**
{{ $order->customerName() }}, {{ $order->address_line1 }}, {{ $order->postal_code }} {{ $order->city }}

@if ($order->user_id)
<x-mail::button :url="route('account.orders.show', $order)">
Vedi il tuo ordine
</x-mail::button>
@endif

Grazie,<br>
{{ config('shop.name') }}
</x-mail::message>
