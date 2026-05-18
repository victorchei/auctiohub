@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Лоти ({{ $lots->total() }})</h1>
    <a href="{{ route('admin.lots.export') }}" class="rounded bg-indigo-600 px-3 py-1.5 text-sm text-white hover:bg-indigo-700">Експорт CSV</a>
</div>

<form method="GET" class="mt-4 flex gap-2">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Пошук по назві..." class="rounded border border-gray-300 px-3 py-1.5 text-sm">
    <select name="status" class="rounded border border-gray-300 px-3 py-1.5 text-sm">
        <option value="">— Усі статуси —</option>
        @foreach (['active','draft','ended','cancelled'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
        @endforeach
    </select>
    <label class="flex items-center gap-1 text-sm"><input type="checkbox" name="trashed" value="1" @checked(request('trashed'))> Trash</label>
    <button type="submit" class="rounded bg-gray-700 px-3 py-1.5 text-sm text-white">Фільтр</button>
</form>

<form method="POST" action="{{ route('admin.lots.bulkDelete') }}" class="mt-4">
    @csrf
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs"><input type="checkbox" onclick="document.querySelectorAll('input[name=\'ids[]\']').forEach(c=>c.checked=this.checked)"></th>
                    <th class="px-3 py-2 text-left text-xs">#</th>
                    <th class="px-3 py-2 text-left text-xs">Назва</th>
                    <th class="px-3 py-2 text-left text-xs">Продавець</th>
                    <th class="px-3 py-2 text-left text-xs">Ціна</th>
                    <th class="px-3 py-2 text-left text-xs">Статус</th>
                    <th class="px-3 py-2 text-left text-xs">Кінець</th>
                    <th class="px-3 py-2 text-left text-xs">Дії</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach ($lots as $lot)
                <tr @class(['bg-red-50' => $lot->trashed()])>
                    <td class="px-3 py-2"><input type="checkbox" name="ids[]" value="{{ $lot->id }}"></td>
                    <td class="px-3 py-2 text-gray-500">{{ $lot->id }}</td>
                    <td class="px-3 py-2">
                        <a href="{{ route('lots.show', $lot->slug) }}" class="text-indigo-600 hover:underline">{{ Str::limit($lot->title, 40) }}</a>
                    </td>
                    <td class="px-3 py-2">{{ $lot->seller->name ?? '—' }}</td>
                    <td class="px-3 py-2">{{ number_format($lot->current_price, 2, ',', ' ') }}₴</td>
                    <td class="px-3 py-2">
                        <span @class(['rounded px-2 py-0.5 text-xs', 'bg-green-100 text-green-800' => $lot->status==='active', 'bg-gray-100 text-gray-700' => $lot->status==='ended', 'bg-yellow-100 text-yellow-800' => $lot->status==='draft', 'bg-red-100 text-red-800' => $lot->status==='cancelled'])>{{ $lot->status }}</span>
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $lot->ends_at?->format('Y-m-d H:i') }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if ($lot->trashed())
                            <form method="POST" action="{{ route('admin.lots.restore', $lot->id) }}" class="inline">@csrf<button class="text-green-700 hover:underline">↻</button></form>
                        @else
                            @if ($lot->status === 'active')
                                <form method="POST" action="{{ route('admin.lots.cancel', $lot) }}" class="inline">@csrf<button class="text-yellow-700 hover:underline">⏸</button></form>
                            @endif
                            <form method="POST" action="{{ route('admin.lots.destroy', $lot) }}" class="inline">@csrf @method('DELETE')<button class="text-red-700 hover:underline">🗑</button></form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 flex justify-between">
        <button type="submit" onclick="return confirm('Видалити вибрані лоти?')" class="rounded bg-red-600 px-3 py-1.5 text-sm text-white hover:bg-red-700">Bulk delete</button>
        <div>{{ $lots->links() }}</div>
    </div>
</form>
@endsection
