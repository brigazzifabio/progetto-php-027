@extends('layouts.app')

@section('title', $category?->name ?? 'Prodotti')

@section('content')
    <div class="flex flex-col gap-8 md:flex-row">
        <aside class="md:w-56 md:shrink-0">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-stone-500">Categorie</h2>
            <ul class="space-y-1 text-sm">
                <li>
                    <a href="{{ route('products.index') }}"
                       class="block rounded px-2 py-1 {{ $category ? 'hover:bg-stone-100' : 'bg-stone-900 text-white' }}">Tutte</a>
                </li>
                @foreach ($categories as $cat)
                    <li>
                        <a href="{{ route('categories.show', $cat) }}"
                           class="block rounded px-2 py-1 {{ $category?->is($cat) ? 'bg-stone-900 text-white' : 'hover:bg-stone-100' }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="flex-1">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">{{ $category?->name ?? 'Tutti i prodotti' }}</h1>
                    @if (request('q'))
                        <p class="text-sm text-stone-500">Risultati per “{{ request('q') }}”</p>
                    @endif
                </div>

                <form method="GET" class="flex items-center gap-3 text-sm">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <label class="flex items-center gap-1">
                        <input type="checkbox" name="in_stock" value="1" @checked(request()->boolean('in_stock')) onchange="this.form.submit()">
                        Solo disponibili
                    </label>
                    <select name="sort" onchange="this.form.submit()" class="rounded-lg border border-stone-300 px-2 py-1.5">
                        <option value="newest" @selected($sort === 'newest')>Più recenti</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Prezzo crescente</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Prezzo decrescente</option>
                        <option value="name" @selected($sort === 'name')>Nome A–Z</option>
                    </select>
                </form>
            </div>

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="col-span-full text-stone-500">Nessun prodotto trovato.</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $products->links() }}</div>
        </div>
    </div>
@endsection
