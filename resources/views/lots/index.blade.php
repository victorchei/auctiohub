@extends('layouts.public')

@section('content')
<h1 class="text-2xl font-bold">Усі лоти</h1>

<form method="GET" class="mt-4 rounded-lg border border-gray-200 bg-white p-4">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-5">
        <div>
            <label class="text-xs text-gray-600" for="q">Пошук</label>
            <input id="q" type="text" name="q" value="{{ request('q') }}" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-600" for="category">Категорія</label>
            <select id="category" name="category" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
                <option value="">— Усі —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-gray-600" for="min_price">Ціна від</label>
            <input id="min_price" type="number" name="min_price" value="{{ request('min_price') }}" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-600" for="max_price">Ціна до</label>
            <input id="max_price" type="number" name="max_price" value="{{ request('max_price') }}" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-600" for="sort">Сортування</label>
            <select id="sort" name="sort" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
                <option value="ending_soon" @selected($sort === 'ending_soon')>Скоро завершаться</option>
                <option value="price_asc" @selected($sort === 'price_asc')>Ціна ↑</option>
                <option value="price_desc" @selected($sort === 'price_desc')>Ціна ↓</option>
                <option value="popular" @selected($sort === 'popular')>Популярні</option>
                <option value="newest" @selected($sort === 'newest')>Новіші</option>
            </select>
        </div>
    </div>
    <div class="mt-3 flex justify-end gap-2">
        <a href="{{ route('lots.index') }}" class="rounded border border-gray-300 px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Скинути</a>
        <button type="submit" class="rounded bg-indigo-600 px-3 py-1 text-sm text-white hover:bg-indigo-700">Застосувати</button>
    </div>
</form>

<div class="mt-2 text-sm text-gray-500">Знайдено: {{ $lots->total() }}</div>

<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @forelse ($lots as $lot)
        <x-lot-card :lot="$lot" />
    @empty
        <p class="col-span-full rounded border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500">
            Лотів за обраними фільтрами не знайдено.
        </p>
    @endforelse
</div>

<div class="mt-6">{{ $lots->links() }}</div>
@endsection
