<div class="grid gap-6 md:grid-cols-3">
    <div class="rounded-xl border border-stone-200 bg-white md:col-span-2">
        <table class="w-full text-sm">
            <thead class="border-b border-stone-200 text-left text-stone-500">
                <tr>
                    <th class="p-4 font-medium">Prodotto</th>
                    <th class="p-4 text-right font-medium">Prezzo</th>
                    <th class="p-4 text-right font-medium">Qtà</th>
                    <th class="p-4 text-right font-medium">Totale</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="p-4">{{ $item->product_name }} <span class="block text-xs text-stone-400">{{ $item->sku }}</span></td>
                        <td class="p-4 text-right">@money($item->unit_price)</td>
                        <td class="p-4 text-right">{{ $item->quantity }}</td>
                        <td class="p-4 text-right font-medium">@money($item->line_total)</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="space-y-4">
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            @include('partials.totals', ['totals' => $order])
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5 text-sm">
            <h3 class="mb-2 font-semibold">Spedizione a</h3>
            <p>{{ $order->customerName() }}</p>
            <p>{{ $order->address_line1 }}</p>
            @if ($order->address_line2)<p>{{ $order->address_line2 }}</p>@endif
            <p>{{ $order->postal_code }} {{ $order->city }} {{ $order->province ? "({$order->province})" : '' }}</p>
            <p>{{ $order->country }}</p>
            @if ($order->tracking_number)
                <p class="mt-3"><span class="text-stone-500">Tracking:</span> {{ $order->tracking_number }}</p>
            @endif
        </div>
    </div>
</div>
