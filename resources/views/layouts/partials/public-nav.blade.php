<nav class="border-b border-gray-200 bg-white shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-700">
                AuctioHub
            </a>
            <div class="hidden gap-4 md:flex">
                <a href="{{ route('lots.index') }}" class="text-sm text-gray-700 hover:text-indigo-600">Усі лоти</a>
                <a href="{{ route('faq') }}" class="text-sm text-gray-700 hover:text-indigo-600">FAQ</a>
                <a href="{{ route('contact.show') }}" class="text-sm text-gray-700 hover:text-indigo-600">Контакти</a>
            </div>
        </div>

        <form action="{{ route('search') }}" method="GET" class="hidden flex-1 max-w-md mx-6 md:flex">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Пошук лоту..."
                   class="w-full rounded-l border border-gray-300 px-3 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <button type="submit" class="rounded-r bg-indigo-600 px-3 text-sm text-white hover:bg-indigo-700">Шукати</button>
        </form>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-indigo-600">{{ auth()->user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Вийти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600">Увійти</a>
                <a href="{{ route('register') }}" class="rounded bg-indigo-600 px-3 py-1.5 text-sm text-white hover:bg-indigo-700">Реєстрація</a>
            @endauth
        </div>
    </div>
</nav>
