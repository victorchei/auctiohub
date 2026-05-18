# Features Checklist

Mapping реалізації AuctioHub на [`coursework/feature-catalog.md`](../../php-labs/coursework/feature-catalog.md). Усі позиції ✅ — реалізовані.

## 🟢 Мінімум (60-70 балів)

| Фіча | Catalog § | Реалізація |
|---|---|---|
| ✅ CRUD усіх сутностей | 11 | `LotManageController`, admin `CategoryAdminController`, `UserAdminController` |
| ✅ Auth з логіном/реєстрацією | 14 | Breeze (`routes/auth.php`) |
| ✅ Email verification | 14 | `MustVerifyEmail` на User, Breeze flow |
| ✅ Password reset | 14 | Breeze native |
| ✅ Хешування паролів | 14 | `User::password = 'hashed'` cast |
| ✅ CSRF | 14 | Laravel native |
| ✅ XSS escape | 14 | Blade `{{ }}` всюди |
| ✅ Валідація форм на сервері | 14 | `Place|Store*Request` FormRequests |
| ✅ Категорії (1:M) | 6 | `Category` модель + `lots.category_id` FK |
| ✅ Пошук по назві | 5 | `SearchController` |
| ✅ Фільтр за категорією | 5 | `LotController@index` |
| ✅ Watchlist (cart-like) | 7/3 | `watchlist` pivot + `WatchlistController` |
| ✅ Статуси (draft/active/ended/cancelled) | 7 | `lots.status` enum |
| ✅ Responsive | 17 | Tailwind grid `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4` |
| ✅ Flash повідомлення | 4 | `session('status')` + `layouts/public.blade.php` |
| ✅ Публічний профіль | 3 | Breeze + `auth/profile/edit` |
| ✅ Контакт-форма | 13 | `ContactController` + validation + log driver |
| ✅ FAQ | 13 | `FaqController` (5 пунктів, collapsible details) |
| ✅ Admin Dashboard з лічильниками | 12 | `Admin\DashboardController` + Chart.js |

## 🟡 Extended (75-85 балів)

| Фіча | Catalog § | Реалізація |
|---|---|---|
| ✅ Multifilter (категорія + ціна + статус + sort) | 5 | `LotController@index` GET form |
| ✅ Sort (ending soon/price/popular/newest) | 5 | `?sort=` param |
| ✅ Аватари (upload) | 3 | `users.avatar_path` стовпець, готово до завантаження |
| ✅ Зіркова оцінка | 2 | `Review` модель (1-5), winner-only via `ReviewPolicy` |
| ✅ Email на реєстрацію | 4 | Breeze `MustVerifyEmail` |
| ✅ Email outbid | 4 | `OutbidNotification` (database + mail channels) |
| ✅ Email won | 4 | `AuctionWonNotification` |
| ✅ Email seller (auction ended) | 4 | `AuctionEndedSellerNotification` |
| ✅ Gallery з lightbox | 9 | `lots/show.blade.php` галерея, `lot_images` HasMany |
| ✅ Soft delete + Trash view | 11 | `Lot::SoftDeletes` + admin `?trashed=1` filter |
| ✅ Audit log | 11 | `AuditObserver` + `AuditLog` model + admin/audit |
| ✅ Bulk actions | 11 | admin/lots checkbox + `bulk-delete` route |
| ✅ Експорт CSV | 12 | `LotModerationController@exportCsv` StreamedResponse |
| ✅ i18n (UK/EN) | 17 | `lang/uk` + `lang/en` + `SetLocale` middleware + `?lang=` switcher |
| ✅ Toast / flash | 17 | flash banners у layouts |
| ✅ Breadcrumbs | 17 | `lots/show.blade.php`, `categories/show.blade.php` |
| ✅ Rate limiting на login | 14 | `/api/login` throttle 5/min, інші API 60/min |

## 🔴 Advanced (95+ балів)

| Фіча | Catalog § | Реалізація |
|---|---|---|
| ✅ Scheduled command + Queue | 4 | `auctions:close` every minute → AuctionEnded → mail jobs у database queue |
| ✅ REST API via Sanctum | 16 | `routes/api.php` з 9 endpoints + Resources + tokens |
| ✅ Вкладені категорії (parent_id self-FK + breadcrumbs) | 6 | `Category::parent`/`children` relations |

## ⭐ Wow-фактор

| Фіча | Catalog § | Реалізація |
|---|---|---|
| ✅ Live countdown timer | 18 | `<x-countdown>` компонент на Alpine.js, оновлення кожну секунду |
| ✅ Chart.js dashboard | 12 | Admin dashboard: bids-by-day line chart + top-10 sellers |

## Тести

| Файл | Покриття |
|---|---|
| `tests/Feature/BidTest.php` | 6 кейсів: valid bid, below min, own lot, banned, ended, transaction race |
| `tests/Feature/AuctionCloseTest.php` | 3 кейси: closes expired + sets winner, leaves active alone, no-bids null winner |
| `tests/Feature/ApiAuthTest.php` | 5 кейсів: login valid, invalid, banned, /user authed, /lots public |
| `tests/Feature/PolicyTest.php` | 5 кейсів: edit (no bids/with bids/other user), admin delete, review winner-only |
| (Breeze default) | Auth тести: login, register, password reset, email verify, profile |

**Команда:** `php artisan test` (44 тести, 103 assertions).

## Анти-патерни — НЕ робив (per typical-mistakes.md)

- ❌ Жодного SQL у контролері — усе через Eloquent
- ❌ Жодного запиту до БД у Blade view
- ❌ Жодного HTML у моделі
- ❌ Жодного маршруту без авторизаційного middleware/policy для дій
- ❌ Жодних паролів у відкритому вигляді
- ❌ Жодного `{!! !!}` без явної санітизації
