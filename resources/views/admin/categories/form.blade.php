@extends('layouts.admin')

@section('title', $category->exists ? 'Modifica categoria' : 'Nuova categoria')

@php($input = 'w-full rounded-lg border border-stone-300 px-3 py-2')

@section('content')
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-stone-500 hover:underline">← Categorie</a>
    <h1 class="mb-6 mt-2 text-2xl font-bold">{{ $category->exists ? $category->name : 'Nuova categoria' }}</h1>

    <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          method="POST" class="max-w-2xl space-y-5 rounded-xl bg-white p-6 shadow-sm">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <label class="block">
            <span class="mb-1 block text-sm font-medium">Nome *</span>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="{{ $input }}">
        </label>
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="block">
                <span class="mb-1 block text-sm font-medium">Slug <span class="font-normal text-stone-400">(auto se vuoto)</span></span>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="{{ $input }}">
            </label>
            <label class="block">
                <span class="mb-1 block text-sm font-medium">Posizione</span>
                <input type="number" name="position" min="0" value="{{ old('position', $category->position ?? 0) }}" class="{{ $input }}">
            </label>
        </div>
        <label class="block">
            <span class="mb-1 block text-sm font-medium">Categoria padre</span>
            <select name="parent_id" class="{{ $input }}">
                <option value="">— Nessuna (principale) —</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="block">
            <span class="mb-1 block text-sm font-medium">Descrizione</span>
            <textarea name="description" rows="4" class="{{ $input }}">{{ old('description', $category->description) }}</textarea>
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))> Visibile nel negozio
        </label>

        <button class="rounded-lg bg-stone-900 px-5 py-2.5 font-medium text-white hover:bg-stone-700">Salva</button>
    </form>
@endsection
