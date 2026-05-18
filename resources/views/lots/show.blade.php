@extends('layouts.public')

@section('content')
<nav class="mb-4 text-xs text-gray-500">
    <a href="{{ route('home') }}" class="hover:underline">Головна</a> ›
    <a href="{{ route('lots.index') }}" class="hover:underline">Лоти</a> ›
    @if ($lot->category->parent)
        <a href="{{ route('categories.show', $lot->category->parent) }}" class="hover:underline">{{ $lot->category->parent->name }}</a> ›
    @endif
    <a href="{{ route('categories.show', $lot->category) }}" class="hover:underline">{{ $lot->category->name }}</a> ›
    <span class="text-gray-700">{{ Str::limit($lot->title, 40) }}</span>
</nav>

<div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
    <div>
        <div class="aspect-square overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
            @if ($lot->images->isNotEmpty())
                <div class="flex h-full items-center justify-center text-gray-400">[галерея зображень — {{ $lot->images->count() }} фото]</div>
            @else
                <div class="flex h-full items-center justify-center text-gray-400">Без зображень</div>
            @endif
        </div>
        @if ($lot->images->count() > 1)
            <div class="mt-2 grid grid-cols-{{ min($lot->images->count(), 4) }} gap-2">
                @foreach ($lot->images as $img)
                    <div class="aspect-square rounded border border-gray-200 bg-gray-100 text-xs text-gray-400 flex items-center justify-center">img {{ $loop->iteration }}</div>
                @endforeach
            </div>
        @endif
    </div>

    <div>
        <h1 class="text-3xl font-bold">{{ $lot->title }}</h1>
        <p class="mt-2 text-sm text-gray-500">
            Продавець: <span class="font-medium">{{ $lot->seller->name }}</span>
            · Категорія: <a href="{{ route('categories.show', $lot->category) }}" class="text-indigo-600 hover:underline">{{ $lot->category->name }}</a>
        </p>

        <div class="mt-6 rounded-lg border border-gray-200 bg-white p-5">
            <div class="text-xs uppercase text-gray-500">Поточна ціна</div>
            <div class="text-4xl font-bold text-indigo-700">{{ number_format($lot->current_price, 2, ',', ' ') }} ₴</div>
            <div class="mt-2 text-xs text-gray-500">Стартова: {{ number_format($lot->starting_price, 2, ',', ' ') }} ₴ · Крок: {{ number_format($lot->bid_increment, 2, ',', ' ') }} ₴</div>

            @if ($lot->status === 'active')
                <x-countdown :end="$lot->ends_at" class="mt-3 text-sm font-medium text-amber-700" />
            @elseif ($lot->status === 'ended')
                <p class="mt-3 text-sm font-medium text-gray-600">Завершено {{ $lot->ends_at->diffForHumans() }}</p>
                @if ($lot->winner)
                    <p class="mt-1 text-sm">🏆 Переможець: <span class="font-medium">{{ $lot->winner->name }}</span></p>
                @endif
            @endif

            @auth
                @if ($lot->status === 'active' && auth()->id() !== $lot->seller_id && ! auth()->user()->isBanned())
                    <form method="POST" action="{{ route('bids.store', $lot) }}" class="mt-4 flex gap-2">
                        @csrf
                        <input type="number" step="0.01" name="amount" min="{{ $lot->minNextBid() }}" placeholder="≥ {{ number_format($lot->minNextBid(), 2, ',', ' ') }}"
                               class="flex-1 rounded border border-gray-300 px-3 py-2 text-sm" required>
                        <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Ставка</button>
                    </form>
                    @error('amount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                @endif
                <form method="POST" action="{{ route('watchlist.toggle', $lot) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:underline">
                        {{ $watching ? '★ У вашому списку — прибрати' : '☆ Додати у спостереження' }}
                    </button>
                </form>
            @else
                <p class="mt-4 text-sm text-gray-500">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Увійдіть</a> щоб робити ставки.
                </p>
            @endauth
        </div>

        <div class="prose prose-sm mt-6 max-w-none text-gray-700">{!! nl2br(e($lot->description)) !!}</div>
    </div>
</div>

<section class="mt-12">
    <h2 class="text-xl font-bold">Історія ставок ({{ $lot->bids->count() }})</h2>
    @if ($lot->bids->isEmpty())
        <p class="mt-2 text-sm text-gray-500">Ставок ще немає. Будьте першим!</p>
    @else
        <ol class="mt-3 divide-y divide-gray-200 rounded-lg border border-gray-200 bg-white">
            @foreach ($lot->bids as $bid)
                <li class="flex items-center justify-between p-3 text-sm">
                    <span><span class="font-medium">{{ $bid->user->name }}</span> · {{ $bid->placed_at->diffForHumans() }}</span>
                    <span class="font-bold text-indigo-700">{{ number_format($bid->amount, 2, ',', ' ') }} ₴</span>
                </li>
            @endforeach
        </ol>
    @endif
</section>

<section class="mt-12">
    <h2 class="text-xl font-bold">Коментарі ({{ $lot->comments->count() }})</h2>

    @auth
        <form method="POST" action="{{ route('comments.store', $lot) }}" class="mt-4 rounded-lg border border-gray-200 bg-white p-4">
            @csrf
            <textarea name="body" rows="3" placeholder="Ваш коментар..." required
                      class="block w-full rounded border border-gray-300 px-3 py-2 text-sm">{{ old('body') }}</textarea>
            @error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            <div class="mt-2 flex justify-end">
                <button type="submit" class="rounded bg-indigo-600 px-4 py-1.5 text-sm text-white hover:bg-indigo-700">Опублікувати</button>
            </div>
        </form>
    @endauth

    @foreach ($lot->comments as $comment)
        <div class="mt-4 rounded-lg border border-gray-200 bg-white p-4">
            <div class="text-xs text-gray-500">{{ $comment->user->name }} · {{ $comment->created_at->diffForHumans() }}</div>
            <div class="mt-1 text-sm">{{ $comment->body }}</div>
            @foreach ($comment->replies as $reply)
                <div class="ml-6 mt-3 border-l-2 border-indigo-200 pl-3">
                    <div class="text-xs text-gray-500">{{ $reply->user->name }} · {{ $reply->created_at->diffForHumans() }}</div>
                    <div class="mt-1 text-sm">{{ $reply->body }}</div>
                </div>
            @endforeach
        </div>
    @endforeach
</section>

@if ($similar->isNotEmpty())
<section class="mt-12">
    <h2 class="text-xl font-bold">Схожі лоти</h2>
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($similar as $s)
            <x-lot-card :lot="$s" />
        @endforeach
    </div>
</section>
@endif
@endsection
