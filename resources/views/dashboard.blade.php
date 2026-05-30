<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('messages.dashboard.title') }}
            </h2>
            <a href="{{ route('home') }}"
               class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ __('messages.dashboard.go_to_site') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Welcome banner --}}
            <div class="rounded-lg bg-indigo-50 border border-indigo-200 px-6 py-4 mb-6">
                <p class="text-indigo-800 font-medium">
                    {{ __('messages.dashboard.welcome', ['name' => auth()->user()->name]) }}
                </p>
                <p class="mt-1 text-sm text-indigo-600">{{ auth()->user()->email }}</p>
            </div>

            {{-- Quick actions grid --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <a href="{{ route('lots.index') }}"
                   class="flex flex-col items-center justify-center rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:border-indigo-300 hover:shadow-md transition">
                    <span class="text-3xl">🏷️</span>
                    <span class="mt-2 text-sm font-medium text-gray-800">{{ __('messages.dashboard.browse_lots') }}</span>
                </a>

                <a href="{{ route('lots.create') }}"
                   class="flex flex-col items-center justify-center rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:border-indigo-300 hover:shadow-md transition">
                    <span class="text-3xl">➕</span>
                    <span class="mt-2 text-sm font-medium text-gray-800">{{ __('messages.dashboard.create_lot') }}</span>
                </a>

                <a href="{{ route('watchlist.index') }}"
                   class="flex flex-col items-center justify-center rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:border-indigo-300 hover:shadow-md transition">
                    <span class="text-3xl">★</span>
                    <span class="mt-2 text-sm font-medium text-gray-800">{{ __('messages.dashboard.watchlist') }}</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="flex flex-col items-center justify-center rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:border-indigo-300 hover:shadow-md transition">
                    <span class="text-3xl">⚙️</span>
                    <span class="mt-2 text-sm font-medium text-gray-800">{{ __('messages.dashboard.profile') }}</span>
                </a>
            </div>

            @if (auth()->user()->isAdmin())
                <div class="mt-4">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-2 rounded-lg border border-amber-300 bg-amber-50 px-5 py-3 text-sm font-medium text-amber-800 hover:bg-amber-100 transition">
                        ⚙ {{ __('messages.dashboard.admin_panel') }}
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
