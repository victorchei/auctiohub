@extends('layouts.public')

@section('content')
<h1 class="text-2xl font-bold">Часті запитання</h1>

<div class="mt-6 space-y-3">
    @foreach ($items as $item)
        <details class="rounded-lg border border-gray-200 bg-white p-4">
            <summary class="cursor-pointer text-sm font-medium text-gray-900">{{ $item['q'] }}</summary>
            <p class="mt-2 text-sm text-gray-700">{{ $item['a'] }}</p>
        </details>
    @endforeach
</div>
@endsection
