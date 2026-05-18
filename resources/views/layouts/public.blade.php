<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} — AuctioHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 font-sans antialiased">
    @include('layouts.partials.public-nav')

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded border border-green-300 bg-green-50 p-4 text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded border border-red-300 bg-red-50 p-4 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-gray-200 bg-white py-6">
        <div class="mx-auto max-w-7xl px-4 text-center text-sm text-gray-500">
            © {{ date('Y') }} AuctioHub — навчальне демо курсової роботи (Laravel 11)
            · <a href="{{ route('faq') }}" class="underline">FAQ</a>
            · <a href="{{ route('contact.show') }}" class="underline">Контакти</a>
        </div>
    </footer>
</body>
</html>
