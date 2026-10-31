@extends('layouts.admin')

@section('title', 'Categorie')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Categorie</h1>
        <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-stone-900 px-4 py-2 text-sm font-medium text-white hover:bg-stone-700">+ Nuova categoria</a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-stone-100 text-left text-stone-500">
                <tr>
                    <th class="p-4 font-medium">Nome</th>
                    <th class="p-4 font-medium">Slug</th>
                    <th class="p-4 font-medium">Padre</th>
                    <th class="p-4 text-right font-medium">Prodotti</th>
                    <th class="p-4 font-medium">Stato</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="p-4 font-medium">{{ $category->name }}</td>
                        <td class="p-4 text-stone-500">{{ $category->slug }}</td>
                        <td class="p-4">{{ $category->parent?->name ?? '—' }}</td>
                        <td class="p-4 text-right">{{ $category->products_count }}</td>
                        <td class="p-4">{{ $category->is_active ? 'Attiva' : 'Nascosta' }}</td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="underline">Modifica</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="ml-3 inline"
                                  onsubmit="return confirm('Eliminare la categoria? I prodotti resteranno senza categoria.')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 underline">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-stone-500">Nessuna categoria.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
