<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — AuctioHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 font-sans antialiased">
    <nav class="bg-gray-900 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
            <div class="flex items-center gap-5">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-amber-400">⚙ AuctioHub Admin</a>
                <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-amber-400">Dashboard</a>
                <a href="{{ route('admin.lots.index') }}" class="text-sm hover:text-amber-400">Лоти</a>
                <a href="{{ route('admin.users.index') }}" class="text-sm hover:text-amber-400">Користувачі</a>
                <a href="{{ route('admin.categories.index') }}" class="text-sm hover:text-amber-400">Категорії</a>
                <a href="{{ route('admin.audit.index') }}" class="text-sm hover:text-amber-400">Audit Log</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-white">← На сайт</a>
                <span class="text-sm">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm text-gray-400 hover:text-white">Вийти</button></form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-6">
        @if (session('status'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
