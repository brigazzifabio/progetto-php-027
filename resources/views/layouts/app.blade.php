<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') · @endif{{ config('shop.name') }}</title>
    @include('partials.assets')
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased flex flex-col">
    <header class="border-b border-stone-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center gap-6 px-4 py-4">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">{{ config('shop.name') }}</a>

            <nav class="hidden gap-5 text-sm md:flex">
                <a href="{{ route('products.index') }}" class="hover:text-stone-600">Tutti i prodotti</a>
            </nav>

            <form action="{{ route('products.index') }}" method="GET" class="ml-auto hidden w-full max-w-xs sm:block">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cerca prodotti…"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-stone-500 focus:outline-none">
            </form>

            <div class="ml-auto flex items-center gap-4 text-sm sm:ml-0">
                @auth
                    @can('admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-600">Admin</a>
                    @endcan
                    <a href="{{ route('account.orders.index') }}" class="hover:text-stone-600">I miei ordini</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="hover:text-stone-600">Esci</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-stone-600">Accedi</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative rounded-lg bg-stone-900 px-3 py-2 font-medium text-white hover:bg-stone-700">
                    Carrello
                    @if (($cartCount ?? 0) > 0)
                        <span class="ml-1 rounded-full bg-white px-2 text-xs text-stone-900">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-8">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="border-t border-stone-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-6 text-sm text-stone-500">
            © {{ date('Y') }} {{ config('shop.name') }} · Prezzi IVA esclusa · Spedizione gratuita sopra @money(config('shop.free_shipping_threshold'))
        </div>
    </footer>
</body>
</html>
