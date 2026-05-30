@extends('layouts.public')

@section('content')
<nav class="mb-4 text-xs text-gray-500">
    <a href="{{ route('home') }}" class="hover:underline">{{ __('messages.nav.home') }}</a> ›
    <a href="{{ route('lots.index') }}" class="hover:underline">{{ __('messages.nav.lots') }}</a> ›
    @if ($lot->category->parent)
        <a href="{{ route('categories.show', $lot->category->parent) }}" class="hover:underline">{{ $lot->category->parent->name }}</a> ›
    @endif
    <a href="{{ route('categories.show', $lot->category) }}" class="hover:underline">{{ $lot->category->name }}</a> ›
    <span class="text-gray-700">{{ Str::limit($lot->title, 40) }}</span>
</nav>

<div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
    <div x-data="{
            open: false,
            active: 0,
            images: @js($lot->images->map(fn($i) => asset('storage/' . $i->path))->all()),
            titles: @js($lot->images->map(fn($i, $k) => 'Зображення ' . ($k + 1) . ' з ' . $lot->images->count())->all()),
            next() { this.active = (this.active + 1) % this.images.length; },
            prev() { this.active = (this.active - 1 + this.images.length) % this.images.length; },
        }"
        @keydown.window.escape="open = false"
        @keydown.window.arrow-right="open && next()"
        @keydown.window.arrow-left="open && prev()">

        {{-- Main image (clickable to open lightbox) --}}
        <button type="button"
                @click="open = true; active = 0"
                class="block aspect-square w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                aria-label="{{ __('messages.lots.no_images') }}">
            @if ($lot->images->isNotEmpty())
                <img src="{{ asset('storage/' . $lot->images->first()->path) }}"
                     alt="{{ __('messages.lot.image_alt', ['title' => $lot->title]) }}"
                     class="h-full w-full object-cover transition hover:scale-105">
            @else
                <div class="flex h-full items-center justify-center text-gray-400" aria-hidden="true">{{ __('messages.lots.no_images') }}</div>
            @endif
        </button>

        {{-- Thumbnail strip --}}
        @if ($lot->images->count() > 1)
            <div class="mt-2 grid gap-2" style="grid-template-columns: repeat({{ min($lot->images->count(), 4) }}, minmax(0, 1fr));">
                @foreach ($lot->images as $img)
                    <button type="button"
                            @click="open = true; active = {{ $loop->index }}"
                            class="aspect-square overflow-hidden rounded border border-gray-200 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="Відкрити зображення {{ $loop->iteration }} з {{ $lot->images->count() }}">
                        <img src="{{ asset('storage/' . $img->path) }}"
                             alt="Мініатюра зображення {{ $loop->iteration }} лоту «{{ $lot->title }}»"
                             loading="lazy"
                             class="h-full w-full object-cover">
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Lightbox modal --}}
        <div x-show="open"
             x-cloak
             @click.self="open = false"
             role="dialog"
             aria-modal="true"
             aria-label="Галерея зображень лоту"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4"
             x-transition.opacity>
            <button type="button"
                    @click="open = false"
                    aria-label="Закрити галерею"
                    class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <button type="button"
                    @click.stop="prev()"
                    x-show="images.length > 1"
                    aria-label="Попереднє зображення"
                    class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <figure class="flex w-full max-w-5xl flex-col items-center px-16" style="max-height: 92vh;">
                <div class="relative w-full overflow-hidden rounded-lg bg-gray-900 shadow-2xl"
                     style="aspect-ratio: 4 / 3; max-height: 80vh;">
                    <img :src="images[active]"
                         :alt="titles[active]"
                         class="absolute inset-0 h-full w-full object-contain">
                </div>
                <figcaption class="mt-4 flex w-full items-center justify-between gap-4 text-sm text-white">
                    <span x-text="titles[active]" class="font-medium"></span>
                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs"
                          x-text="`${active + 1} / ${images.length}`"></span>
                </figcaption>
            </figure>

            <button type="button"
                    @click.stop="next()"
                    x-show="images.length > 1"
                    aria-label="Наступне зображення"
                    class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <div>
        <h1 class="text-3xl font-bold">{{ $lot->title }}</h1>
        <p class="mt-2 text-sm text-gray-500">
            {{ __('messages.lots.seller') }}: <span class="font-medium">{{ $lot->seller->name }}</span>
            · {{ __('messages.lots.filter_category') }}: <a href="{{ route('categories.show', $lot->category) }}" class="text-indigo-600 hover:underline">{{ $lot->category->name }}</a>
        </p>

        <div class="mt-6 rounded-lg border border-gray-200 bg-white p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('messages.lot.current_price') }}</div>
            <div class="text-4xl font-bold text-indigo-700">{{ number_format($lot->current_price, 2, ',', ' ') }} ₴</div>
            <div class="mt-2 text-xs text-gray-500">{{ __('messages.lot.starting_price') }}: {{ number_format($lot->starting_price, 2, ',', ' ') }} ₴ · {{ __('messages.lot.bid_increment') }}: {{ number_format($lot->bid_increment, 2, ',', ' ') }} ₴</div>

            @if ($lot->status === 'active')
                <x-countdown :end="$lot->ends_at" class="mt-3 text-sm font-medium text-amber-700" />
            @elseif ($lot->status === 'ended')
                <p class="mt-3 text-sm font-medium text-gray-600">{{ __('messages.lots.ended_ago') }} {{ $lot->ends_at->diffForHumans() }}</p>
                @if ($lot->winner)
                    <p class="mt-1 text-sm">{{ __('messages.lot.winner') }}: <span class="font-medium">{{ $lot->winner->name }}</span></p>
                @endif
            @endif

            @auth
                @if ($lot->status === 'active' && auth()->id() !== $lot->seller_id && ! auth()->user()->isBanned())
                    <form method="POST" action="{{ route('bids.store', $lot) }}" class="mt-4 flex gap-2">
                        @csrf
                        <input type="number" step="0.01" name="amount" min="{{ $lot->minNextBid() }}" placeholder="≥ {{ number_format($lot->minNextBid(), 2, ',', ' ') }}"
                               class="flex-1 rounded border border-gray-300 px-3 py-2 text-sm" required>
                        <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">{{ __('messages.lots.bid_button') }}</button>
                    </form>
                    @error('amount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                @endif
                <form method="POST" action="{{ route('watchlist.toggle', $lot) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:underline">
                        {{ $watching ? __('messages.lot.watching') : __('messages.lot.watch') }}
                    </button>
                </form>
            @else
                <p class="mt-4 text-sm text-gray-500">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">{{ __('messages.nav.login') }}</a> — {{ __('messages.lot.login_to_bid') }}
                </p>
            @endauth
        </div>

        <div class="prose prose-sm mt-6 max-w-none text-gray-700">{!! nl2br(e($lot->description)) !!}</div>
    </div>
</div>

<section class="mt-12">
    <h2 class="text-xl font-bold">{{ __('messages.lots.bid_history', ['count' => $lot->bids->count()]) }}</h2>
    @if ($lot->bids->isEmpty())
        <p class="mt-2 text-sm text-gray-500">{{ __('messages.lots.no_bids') }}</p>
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
    <h2 class="text-xl font-bold">{{ __('messages.lots.comments_title', ['count' => $lot->comments->count()]) }}</h2>

    @auth
        <form method="POST" action="{{ route('comments.store', $lot) }}" class="mt-4 rounded-lg border border-gray-200 bg-white p-4">
            @csrf
            <textarea name="body" rows="3" placeholder="{{ __('messages.lots.comment_placeholder') }}" required
                      class="block w-full rounded border border-gray-300 px-3 py-2 text-sm">{{ old('body') }}</textarea>
            @error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            <div class="mt-2 flex justify-end">
                <button type="submit" class="rounded bg-indigo-600 px-4 py-1.5 text-sm text-white hover:bg-indigo-700">{{ __('messages.lots.comment_submit') }}</button>
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
    <h2 class="text-xl font-bold">{{ __('messages.lots.similar') }}</h2>
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($similar as $s)
            <x-lot-card :lot="$s" />
        @endforeach
    </div>
</section>
@endif
@endsection
