@extends('layouts.public')

@section('content')
<nav class="mb-4 text-xs text-gray-500">
    <a href="{{ route('home') }}" class="hover:underline">Головна</a> ›
    @if ($category->parent)
        <a href="{{ route('categories.show', $category->parent) }}" class="hover:underline">{{ $category->parent->name }}</a> ›
    @endif
    <span class="text-gray-700">{{ $category->name }}</span>
</nav>

<h1 class="text-2xl font-bold">{{ $category->name }}</h1>
@if ($category->description)
    <p class="mt-1 text-sm text-gray-600">{{ $category->description }}</p>
@endif

@if ($category->children->isNotEmpty())
    <div class="mt-3 flex flex-wrap gap-2">
        @foreach ($category->children as $child)
            <a href="{{ route('categories.show', $child) }}" class="rounded-full border border-gray-300 bg-white px-3 py-1 text-xs text-gray-700 hover:border-indigo-400 hover:text-indigo-700">
                {{ $child->name }}
            </a>
        @endforeach
    </div>
@endif

<div class="mt-2 text-sm text-gray-500">Активних лотів: {{ $lots->total() }}</div>

<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @forelse ($lots as $lot)
        <x-lot-card :lot="$lot" />
    @empty
        <p class="col-span-full rounded border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500">
            У цій категорії поки немає активних лотів.
        </p>
    @endforelse
</div>

<div class="mt-6">{{ $lots->links() }}</div>
@endsection
