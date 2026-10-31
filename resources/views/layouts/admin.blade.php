<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title') @yield('title') · @endif Admin · {{ config('shop.name') }}</title>
    @include('partials.assets')
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-56 shrink-0 bg-stone-900 text-stone-200">
            <div class="px-5 py-5 text-lg font-bold text-white">{{ config('shop.name') }}</div>
            <nav class="space-y-1 px-3 text-sm">
                @foreach ([
                    'admin.dashboard' => 'Dashboard',
                    'admin.orders.index' => 'Ordini',
                    'admin.products.index' => 'Prodotti',
                    'admin.categories.index' => 'Categorie',
                ] as $route => $label)
                    @php $active = request()->routeIs(str_replace('.index', '.*', $route)); @endphp
                    <a href="{{ route($route) }}"
                       class="block rounded-lg px-3 py-2 {{ $active ? 'bg-stone-700 text-white' : 'hover:bg-stone-800' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('home') }}" class="mt-6 block rounded-lg px-3 py-2 hover:bg-stone-800">← Vai al negozio</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
</body>
</html>
