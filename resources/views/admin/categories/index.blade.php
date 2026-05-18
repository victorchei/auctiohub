@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold">Категорії ({{ $categories->count() }})</h1>

<div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs">Назва</th>
                    <th class="px-3 py-2 text-left text-xs">Батьківська</th>
                    <th class="px-3 py-2 text-left text-xs">Лотів</th>
                    <th class="px-3 py-2 text-left text-xs">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach ($categories as $cat)
                <tr>
                    <td class="px-3 py-2">{{ $cat->name }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $cat->parent?->name ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $cat->lots_count }}</td>
                    <td class="px-3 py-2 text-xs">
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline">@csrf @method('DELETE')<button class="text-red-700 hover:underline" onclick="return confirm('Видалити?')">🗑</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="rounded-lg bg-white p-4 shadow">
        <h2 class="text-sm font-semibold">Нова категорія</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-3 space-y-3">
            @csrf
            <input type="text" name="name" placeholder="Назва" required class="block w-full rounded border border-gray-300 px-2 py-1 text-sm">
            <select name="parent_id" class="block w-full rounded border border-gray-300 px-2 py-1 text-sm">
                <option value="">— Без батька —</option>
                @foreach ($categories->whereNull('parent_id') as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            <textarea name="description" rows="2" placeholder="Опис..." class="block w-full rounded border border-gray-300 px-2 py-1 text-sm"></textarea>
            @error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            <button type="submit" class="w-full rounded bg-indigo-600 px-3 py-1.5 text-sm text-white hover:bg-indigo-700">Створити</button>
        </form>
    </div>
</div>
@endsection
