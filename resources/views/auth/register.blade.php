@extends('layouts.app')

@section('title', 'Registrati')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl border border-stone-200 bg-white p-8">
        <h1 class="mb-6 text-2xl font-bold">Crea un account</h1>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <label class="block">
                <span class="mb-1 block text-sm">Nome e cognome</span>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
            </label>
            <label class="block">
                <span class="mb-1 block text-sm">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
            </label>
            <label class="block">
                <span class="mb-1 block text-sm">Password</span>
                <input type="password" name="password" required autocomplete="new-password"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
            </label>
            <label class="block">
                <span class="mb-1 block text-sm">Conferma password</span>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
            </label>
            <button class="w-full rounded-lg bg-stone-900 px-5 py-3 font-medium text-white hover:bg-stone-700">Registrati</button>
        </form>

        <p class="mt-6 text-center text-sm text-stone-600">
            Hai già un account? <a href="{{ route('login') }}" class="font-medium underline">Accedi</a>
        </p>
    </div>
@endsection
