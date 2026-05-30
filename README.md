# AuctioHub

> Reference Laravel 11 implementation для курсової роботи з предмета "Серверні технології та розробка бекенду". Тема: онлайн-аукціонна платформа.

## 🚀 Студенту 2 курсу — швидкий старт (БЕЗ Docker)

Якщо ти новий — спершу прочитай **[STUDENT_QUICKSTART.md](STUDENT_QUICKSTART.md)**: clone → 4 команди → запуск. Працює на SQLite (БЕЗ MySQL/Docker).

⚠️ **Навчально закомічено** (зазвичай у `.gitignore`): `.env`, `database/database.sqlite`, `public/build/`. Для зручного clone-and-run. **У своїй курсовій НЕ комітити!**

**Призначення.** Робочий приклад-еталон Laravel-варіанту курсової (Опція A, до 100 балів). Демонструє Advanced рівень функціональності. Студент бачить, як одна реалізація вкладається в усі вимоги [coursework/feature-catalog.md](../php-labs/coursework/feature-catalog.md), і використовує патерни для своєї теми.

⚠️ **НЕ копіювати один-в-один.** Це не варіант з 30 — викладач помітить. Адаптуй структуру під свою тему (mapping див. кінець файлу).

## Stack

| Layer | Версія / вибір |
| --- | --- |
| PHP | 8.5 (>=8.2) |
| Framework | Laravel 11.52 |
| Auth | Laravel Breeze (Blade + Alpine, default Tailwind) |
| БД | На вибір: **SQLite** (для 2 курсу, БЕЗ Docker) / **MySQL** (XAMPP, MAMP, Laragon, brew, або Docker) — див. розділ «3. База даних» |
| Queue | database driver |
| Mail | log driver (для розробки) |
| Test | Pest |
| Frontend | Tailwind CSS 3 + Alpine.js + Chart.js + lightbox.js |

## Quick Start

### 1. Залежності
```bash
composer install
npm install
```

### 2. Environment
```bash
cp .env.example .env
php artisan key:generate
```

Після `cp` відредагуй у `.env`:
```ini
APP_NAME=AuctioHub
APP_LOCALE=uk
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=uk_UA

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auctiohub
DB_USERNAME=auctiohub
DB_PASSWORD=secret

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@auctiohub.test"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. База даних

**Швидкий вибір:**

| Ситуація | Рішення |
| --- | --- |
| Перший запуск, хочу побачити як працює | **SQLite** — нічого не встановлювати |
| Університет вимагає MySQL | **Локальний MySQL** (XAMPP/MAMP/brew) |
| Знайомий з Docker | **Docker** — одна команда |

#### Варіант A: SQLite ⭐ рекомендовано для знайомства

Нічого встановлювати не треба. Просто виправити `.env`:

```ini
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Решту `DB_HOST`, `DB_PORT`, `DB_DATABASE=auctiohub`, `DB_USERNAME`, `DB_PASSWORD` — закоментувати або видалити.

> ⚠️ **Обмеження SQLite**: пошук кирилицею регістрозалежний (`Намисто` знаходить, `намисто` — ні). Для повноцінного пошуку потрібен MySQL.

#### Варіант B: Локальний MySQL (XAMPP / MAMP / brew)

1. **XAMPP / MAMP (Windows/macOS)**: запустіть MySQL, відкрийте phpMyAdmin → створіть БД `auctiohub` (utf8mb4_unicode_ci).
2. **macOS Homebrew**:

   ```bash
   brew install mariadb && brew services start mariadb
   mysql -u root -e "CREATE DATABASE auctiohub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

3. **Linux**: `sudo apt install mariadb-server && sudo mysql -e "CREATE DATABASE auctiohub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`

У `.env` залишити `DB_CONNECTION=mysql`, вказати свої `DB_USERNAME`/`DB_PASSWORD` (для XAMPP/MAMP зазвичай `root` без пароля).

#### Варіант C: MySQL через Docker

```bash
docker compose up -d   # запускає MySQL 8 на порту 3306
docker compose ps      # дочекатись статусу healthy (~10 сек)
```

Параметри з `docker-compose.yml` уже відповідають `.env.example` — правок не потрібно.

### 4. Міграції + сіди
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 5. Build assets + servers
```bash
npm run build  # або: npm run dev — для hot reload
php artisan queue:work &      # обробка job'ів (mail, scheduled)
php artisan schedule:work &   # CloseExpiredAuctions кожну хвилину
php artisan serve             # http://localhost:8000
```

## Test Accounts

Після `migrate:fresh --seed`:
- **Admin:** `admin@auctiohub.test` / `password`
- **Users:** `user1@auctiohub.test` ... `user5@auctiohub.test` / `password`

## Features (~100 балів за критеріями coursework)

Детальний mapping на [coursework/feature-catalog.md](../php-labs/coursework/feature-catalog.md) — у [docs/features-checklist.md](docs/features-checklist.md).

**Мінімум 🟢:** CRUD усіх сутностей, Auth (Breeze), категорії, пошук, фільтр, watchlist, статуси, валідація, CSRF/XSS, hash паролів, responsive, flash, public profile, contact form, FAQ, admin dashboard.

**Extended 🟡 (14):** Multifilter, sort, аватари, зіркова оцінка, email на реєстрацію + outbid + won, gallery з lightbox, soft delete + Trash, audit log, bulk actions, CSV export, i18n (UK/EN), toast, breadcrumbs, rate limiting.

**Advanced 🔴 (3):**
1. Scheduled command + Queue (`auctions:close` → `AuctionEnded` event → mail jobs)
2. REST API через Sanctum
3. Вкладені категорії (parent_id self-FK)

**Wow ⭐ (2):**
1. Live countdown timer + AJAX polling current_price
2. Chart.js dashboard (ставки за 30 днів)

## Architecture

```
app/
├── Console/Commands/    — CloseExpiredAuctions
├── Events/              — BidPlaced, AuctionEnded, LotCancelled
├── Http/Controllers/    — web (LotController, BidController, ...)
├── Http/Controllers/Admin/  — admin panel
├── Http/Controllers/Api/    — Sanctum endpoints
├── Http/Requests/       — FormRequests з валідацією
├── Http/Resources/      — API JSON формат
├── Http/Middleware/     — EnsureAdmin, EnsureNotBanned, SetLocale
├── Listeners/           — обробляють events → notifications/mail
├── Mail/                — Mailables
├── Models/              — Eloquent (7 сутностей)
├── Notifications/       — database + mail channels
├── Observers/           — AuditObserver
└── Policies/            — авторизація на CRUD
```

## Domain Model

```
User ───────┬─< Lot (seller_id)
            ├─< Bid (user_id)
            ├─< Comment (user_id)
            ├─< Review (buyer_id, seller_id)
            └─< Watchlist (M:N з Lot)

Category ───┬─< Lot (category_id)
            └─> Category (parent_id, self-ref — вкладені)

Lot ────────┬─< LotImage (lot_id)
            ├─< Bid (lot_id)
            ├─< Comment (lot_id)
            └─< Review (lot_id)
```

ER-діаграма в Mermaid: [database/er-diagram.md](database/er-diagram.md).

## Тести

```bash
php artisan test
```

Покриття у `tests/Feature/`:
- `BidTest` — race condition (2 concurrent bids), validation, ban check
- `AuctionCloseTest` — scheduled command закриває expired, фіксує winner
- `PolicyTest` — авторизація edit/delete на лотах і коментарях
- `ApiAuthTest` — Sanctum tokens, rate limit
- `SearchTest` — multifilter, sort

## How to adapt to your theme

Замінити сутності AuctioHub на свої. Приклад mapping під 3 типи систем з [coursework/functionality-flow.md](../php-labs/coursework/functionality-flow.md):

| AuctioHub | Shop (A) — Книгарня | Booking (B) — Стоматологія | Catalog (C) — Музей |
|---|---|---|---|
| `Lot` | `Book` (товар) | `Service` | `Exhibit` |
| `Bid` | `OrderItem` | `Appointment` | (немає, лише перегляд) |
| `Watchlist` | `Cart` (session-based) | (немає) | `Favorites` |
| `Comment` | `Review` товару | `AppointmentNote` | `Comment` до експонату |
| `Review` (after auction end) | `Review` тільки куплене | `Review` після відвіду | (немає) |
| `Category` | `Genre` | `Department` | `Era` / `Hall` |
| `CloseExpiredAuctions` | (немає) | `RemindUpcomingAppointments` | (немає) |
| `OutbidNotification` | (немає) | `AppointmentReminder` | (немає) |
| `AuctionEnded` event | `OrderShipped` event | `AppointmentCompleted` event | (немає) |

Кроки міграції:
1. Перейменувати сутності: моделі, міграції, контролери, маршрути, views.
2. Виправити business rules: ставки (highest wins) → корзина (sum of items) або бронювання (slot uniqueness).
3. Видалити нерелевантні фічі: countdown timer (тільки для аукціонів і бронювань), winner-based review.
4. Залишити патерни: FormRequest валідація, Policy авторизація, Eloquent зв'язки, Soft delete, Audit log, i18n, Sanctum API — це універсальні Laravel-практики.

## Документація

- [`.claude/CLAUDE.md`](.claude/CLAUDE.md) — інструкції Claude
- [`.claude/CHANGELOG.md`](.claude/CHANGELOG.md) — історія змін
- [`docs/features-checklist.md`](docs/features-checklist.md) — mapping на feature-catalog.md (заповнено у Phase 7)
- [`docs/routes.md`](docs/routes.md) — повний список маршрутів (Phase 7)
- [`database/er-diagram.md`](database/er-diagram.md) — Mermaid ER (Phase 2)
- [`docs/screenshots/`](docs/screenshots/) — скриншоти UI (Phase 7)

## Sibling resources

Цей репо координується з [php-labs-ai-middleware/php-labs/coursework/](../php-labs/coursework/):
- [`assignment.md`](../php-labs/coursework/assignment.md) — повні вимоги до курсової
- [`feature-catalog.md`](../php-labs/coursework/feature-catalog.md) — каталог фіч
- [`system-design.md`](../php-labs/coursework/system-design.md) — обов'язкові архітектурні блоки
- [`schemas.md`](../php-labs/coursework/schemas.md) — еталонні схеми БД
- [`defense-checklist.md`](../php-labs/coursework/defense-checklist.md) — питання на захисті
- [`typical-mistakes.md`](../php-labs/coursework/typical-mistakes.md) — як втрачаються бали

## License

Навчальний матеріал. Не для AI-тренінгу. © 2026 Viktor Zhelizko.
