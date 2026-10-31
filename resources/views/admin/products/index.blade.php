@extends('layouts.admin')

@section('title', 'Prodotti')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Prodotti</h1>
        <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-sm font-medium text-white hover:bg-stone-700">+ Nuovo prodotto</a>
    </div>

    <form method="GET" class="mb-4 flex gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Nome o SKU"
               class="w-64 rounded-lg border border-stone-300 px-3 py-2 text-sm">
        <select name="category" class="rounded-lg border border-stone-300 px-3 py-2 text-sm">
            <option value="">Tutte le categorie</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm">Filtra</button>
    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-stone-100 text-left text-stone-500">
                <tr>
                    <th class="p-4 font-medium">Prodotto</th>
                    <th class="p-4 font-medium">SKU</th>
                    <th class="p-4 font-medium">Categoria</th>
                    <th class="p-4 text-right font-medium">Prezzo</th>
                    <th class="p-4 text-right font-medium">Stock</th>
                    <th class="p-4 font-medium">Stato</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($products as $product)
                    <tr>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="" class="h-10 w-10 rounded object-cover">
                                <span class="font-medium">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-stone-500">{{ $product->sku }}</td>
                        <td class="p-4">{{ $product->category?->name ?? '—' }}</td>
                        <td class="p-4 text-right">@money($product->price)</td>
                        <td class="p-4 text-right {{ $product->stock === 0 ? 'text-red-600' : '' }}">{{ $product->stock }}</td>
                        <td class="p-4">{{ $product->is_active ? 'Attivo' : 'Bozza' }}</td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="underline">Modifica</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="ml-3 inline"
                                  onsubmit="return confirm('Eliminare questo prodotto?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 underline">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-stone-500">Nessun prodotto.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endsection
