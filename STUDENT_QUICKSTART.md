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
