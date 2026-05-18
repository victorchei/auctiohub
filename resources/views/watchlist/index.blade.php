@extends('layouts.public')

@section('content')
<h1 class="text-2xl font-bold">★ Ваш список спостереження</h1>
<p class="mt-1 text-sm text-gray-600">Лоти за якими ви слідкуєте.</p>

<div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @forelse ($lots as $lot)
        <x-lot-card :lot="$lot" />
    @empty
        <p class="col-span-full rounded border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500">
            Список порожній. Додавайте лоти кнопкою «☆ Спостерігати» на сторінці лоту.
        </p>
    @endforelse
</div>

<div class="mt-6">{{ $lots->links() }}</div>
@endsection
