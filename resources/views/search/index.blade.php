@extends('layouts.public')

@section('content')
<h1 class="text-2xl font-bold">Пошук</h1>

<form method="GET" class="mt-3 flex max-w-xl gap-2">
    <input type="text" name="q" value="{{ $q }}" placeholder="Що шукаємо?"
           class="flex-1 rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
    <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Шукати</button>
</form>

@if ($q !== '')
    <div class="mt-4 text-sm text-gray-500">
        Запит: <span class="font-medium text-gray-900">{{ $q }}</span> · Знайдено: {{ $lots->total() }}
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($lots as $lot)
            <x-lot-card :lot="$lot" />
        @empty
            <p class="col-span-full rounded border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500">
                Нічого не знайдено за запитом «{{ $q }}».
            </p>
        @endforelse
    </div>

    <div class="mt-6">{{ $lots->links() }}</div>
@else
    <p class="mt-4 text-sm text-gray-500">Введіть пошуковий запит.</p>
@endif
@endsection
