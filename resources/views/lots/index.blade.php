@extends('layouts.public')

@section('content')
<h1 class="text-2xl font-bold">{{ __('messages.lots.index_title') }}</h1>

<form method="GET" class="mt-4 rounded-lg border border-gray-200 bg-white p-4">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-5">
        <div>
            <label class="text-xs text-gray-600" for="q">{{ __('messages.lots.filter_search') }}</label>
            <input id="q" type="text" name="q" value="{{ request('q') }}" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-600" for="category">{{ __('messages.lots.filter_category') }}</label>
            <select id="category" name="category" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
                <option value="">{{ __('messages.lots.filter_all') }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-gray-600" for="min_price">{{ __('messages.lots.filter_price_from') }}</label>
            <input id="min_price" type="number" name="min_price" value="{{ request('min_price') }}" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-600" for="max_price">{{ __('messages.lots.filter_price_to') }}</label>
            <input id="max_price" type="number" name="max_price" value="{{ request('max_price') }}" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-600" for="sort">{{ __('messages.lots.filter_sort') }}</label>
            <select id="sort" name="sort" class="mt-1 block w-full rounded border border-gray-300 px-2 py-1 text-sm">
                <option value="ending_soon" @selected($sort === 'ending_soon')>{{ __('messages.lots.sort_ending_soon') }}</option>
                <option value="price_asc" @selected($sort === 'price_asc')>{{ __('messages.lots.sort_price_asc') }}</option>
                <option value="price_desc" @selected($sort === 'price_desc')>{{ __('messages.lots.sort_price_desc') }}</option>
                <option value="popular" @selected($sort === 'popular')>{{ __('messages.lots.sort_popular') }}</option>
                <option value="newest" @selected($sort === 'newest')>{{ __('messages.lots.sort_newest') }}</option>
            </select>
        </div>
    </div>
    <div class="mt-3 flex justify-end gap-2">
        <a href="{{ route('lots.index') }}" class="rounded border border-gray-300 px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.lots.filter_reset') }}</a>
        <button type="submit" class="rounded bg-indigo-600 px-3 py-1 text-sm text-white hover:bg-indigo-700">{{ __('messages.lots.filter_apply') }}</button>
    </div>
</form>

<div class="mt-2 text-sm text-gray-500">{{ __('messages.lots.found_count', ['count' => $lots->total()]) }}</div>

<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @forelse ($lots as $lot)
        <x-lot-card :lot="$lot" />
    @empty
        <p class="col-span-full rounded border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500">
            {{ __('messages.lots.no_results') }}
        </p>
    @endforelse
</div>

<div class="mt-6">{{ $lots->links() }}</div>
@endsection
