# AuctioHub — Claude Instructions

Laravel 11 demo проект — еталонна реалізація курсової роботи для курсу "Серверні технології та розробка бекенду". Тематика: онлайн-аукціонна платформа.

## Common Layer (life-ecosystem)

Inherits: [life-ecosystem CLAUDE.md](../../../../../CLAUDE.md)

## Sibling Project

Координується з [php-labs-ai-middleware/php-labs/coursework/](../../php-labs/coursework/) — там розписані вимоги, схеми, фічі для курсової. AuctioHub реалізує всі мінімум 🟢 + 14 🟡 + 3 🔴 + 2 ⭐ з [feature-catalog.md](../../php-labs/coursework/feature-catalog.md).

## Planning

- [plane/backlog.md](plane/backlog.md) — беклог проекту
- [CHANGELOG.md](CHANGELOG.md) — історія змін
- Master plan (видалено після завершення) — деталі у `.claude/CHANGELOG.md`

## Stack

| Layer | Version |
|-------|---------|
| PHP | 8.5 (системний brew, з deprecation warnings для PDO константи — нелатально) |
| Laravel | 11.52.0 |
| Auth | Breeze (Blade + Alpine) |
| DB | MySQL 8 через Docker (`docker-compose.yml`) |
| Queue | database |
| Mail | log driver (для розробки), Mailtrap для боротьба з spam-filter |
| Test | Pest |
| Frontend | Tailwind CSS 3 (Breeze default) + Alpine.js + Chart.js + lightbox.js |

## Test Accounts (after `php artisan migrate:fresh --seed`)

- Admin: `admin@auctiohub.test` / `password`
- Users: `user1@auctiohub.test` ... `user5@auctiohub.test` / `password`

## Local Setup

```bash
composer install
npm install && npm run build
cp .env.example .env && php artisan key:generate
docker compose up -d  # MySQL 8 на порту 3306
php artisan migrate:fresh --seed
php artisan storage:link
php artisan queue:work &
php artisan schedule:work &
php artisan serve  # http://localhost:8000
```

## Reused Resources from `php-labs/coursework/`

| Що | Звідки | Як |
|---|--------|---|
| Schemas | `coursework/schemas.md` | Адаптація Shop-схеми → Lots+Bids |
| Migrations style | `coursework/migrations-seeders-example.md` | Той самий стиль |
| Code patterns | `coursework/code-patterns.md` | FormRequest, Policy, Cart-like для watchlist |
| Defense questions | `coursework/defense-checklist.md` | Self-check |
| Typical mistakes | `coursework/typical-mistakes.md` | Self-review |
| Feature catalog | `coursework/feature-catalog.md` | Mapping в `docs/features-checklist.md` |

## How students use this repo

1. Clone окремо від `php-labs-ai-middleware`
2. Запустити локально
3. Подивитись як реалізовано конкретну фічу (`docs/features-checklist.md` → файл → код)
4. Адаптувати під свою тему: замінити сутності AuctioHub на свої (Lots→Books, Bids→Orders, тощо)
5. **НЕ копіювати один-в-один** — викладач помітить
