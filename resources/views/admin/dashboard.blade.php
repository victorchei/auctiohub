@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold">Dashboard</h1>

<div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-5">
    @foreach ([
        'Користувачі' => $stats['users'],
        'Лотів усього' => $stats['lots_total'],
        'Активних' => $stats['lots_active'],
        'Ставок' => $stats['bids'],
        'Заблоковано' => $stats['banned'],
    ] as $label => $value)
        <div class="rounded-lg bg-white p-4 shadow">
            <div class="text-xs uppercase text-gray-500">{{ $label }}</div>
            <div class="mt-1 text-3xl font-bold text-indigo-700">{{ $value }}</div>
        </div>
    @endforeach
</div>

<div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="text-lg font-semibold">Ставки за 30 днів</h2>
        <canvas id="bidsChart" class="mt-4 max-h-72"></canvas>
        <script>
            new Chart(document.getElementById('bidsChart'), {
                type: 'line',
                data: {
                    labels: @json($bidsLast30Days->pluck('day')),
                    datasets: [{
                        label: 'Ставок',
                        data: @json($bidsLast30Days->pluck('count')),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        </script>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="text-lg font-semibold">Топ-10 продавців</h2>
        <ol class="mt-3 divide-y divide-gray-200">
            @foreach ($topSellers as $seller)
                <li class="flex justify-between py-2 text-sm">
                    <span>{{ $loop->iteration }}. {{ $seller->name }}</span>
                    <span class="font-medium text-indigo-700">{{ $seller->lots_count }} лотів</span>
                </li>
            @endforeach
        </ol>
    </div>
</div>
@endsection
