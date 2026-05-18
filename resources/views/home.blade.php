@extends('layouts.public')

@section('content')
<section class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-16 text-white shadow-lg">
    <h1 class="text-4xl font-bold sm:text-5xl">AuctioHub</h1>
    <p class="mt-4 max-w-2xl text-lg text-indigo-100">
        Демо онлайн-аукціонної платформи на Laravel 11. Робіть ставки на унікальні лоти, спостерігайте за фаворитами,
        отримуйте сповіщення про обігнані ставки.
    </p>
    <div class="mt-6 flex gap-3">
        <a href="{{ route('lots.index') }}" class="rounded bg-white px-5 py-2 font-semibold text-indigo-700 hover:bg-indigo-50">Дивитись лоти</a>
        @guest
            <a href="{{ route('register') }}" class="rounded border border-white px-5 py-2 font-semibold text-white hover:bg-white/10">Зареєструватись</a>
        @endguest
    </div>
</section>

<section class="mt-12">
    <h2 class="mb-4 text-2xl font-bold">Категорії</h2>
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-8">
        @foreach ($categories as $category)
            <a href="{{ route('categories.show', $category) }}" class="rounded border border-gray-200 bg-white p-3 text-center text-sm font-medium text-gray-700 hover:border-indigo-400 hover:text-indigo-700">
                {{ $category->name }}
                <div class="text-xs text-gray-400">{{ $category->lots_count }} лотів</div>
            </a>
        @endforeach
    </div>
</section>

<section class="mt-12">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">🔥 Найпопулярніші</h2>
        <a href="{{ route('lots.index', ['sort' => 'popular']) }}" class="text-sm text-indigo-600 hover:underline">Усі →</a>
    </div>
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($featuredLots as $lot)
            <x-lot-card :lot="$lot" />
        @endforeach
    </div>
</section>

<section class="mt-12">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">⏰ Завершуються скоро</h2>
        <a href="{{ route('lots.index', ['sort' => 'ending_soon']) }}" class="text-sm text-indigo-600 hover:underline">Усі →</a>
    </div>
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($endingSoon as $lot)
            <x-lot-card :lot="$lot" />
        @endforeach
    </div>
</section>
@endsection
