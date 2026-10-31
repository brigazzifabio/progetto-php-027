@extends('layouts.app')

@section('title', 'Accedi')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl border border-stone-200 bg-white p-8">
        <h1 class="mb-6 text-2xl font-bold">Accedi</h1>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <label class="block">
                <span class="mb-1 block text-sm">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
            </label>
            <label class="block">
                <span class="mb-1 block text-sm">Password</span>
                <input type="password" name="password" required autocomplete="current-password"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1"> Ricordami
            </label>
            <button class="w-full rounded-lg bg-stone-900 px-5 py-3 font-medium text-white hover:bg-stone-700">Accedi</button>
        </form>

        <p class="mt-6 text-center text-sm text-stone-600">
            Non hai un account? <a href="{{ route('register') }}" class="font-medium underline">Registrati</a>
        </p>
    </div>
@endsection
