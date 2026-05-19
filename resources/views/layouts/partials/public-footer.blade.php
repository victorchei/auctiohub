@php
    $footerCategories = \App\Models\Category::whereNull('parent_id')->orderBy('name')->limit(8)->get(['id','name','slug']);
@endphp

<footer class="mt-16 border-t border-gray-200 bg-gray-900 text-gray-300">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

        {{-- 4-колонкова сітка --}}
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">

            {{-- Колонка 1: Brand --}}
            <div>
                <h3 class="text-xl font-bold text-white">⚖ AuctioHub</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-400">
                    Демо онлайн-аукціонної платформи на Laravel 11. Робіть ставки на унікальні лоти,
                    спостерігайте за фаворитами, отримуйте сповіщення про обігнані ставки.
                </p>
                <p class="mt-4 text-xs text-gray-400">
                    Reference implementation для курсової роботи з предмета
                    "Серверні технології та розробка бекенду".
                </p>
            </div>

            {{-- Колонка 2: Каталог --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Каталог</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('lots.index') }}" class="text-gray-400 hover:text-indigo-400">Усі лоти</a></li>
                    <li><a href="{{ route('lots.index', ['sort' => 'ending_soon']) }}" class="text-gray-400 hover:text-indigo-400">⏰ Завершуються скоро</a></li>
                    <li><a href="{{ route('lots.index', ['sort' => 'popular']) }}" class="text-gray-400 hover:text-indigo-400">🔥 Найпопулярніші</a></li>
                    <li><a href="{{ route('lots.index', ['sort' => 'newest']) }}" class="text-gray-400 hover:text-indigo-400">✨ Найновіші</a></li>
                    <li><a href="{{ route('search') }}" class="text-gray-400 hover:text-indigo-400">Пошук</a></li>
                </ul>
            </div>

            {{-- Колонка 3: Категорії --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Категорії</h4>
                <ul class="mt-4 grid grid-cols-1 gap-2 text-sm">
                    @foreach ($footerCategories as $category)
                        <li><a href="{{ route('categories.show', $category) }}" class="text-gray-400 hover:text-indigo-400">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Колонка 4: Акаунт + Інфо --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Акаунт</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-indigo-400">Мій кабінет</a></li>
                        <li><a href="{{ route('watchlist.index') }}" class="text-gray-400 hover:text-indigo-400">★ Список спостереження</a></li>
                        <li><a href="{{ route('lots.create') }}" class="text-gray-400 hover:text-indigo-400">+ Створити лот</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-indigo-400">Профіль</a></li>
                        @if (auth()->user()->isAdmin())
                            <li><a href="/admin/dashboard" class="text-amber-400 hover:text-amber-300">⚙ Адмін-панель</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-indigo-400">Увійти</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-indigo-400">Реєстрація</a></li>
                        <li><a href="{{ route('password.request') }}" class="text-gray-400 hover:text-indigo-400">Забули пароль?</a></li>
                    @endauth
                </ul>

                <h4 class="mt-6 text-sm font-semibold uppercase tracking-wider text-white">Інформація</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-indigo-400">FAQ</a></li>
                    <li><a href="{{ route('contact.show') }}" class="text-gray-400 hover:text-indigo-400">Контакти</a></li>
                    <li><a href="{{ url('/api/lots') }}" class="text-gray-400 hover:text-indigo-400" target="_blank" rel="noopener">REST API</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom: copyright + tech stack + language switcher --}}
        <div class="mt-12 border-t border-gray-800 pt-6">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="text-xs text-gray-300">
                    © {{ date('Y') }} AuctioHub.
                    Навчальний проект для курсової з предмета
                    "Серверні технології та розробка бекенду".
                </div>

                <div class="flex items-center gap-4 text-xs text-gray-300">
                    <span title="Стек">Laravel 11 · PHP {{ phpversion() }} · Tailwind 3 · Alpine.js</span>
                    <span class="hidden sm:inline">·</span>
                    <span>
                        <a href="?lang=uk" class="@if(app()->getLocale()==='uk') font-semibold text-indigo-300 @else text-gray-300 hover:text-indigo-300 @endif">UA</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=en" class="@if(app()->getLocale()==='en') font-semibold text-indigo-300 @else text-gray-300 hover:text-indigo-300 @endif">EN</a>
                    </span>
                </div>
            </div>

            <div class="mt-3 text-center text-xs text-gray-300">
                ⚠️ Демо проект. НЕ копіювати у свою курсову один-в-один —
                <a href="https://github.com/" class="underline text-gray-200 hover:text-indigo-300" target="_blank" rel="noopener">адаптуй патерни під свою тему</a>.
            </div>
        </div>
    </div>
</footer>
