@extends('layouts.public')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold">Редагувати лот: {{ $lot->title }}</h1>

    @if ($errors->any())
        <div class="mt-4 rounded border border-red-300 bg-red-50 p-3 text-sm text-red-800">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('lots.update', $lot) }}" enctype="multipart/form-data"
          class="mt-6 space-y-4 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium">Назва</label>
            <input name="title" type="text" value="{{ old('title', $lot->title) }}" required
                   class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium">Категорія</label>
            <select name="category_id" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $lot->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Опис</label>
            <textarea name="description" rows="5" required
                      class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">{{ old('description', $lot->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Стартова ціна</label>
                <input name="starting_price" type="number" step="0.01" value="{{ old('starting_price', $lot->starting_price) }}" required
                       class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">Крок</label>
                <input name="bid_increment" type="number" step="0.01" value="{{ old('bid_increment', $lot->bid_increment) }}" required
                       class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Старт</label>
                <input name="starts_at" type="datetime-local" value="{{ old('starts_at', $lot->starts_at->format('Y-m-d\TH:i')) }}" required
                       class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">Кінець</label>
                <input name="ends_at" type="datetime-local" value="{{ old('ends_at', $lot->ends_at->format('Y-m-d\TH:i')) }}" required
                       class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('lots.show', $lot) }}" class="rounded border border-gray-300 px-4 py-2 text-sm">Скасувати</a>
            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Зберегти</button>
        </div>
    </form>

    <form method="POST" action="{{ route('lots.destroy', $lot) }}" class="mt-6">
        @csrf @method('DELETE')
        <button type="submit" onclick="return confirm('Видалити лот? Дію не можна скасувати.')"
                class="text-sm text-red-600 hover:underline">Видалити лот</button>
    </form>
</div>
@endsection
