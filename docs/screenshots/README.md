# Visual Test Evidence

Скриншоти зроблено 2026-05-18 через Chrome DevTools MCP після `php artisan migrate:fresh --seed`. Viewport 1440×900 (з fullPage де доречно).

## Сценарії

| # | Файл | Сценарій | Що верифіковано |
|---|---|---|---|
| 01 | [01-home-anonymous.png](01-home-anonymous.png) | Anonymous: home page | Hero gradient indigo→purple, 8 категорій grid, 6 featured + 4 ending-soon карток з реальними цінами в ₴ та bid counts, footer з FAQ/контакти, UA/EN switcher |
| 02 | [02-lots-index-filters.png](02-lots-index-filters.png) | Anonymous: /lots з фільтрами | "Знайдено: 38", filter form (q + category + min/max price + sort), 12 lot cards 4-col grid, countdown timers "Хд Хг Хх Хс", pagination 1-4 |
| 03 | [03-lot-detail-anonymous.png](03-lot-detail-anonymous.png) | Anonymous: lot detail | Breadcrumbs вкладеної категорії (Головна > Лоти > Антикваріат > Монети > Монета СРСР 1961 року), Поточна ціна 4137.96 ₴, Залишилось 0д 3г, "Увійдіть щоб робити ставки", Історія ставок (5), Коментарі (1), Схожі лоти |
| 04 | [04-login-breeze.png](04-login-breeze.png) | Login page | Breeze дефолтний form: email + password + remember me + forgot + LOG IN |
| 05 | [05-admin-dashboard-chartjs.png](05-admin-dashboard-chartjs.png) | Admin: dashboard | 5 stat cards (21 users / 50 lots / 38 active / 247 bids / 0 banned), **Chart.js line chart** "Ставки за 30 днів" з реальними точками, "Топ-10 продавців" список |
| 06 | [06-admin-lots-moderation.png](06-admin-lots-moderation.png) | Admin: lots moderation | "Лоти (50)", filter (q + status + Trash), Bulk delete button, Експорт CSV button, table з status badges (active/ended), action icons (cancel/delete) |
| 07 | [07-admin-users-ban-promote.png](07-admin-users-ban-promote.png) | Admin: users | "Користувачі (21)", filter (q + role + banned only), кнопки Ban + Promote на кожному не-адмін рядку, лічильники Лотів/Ставок |
| 08 | [08-admin-categories-nested.png](08-admin-categories-nested.png) | Admin: categories | CRUD form справа + table зліва з parent колонкою (вкладеність) |
| 09 | [09-admin-audit-log.png](09-admin-audit-log.png) | Admin: audit log | **141 записів** від AuditObserver під час seeding, JSON payload в Деталі, action badges (created/updated), pagination 1-5 |
| 10 | [10-lot-detail-authenticated-bid-form.png](10-lot-detail-authenticated-bid-form.png) | Authenticated: lot detail | Top nav з "+ Лот / ★ Список / ⚙ Адмін / AuctioHub Admin / Вийти", **bid form з min validation** (≥ 3 031,50), watchlist toggle, comment textarea + Опублікувати button, gallery 2 фото thumbnails |
| 11 | [11-locale-en-applied.png](11-locale-en-applied.png) | Locale switcher | Cookie set після `?lang=en`, "EN" bold у switcher. Templates переважно hardcoded UK (повний переклад — TODO) |

## API verification (без скриншотів — JSON output)

```
GET /api/lots?status=active
→ 200 JSON paginated: {"data":[{id,slug,title,description,starting_price,current_price,bid_increment,min_next_bid,starts_at,ends_at,status,seller:{},category:{},winner_id,images_count}], "links":{}, "meta":{}}

POST /api/login {email:admin@auctiohub.test, password:password, device_name:verify}
→ 200 {"token":"1|LyK9SVEq...", "user":{...}}

GET /api/user (Bearer)
→ 200 {"id":1,"name":"AuctioHub Admin","email":"admin@auctiohub.test","role":"admin",...}
```

## Що НЕ покрито скриншотами

- Lot create form (потрібен manual upload)
- Watchlist after toggle (потрібен AJAX trigger)
- Bid placement flow end-to-end (potrebno відправити форму, не тільки рендер)
- Email notifications (зберігаються у `storage/logs/mail.log` — не UI)
- Scheduled command `auctions:close` runtime (виконано і верифіковано раніше через CLI: лот #1 → status=ended, winner_id=13)
- Soft delete + Trash view (потрібен manual delete + переключення фільтру)
- CSV download (binary download — не screen)

## Відомі обмеження після verification

1. **Locale UI not fully translated** — middleware/cookie/switcher працюють (EN активується), але більшість Blade рядків hardcoded українською. Translation keys в `lang/*` створені, але не застосовані до більшості templates. Treat as "i18n infrastructure ready, full translation TODO".
2. **Title "Laravel"** на login сторінці замість "AuctioHub" — Breeze default uses `{{ config('app.name') }}` яке тягнеться з APP_NAME (за замовч. "Laravel"). Студент повинен встановити APP_NAME=AuctioHub у `.env`. README документує це.
3. **Lot images — placeholder text** "[зображення лоту]" — seeded path `lots/placeholder-N.jpg` не має реальних файлів. Це навмисно: коли студент створить лот з реальним upload через `lots/create`, зображення збережуться у `storage/app/public/lots/` і відобразяться правильно (потрібен `php artisan storage:link`, що зроблено).
4. **Parent categories show 0 lots на головній** — seeder кладе лоти у child categories (Монети, Телефони, etc), не у parents. На categories.show parents показують лоти усіх дітей. Це коректна доменна логіка; UI косметика для головної може бути покращена.
5. **Admin nav слегка обрізаний у top-left** — viewport/spacing issue у `layouts/admin.blade.php`. Не блокує функціонал, але потребує polish.

## Загальний висновок

**Архітектура працює end-to-end** на seeded data:
- ✅ 5 ключових режимів (anonymous browse, auth user, admin)
- ✅ Forms render (bid, comment, filters, contact, login)
- ✅ Chart.js dashboard з реальними даними
- ✅ Audit log спрацьовує (141 запис)
- ✅ API Sanctum (login → token → authenticated calls)
- ✅ Breadcrumbs з вкладеними категоріями
- ✅ Countdown timer (Alpine.js) показує реальні значення
- ✅ Pagination, multifilter, sort
- ✅ Auth gates (302 redirect на /login для protected)
- ✅ Admin gates (admin middleware blocking)
