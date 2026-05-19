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
    <a href="#main" class="skip-link">Пропустити навігацію</a>

    @include('layouts.partials.public-nav')

    <main id="main" role="main" tabindex="-1" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded border border-green-300 bg-green-50 p-4 text-green-800" role="alert" aria-live="polite">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded border border-red-300 bg-red-50 p-4 text-red-800" role="alert" aria-live="assertive">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('layouts.partials.public-footer')
</body>
</html>
