# Швидкий старт для студента 2 курсу

> Цей документ — мінімальний шлях від `git clone` до працюючого AuctioHub на твоєму комп'ютері. Розраховано на студентів, які **ще не вчили Docker**.

## ⚠️ Важливе попередження про навчальний характер репо

У цьому репо **закомічено файли, які зазвичай НЕ комітять** у проекти:

| Файл | Чому нормально ігнорується | Чому ТУТ закомічено |
|---|---|---|
| [.env](.env) | Містить секрети (паролі БД, API-ключі, токени) | Щоб ти не налаштовував DB вручну — все працює одразу після clone |
| [database/database.sqlite](database/database.sqlite) | Локальна БД студента (різна у кожного) | Щоб у тебе вже були демо-дані (21 user, 50 лотів, 233 ставки) одразу |
| [public/build/](public/build/) | Зкомпільований CSS/JS — артефакт build | Щоб тобі не довелось одразу запускати `npm install && npm run build` |

**У СВОЇЙ курсовій** — НЕ комітити ці файли. Розкоментуй відповідні рядки у [.gitignore](.gitignore). Якщо випадково закомітив пароль — змінюй його ОДРАЗУ і викидай через `git filter-repo`.

## Мінімальні вимоги

| Інструмент | Версія | Як встановити |
|---|---|---|
| PHP | 8.2+ (тестовано на 8.5) | macOS: `brew install php`. Windows: XAMPP / Laragon. Linux: `apt install php8.3-cli php8.3-sqlite3 php8.3-mbstring php8.3-xml` |
| Composer | 2.x | macOS: `brew install composer`. Windows: [getcomposer.org](https://getcomposer.org/download/) |
| Node.js + npm | 22.x | [nodejs.org](https://nodejs.org/) — LTS версія |
| Git | 2.x | Зазвичай уже є; macOS: `brew install git` |

**Docker, MySQL, Redis — НЕ потрібні** для базового запуску.

## Запуск за 4 кроки

### 1. Clone + dependencies

```bash
git clone <url-this-repo> auctiohub
cd auctiohub
composer install
npm install
```

### 2. Згенерувати ключ застосунку

`.env` уже існує (з демо-значеннями), але `APP_KEY` порожній — згенеруй:

```bash
php artisan key:generate
```

### 3. (Опційно) Свіжа БД

`database/database.sqlite` уже містить демо-дані. Якщо хочеш чистий старт:

```bash
php artisan migrate:fresh --seed
```

### 4. Запустити веб-сервер

```bash
php artisan serve
```

Відкрий [http://localhost:8000](http://localhost:8000) у браузері.

## Тест-акаунти

| Роль | Email | Пароль |
|---|---|---|
| Admin | `admin@auctiohub.test` | `password` |
| User 1-5 | `user1@auctiohub.test` ... `user5@auctiohub.test` | `password` |

## Що працює одразу

- ✅ Anonymous browse: home, /lots з фільтрами, /lots/{slug}, /categories/{slug}, /search, /faq, /contact
- ✅ Auth: register (з email verification у логах), login, logout, password reset
- ✅ User actions (after login): bid, watchlist, comment, create lot
- ✅ Admin (admin@): dashboard з Chart.js, moderation, audit log, CSV export
- ✅ API: `/api/lots`, `/api/login`, `/api/user` (Sanctum tokens)
- ✅ Mail: листи пишуться у [storage/logs/laravel.log](storage/logs/laravel.log) (log driver)

## Опційно: запустити queue/scheduler

Для повноцінних outbid-emails + auto-close expired auctions потрібні 2 фонові процеси:

```bash
# у новому терміналі
php artisan queue:work

# ще в одному терміналі
php artisan schedule:work
```

Або в один шаг через 3 окремі термінали:

```bash
php artisan serve        # термінал 1: web
php artisan queue:work   # термінал 2: обробка emails
php artisan schedule:work # термінал 3: cron-таски
```

## Якщо щось не запускається

| Помилка | Розв'язок |
|---|---|
| `Class "PDO" not found` або `could not find driver: sqlite` | Встановити PHP-розширення: `brew install php` повинен включати, для Linux — `apt install php-sqlite3` |
| `failed to open stream: Permission denied storage/...` | `chmod -R 775 storage bootstrap/cache` |
| `The stream or file "/var/www/storage/logs/laravel.log" could not be opened` | Те саме `chmod` як вище |
| `vite manifest not found` | Запусти `npm run build` (або `npm run dev` для live reload) |
| `SQLSTATE[HY000] no such table` | `php artisan migrate:fresh --seed` |
| `419 Page Expired` при логіні | Очисти cookies, або вилогінься і знову залогінься |
| `Personal access client not found` | `php artisan passport:install` (тільки якщо ти ввімкнув Passport — за замовч. використовується Sanctum, не потрібно) |
| Перекладено англійською замість української | Перевір що в `.env` стоїть `APP_LOCALE=uk` |

## Перейти на MySQL (опційно)

SQLite — найпростіше, але має обмеження: **пошук кирилицею case-sensitive** (`Намисто` працює, `намисто` — ні). Це специфіка SQLite без ICU.

Для повноцінного пошуку — встанови MySQL:

### macOS
```bash
brew install mariadb
brew services start mariadb
mysql -u root -e "CREATE DATABASE auctiohub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Windows (XAMPP)
1. Завантаж XAMPP [apachefriends.org](https://www.apachefriends.org/)
2. У XAMPP Control Panel запусти **Apache** + **MySQL**
3. Відкрий [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
4. Створи нову БД `auctiohub` з кодуванням `utf8mb4_unicode_ci`

### Windows (Laragon)
1. Завантаж Laragon [laragon.org](https://laragon.org/)
2. Запусти — MySQL стартує автоматично
3. Через GUI → Database → New Database → `auctiohub`

Тоді у [.env](.env) розкоментуй MySQL-блок:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auctiohub
DB_USERNAME=root        # для XAMPP/MAMP/Laragon
DB_PASSWORD=            # для XAMPP/MAMP/Laragon (порожньо)
```

І закоментуй `DB_CONNECTION=sqlite`.

Тоді запусти:
```bash
php artisan migrate:fresh --seed
```

## 🛠 Що таке `artisan`?

`artisan` — це **CLI-інструмент Laravel'у**. Файл `artisan` лежить у корені проекту (це PHP-скрипт), і запускається завжди так:

```bash
php artisan <команда> [аргументи] [--опції]
```

### Аналоги в інших фреймворках

| Стек | Аналог `php artisan` |
|---|---|
| **Laravel** | `php artisan` |
| Symfony | `php bin/console` |
| Django (Python) | `python manage.py` |
| Rails (Ruby) | `rails` / `rake` |
| ASP.NET Core (.NET) | `dotnet` |

### Що вміє

**1. Запускати сервер та інструменти:**
```bash
php artisan serve           # dev-сервер на localhost:8000
php artisan tinker          # інтерактивна REPL для роботи з моделями
```

**2. Працювати з БД:**
```bash
php artisan migrate              # запустити нові міграції
php artisan migrate:fresh        # видалити ВСІ таблиці і створити заново
php artisan migrate:fresh --seed # + наповнити тестовими даними
php artisan migrate:rollback     # відкотити останню партію
php artisan db:seed              # тільки seeders без міграцій
```

**3. Генерувати файли зі шаблону** (це **ГОЛОВНА суперсила artisan**):
```bash
php artisan make:model Book -mf       # створить 3 файли одразу:
                                       #  - app/Models/Book.php
                                       #  - database/migrations/..._create_books_table.php
                                       #  - database/factories/BookFactory.php

php artisan make:controller BookController         # контролер
php artisan make:migration create_orders_table     # тільки міграція
php artisan make:request StoreBookRequest          # FormRequest валідація
php artisan make:policy BookPolicy --model=Book    # авторизація
php artisan make:middleware EnsureAdmin            # middleware
php artisan make:event OrderPlaced                 # подія
php artisan make:listener SendOrderConfirmation    # обробник події
php artisan make:notification OrderShipped         # сповіщення
php artisan make:command MyDailyTask               # CLI команда
php artisan make:test BookTest                     # тест
php artisan make:seeder BookSeeder                 # seeder
php artisan make:factory BookFactory               # factory
```

**4. Інформаційні:**
```bash
php artisan list                # ВСІ доступні команди (їх ~80+)
php artisan help migrate        # довідка по конкретній команді
php artisan route:list          # усі маршрути проекту
php artisan about               # стан проекту (версія, env, drivers)
```

**5. Очистка кешів** (якщо щось дивне відбувається):
```bash
php artisan optimize:clear      # очистити ВСІ кеші
php artisan config:clear        # тільки конфіг
php artisan view:clear          # тільки view-кеш
php artisan route:clear         # тільки route-кеш
```

**6. Auth + ключі:**
```bash
php artisan key:generate        # генерує APP_KEY у .env (унікальний для кожного інсталу)
php artisan storage:link        # symlink public/storage → storage/app/public (для uploads)
```

**7. Фонові процеси** (вже знайомі з AuctioHub):
```bash
php artisan queue:work          # обробка job'ів у черзі (mail, listeners)
php artisan queue:work --once   # одноразово (1 job)
php artisan schedule:work       # запускати scheduler (cron-like) для CloseExpiredAuctions
```

**8. Тести:**
```bash
php artisan test                       # запустити ВСІ тести
php artisan test --filter BidTest      # тільки BidTest
php artisan test tests/Feature/BidTest.php  # конкретний файл
```

### Власні команди

У AuctioHub є власна команда — [auctions:close](app/Console/Commands/CloseExpiredAuctions.php). Створено через `php artisan make:command CloseExpiredAuctions` і зареєстровано у [routes/console.php](routes/console.php):

```php
Schedule::command('auctions:close')->everyMinute()->withoutOverlapping();
```

Тепер вона:
- запускається вручну: `php artisan auctions:close`
- автоматично щохвилини коли запущено `php artisan schedule:work`

У своїй курсовій ти теж можеш створити свою команду (наприклад, `php artisan reminders:send` для розсилки нагадувань).

### Чому це зручно

1. **Один CLI на все** — створювати файли, мігрувати БД, чистити кеш, запускати сервер — все з одного інструмента.
2. **Шаблонна генерація** — `make:model Book -mf` створить 3 файли з правильним namespace, naming, базовим scaffold — НЕ треба писати вручну.
3. **Контекст застосунку** — `tinker` дає доступ до твоїх моделей: `App\Models\Lot::count()` працює прямо у REPL.
4. **Розширюваність** — `make:command` додає твою команду в `artisan list`.

### Документація

- Офіційна: [laravel.com/docs/11.x/artisan](https://laravel.com/docs/11.x/artisan)
- Список усіх команд у тебе локально: `php artisan list`
- Довідка по будь-якій: `php artisan help <команда>`

## Команди для роботи з курсовою

```bash
# Створити нову міграцію
php artisan make:migration create_my_table

# Створити модель + міграцію + factory
php artisan make:model MyModel -mf

# Створити контролер
php artisan make:controller MyController

# Створити FormRequest
php artisan make:request StoreMyRequest

# Створити Policy
php artisan make:policy MyPolicy --model=MyModel

# Список усіх маршрутів
php artisan route:list

# Запустити тести
php artisan test

# Чистити cache (якщо щось дивне)
php artisan optimize:clear
```

## Структура проекту (ключові директорії)

```
auctiohub/
├── app/
│   ├── Http/Controllers/    # обробка запитів
│   ├── Http/Requests/       # FormRequest валідація
│   ├── Http/Middleware/     # admin, banned check, locale
│   ├── Models/              # Eloquent (User, Lot, Bid, Category, ...)
│   ├── Policies/            # авторизація на CRUD
│   ├── Events/              # BidPlaced, AuctionEnded
│   ├── Listeners/           # обробка events → notifications
│   ├── Notifications/       # OutbidNotification та ін.
│   └── Console/Commands/    # CloseExpiredAuctions
├── database/
│   ├── migrations/          # схема БД
│   ├── factories/           # генерація фейкових даних
│   ├── seeders/             # початкові дані
│   └── database.sqlite      # сама БД (для SQLite)
├── resources/
│   ├── views/               # Blade templates
│   ├── css/                 # Tailwind input
│   └── js/                  # Alpine.js + app.js
├── routes/
│   ├── web.php              # публічні + auth маршрути
│   ├── api.php              # Sanctum REST API
│   └── console.php          # scheduled tasks
├── tests/Feature/           # E2E тести (Pest/PHPUnit)
└── docs/                    # документація
    ├── routes.md            # повна таблиця маршрутів
    ├── features-checklist.md # mapping на coursework feature-catalog.md
    └── screenshots/         # візуальна evidence
```

## Документація проекту

- [README.md](README.md) — головний огляд + mapping AuctioHub → твоя тема
- [docs/routes.md](docs/routes.md) — повна таблиця маршрутів (50+)
- [docs/features-checklist.md](docs/features-checklist.md) — які фічі реалізовано (mapping на coursework/feature-catalog.md)
- [database/er-diagram.md](database/er-diagram.md) — Mermaid ER-діаграма
- [docs/screenshots/](docs/screenshots/) — візуальна evidence ключових сторінок
- [docs/screenshots/e2e/](docs/screenshots/e2e/) — повний QA test report

## Що далі

Адаптуй цей проект під свою тему курсової — у [README § "How to adapt to your theme"](README.md#how-to-adapt-to-your-theme) є таблиця mapping (AuctioHub → Shop / Booking / Catalog).

⚠️ **НЕ копіювати один-в-один** — викладач помітить. Це **референс патернів**, не варіант з 30 LR4-5.

Удачі! 🚀
