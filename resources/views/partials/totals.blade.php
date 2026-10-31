{{-- Accetta $totals (CartTotals) oppure un Order: entrambi espongono subtotal/discount/shipping/tax/total. --}}
<dl class="space-y-2 text-sm">
    <div class="flex justify-between"><dt>Subtotale</dt><dd>@money($totals->subtotal)</dd></div>
    @if ($totals->discount > 0)
        <div class="flex justify-between text-green-700"><dt>Sconto</dt><dd>− @money($totals->discount)</dd></div>
    @endif
    <div class="flex justify-between">
        <dt>Spedizione</dt>
        <dd>{{ $totals->shipping === 0 ? 'Gratuita' : \App\Support\Money::format($totals->shipping) }}</dd>
    </div>
    <div class="flex justify-between"><dt>IVA ({{ rtrim(rtrim(number_format(config('shop.tax_rate'), 2, ',', ''), '0'), ',') }}%)</dt><dd>@money($totals->tax)</dd></div>
    <div class="flex justify-between border-t border-stone-200 pt-2 text-base font-semibold">
        <dt>Totale</dt><dd>@money($totals->total)</dd>
    </div>
</dl>
