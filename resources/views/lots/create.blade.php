@extends('layouts.public')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold">Створити лот</h1>

    @if ($errors->any())
        <div class="mt-4 rounded border border-red-300 bg-red-50 p-3 text-sm text-red-800">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('lots.store') }}" enctype="multipart/form-data"
          class="mt-6 space-y-4 rounded-lg border border-gray-200 bg-white p-6">
        @csrf

        <div>
            <label for="title" class="block text-sm font-medium">Назва</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}" required
                   class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label for="category_id" class="block text-sm font-medium">Категорія</label>
            <select id="category_id" name="category_id" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
                <option value="">— Оберіть —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->parent ? $cat->parent->name.' / ' : '' }}{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium">Опис</label>
            <textarea id="description" name="description" rows="5" required
                      class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="starting_price" class="block text-sm font-medium">Стартова ціна (₴)</label>
                <input id="starting_price" name="starting_price" type="number" step="0.01" min="1" value="{{ old('starting_price', 100) }}" required
                       class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label for="bid_increment" class="block text-sm font-medium">Крок ставки (₴)</label>
                <input id="bid_increment" name="bid_increment" type="number" step="0.01" min="1" value="{{ old('bid_increment', 10) }}" required
                       class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="starts_at" class="block text-sm font-medium">Старт</label>
                <input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}" required
                       class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label for="ends_at" class="block text-sm font-medium">Кінець</label>
                <input id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at', now()->addDays(7)->format('Y-m-d\TH:i')) }}" required
                       class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label for="images" class="block text-sm font-medium">Зображення (макс. 6, до 5 MB кожне)</label>
            <input id="images" name="images[]" type="file" multiple accept="image/*"
                   class="mt-1 block w-full text-sm">
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('lots.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm">Скасувати</a>
            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Створити</button>
        </div>
    </form>
</div>
@endsection
