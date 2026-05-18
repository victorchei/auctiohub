@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold">Audit Log ({{ $logs->total() }})</h1>

<form method="GET" class="mt-4 flex gap-2">
    <select name="action" class="rounded border border-gray-300 px-3 py-1.5 text-sm">
        <option value="">Усі дії</option>
        @foreach (['created','updated','deleted','restored'] as $a)
            <option value="{{ $a }}" @selected(request('action')===$a)>{{ $a }}</option>
        @endforeach
    </select>
    <button type="submit" class="rounded bg-gray-700 px-3 py-1.5 text-sm text-white">Фільтр</button>
</form>

<div class="mt-4 overflow-hidden rounded-lg bg-white shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-3 py-2 text-left text-xs">Час</th>
                <th class="px-3 py-2 text-left text-xs">Користувач</th>
                <th class="px-3 py-2 text-left text-xs">Дія</th>
                <th class="px-3 py-2 text-left text-xs">Об'єкт</th>
                <th class="px-3 py-2 text-left text-xs">Деталі</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse ($logs as $log)
            <tr>
                <td class="px-3 py-2 text-xs text-gray-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="px-3 py-2">{{ $log->user?->name ?? 'system' }}</td>
                <td class="px-3 py-2">
                    <span @class(['rounded px-2 py-0.5 text-xs', 'bg-green-100 text-green-800' => $log->action==='created', 'bg-blue-100 text-blue-800' => $log->action==='updated', 'bg-red-100 text-red-800' => $log->action==='deleted', 'bg-purple-100 text-purple-800' => $log->action==='restored'])>{{ $log->action }}</span>
                </td>
                <td class="px-3 py-2 text-xs">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</td>
                <td class="px-3 py-2 text-xs text-gray-600">{{ Str::limit(json_encode($log->payload, JSON_UNESCAPED_UNICODE), 100) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-3 py-8 text-center text-gray-500">Журнал порожній.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $logs->links() }}</div>
@endsection
