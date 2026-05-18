@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold">Користувачі ({{ $users->total() }})</h1>

<form method="GET" class="mt-4 flex gap-2">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Пошук..." class="rounded border border-gray-300 px-3 py-1.5 text-sm">
    <select name="role" class="rounded border border-gray-300 px-3 py-1.5 text-sm">
        <option value="">Усі ролі</option>
        <option value="user" @selected(request('role')==='user')>user</option>
        <option value="admin" @selected(request('role')==='admin')>admin</option>
    </select>
    <label class="flex items-center gap-1 text-sm"><input type="checkbox" name="banned_only" value="1" @checked(request('banned_only'))> Тільки заблоковані</label>
    <button type="submit" class="rounded bg-gray-700 px-3 py-1.5 text-sm text-white">Фільтр</button>
</form>

<div class="mt-4 overflow-hidden rounded-lg bg-white shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-3 py-2 text-left text-xs">#</th>
                <th class="px-3 py-2 text-left text-xs">Ім'я</th>
                <th class="px-3 py-2 text-left text-xs">Email</th>
                <th class="px-3 py-2 text-left text-xs">Роль</th>
                <th class="px-3 py-2 text-left text-xs">Лотів</th>
                <th class="px-3 py-2 text-left text-xs">Ставок</th>
                <th class="px-3 py-2 text-left text-xs">Статус</th>
                <th class="px-3 py-2 text-left text-xs">Дії</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @foreach ($users as $user)
            <tr @class(['bg-red-50' => $user->isBanned()])>
                <td class="px-3 py-2 text-gray-500">{{ $user->id }}</td>
                <td class="px-3 py-2">{{ $user->name }}</td>
                <td class="px-3 py-2 text-gray-600">{{ $user->email }}</td>
                <td class="px-3 py-2">
                    <span @class(['rounded px-2 py-0.5 text-xs', 'bg-amber-100 text-amber-800' => $user->isAdmin(), 'bg-blue-100 text-blue-800' => !$user->isAdmin()])>{{ $user->role }}</span>
                </td>
                <td class="px-3 py-2">{{ $user->lots_count }}</td>
                <td class="px-3 py-2">{{ $user->bids_count }}</td>
                <td class="px-3 py-2 text-xs">{{ $user->isBanned() ? 'забан '.$user->banned_at->diffForHumans() : '—' }}</td>
                <td class="px-3 py-2 text-xs">
                    @if (!$user->isBanned() && !$user->isAdmin())
                        <form method="POST" action="{{ route('admin.users.ban', $user) }}" class="inline">@csrf<button class="text-red-700 hover:underline" onclick="return confirm('Заблокувати?')">⛔ Ban</button></form>
                    @elseif ($user->isBanned())
                        <form method="POST" action="{{ route('admin.users.unban', $user) }}" class="inline">@csrf<button class="text-green-700 hover:underline">↩ Unban</button></form>
                    @endif
                    @if (!$user->isAdmin())
                        <form method="POST" action="{{ route('admin.users.promote', $user) }}" class="inline">@csrf<button class="text-purple-700 hover:underline" onclick="return confirm('Підвищити до адміна?')">⬆ Promote</button></form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
