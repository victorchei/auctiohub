@props(['lot'])

@php
    $firstImage = $lot->images->first()?->path ?? $lot->cover_image_path;
    $imageUrl = $firstImage ? asset('storage/' . $firstImage) : null;
@endphp

<a href="{{ route('lots.show', $lot) }}"
   class="group block overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
   aria-label="Лот: {{ $lot->title }}, поточна ціна {{ number_format($lot->current_price, 2, ',', ' ') }} гривень">
    <div class="aspect-video overflow-hidden bg-gray-100">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}"
                 alt="Зображення лоту «{{ $lot->title }}»"
                 loading="lazy"
                 class="h-full w-full object-cover transition group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-xs text-gray-400" aria-hidden="true">Без зображення</div>
        @endif
    </div>
    <div class="p-4">
        <h3 class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $lot->title }}</h3>
        <div class="mt-2 flex items-center justify-between">
            <span class="text-lg font-bold text-indigo-700">{{ number_format($lot->current_price, 2, ',', ' ') }} ₴</span>
            <span class="text-xs text-gray-500">{{ $lot->bids_count ?? $lot->bids()->count() }} ставок</span>
        </div>
        @if ($lot->status === 'active')
            <x-countdown :end="$lot->ends_at" class="mt-2 text-xs text-amber-700" />
        @elseif ($lot->status === 'ended')
            <p class="mt-2 text-xs text-gray-600">Аукціон завершено</p>
        @endif
    </div>
</a>
