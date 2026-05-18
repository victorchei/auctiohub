@props(['lot'])

<a href="{{ route('lots.show', $lot) }}" class="block overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
    <div class="aspect-video bg-gray-100">
        @if ($lot->images->isNotEmpty())
            <div class="flex h-full items-center justify-center text-xs text-gray-400">[зображення лоту]</div>
        @else
            <div class="flex h-full items-center justify-center text-xs text-gray-400">Без зображення</div>
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
            <p class="mt-2 text-xs text-gray-400">Аукціон завершено</p>
        @endif
    </div>
</a>
