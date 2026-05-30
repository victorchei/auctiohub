# Changelog

## 2026-05-30

### Session 2 — Doc review fixes: translations, dashboard UX, lab docs

Fixed:

- **Translation coverage** — added `lots.*`, `search.*` (title/enter_query/results_meta), `dashboard.*`, `watchlist.*`, `faq.*` keys to `lang/uk/messages.php` + `lang/en/messages.php`
- **search/index.blade.php** — replaced all hardcoded Ukrainian strings with `__('messages.search.*')` keys
- **lots/index.blade.php** — all filter labels, sort options, result count, empty state now use translation keys
- **lots/show.blade.php** — breadcrumbs, seller label, price labels, bid history, comments, watchlist buttons, similar lots section now translated
- **watchlist/index.blade.php** + **faq/index.blade.php** — page titles/descriptions now use translation keys
- **dashboard.blade.php** — replaced blank "You're logged in!" with proper user hub (welcome, quick-action cards for lots/create/watchlist/profile, admin link)
- **navigation.blade.php** (app layout) — added "← На сайт" and "Усі лоти" links so logged-in users can navigate to the public site
- **lr4/assignment.md** — split confusing launch instruction into separate demo vs student-work commands with clear `cd lr4/demo` vs `cd lr4` distinction
- **lr5/assignment.md** — added decision table (SQLite vs MySQL), macOS Homebrew MySQL command, Windows XAMPP guide, documented v31 as optional bonus variant
- **README.md** — replaced 3-variant DB section header with quick-choice table; clarified that SQLite is the recommended starting point

Tests: 44 tests / 103 assertions — all pass (pre-existing PHP 8.5 PDO deprecation warnings are non-fatal, documented in CLAUDE.md)

## 2026-05-18

### Session 1 — Phase 0: Repo Init

Added:
- Laravel 11.52.0 scaffold via `composer create-project laravel/laravel:^11.0`
- `.claude/CLAUDE.md` з inheritance від life-ecosystem
- `.claude/CHANGELOG.md`, `.claude/plane/backlog.md`
- README.md з інструкцією локального запуску
- `.env.example` (MySQL config через Docker)
- `docker-compose.yml` (MySQL 8)
- Nested git repo (parent `php-labs-ai-middleware/.gitignore` доданий `/auctiohub/`)

Decisions:
- PHP 8.5 (системний brew) + Laravel 11 + MySQL 8 (Docker) + Pest + Tailwind 3 (Breeze default)
- Repo: nested git, gitignored у parent (той самий patern що `php-labs/`)
- Тема: AuctioHub (онлайн-аукціон, унікальна vs 30 тем LR4-5)

Known issues:
- PHP 8.5 deprecation warnings про `PDO::MYSQL_ATTR_SSL_CA` у `vendor/laravel/framework/config/database.php`. Не блокер — старий API константи, фікс у новіших Laravel patches.

### Sessions 2-8 — Phases 1-8 (one JARVIS run)

**Phase 1 — Auth scaffold:**
- Breeze blade install (login/register/forgot/reset/verify-email/profile)
- `users` migration: + role enum(user|admin), avatar_path, banned_at
- User model: MustVerifyEmail, isAdmin(), isBanned() helpers
- AdminMiddleware + EnsureNotBanned middleware
- bootstrap/app.php: middleware aliases, E_DEPRECATED suppression, web group EnsureNotBanned
- config/database.php fix: `Pdo\Mysql::ATTR_SSL_CA` (PHP 8.4+ syntax)
- config/app.php defaults: uk locale, Europe/Kyiv timezone

**Phase 2 — Domain layer:**
- 7 migrations (categories, lots, lot_images, bids, comments, reviews, watchlist) з proper FK order
- 7 Eloquent models з relationships (User, Category, Lot, LotImage, Bid, Comment, Review)
- 7 factories (з states для admin/banned/ended/draft)
- 3 seeders: UserSeeder (admin + 5 test + 15 factory), CategorySeeder (8 parents + 12 children), LotSeeder (50 lots + 111 images + 233 bids + 63 comments)

**Phase 3 — Public browse (anonymous):**
- 7 controllers: Home, Lot (index/show), Category, Search, Faq, Contact
- layouts/public.blade.php + partials/public-nav.blade.php з language switcher
- components/lot-card.blade.php + components/countdown.blade.php (Alpine.js live timer)
- 8 views: home (hero + featured + ending), lots/index (multifilter + sort), lots/show (gallery + bids + threaded comments + similar), categories/show (breadcrumbs), search, faq, contact

**Phase 4 — User actions:**
- 4 Policies: LotPolicy, BidPolicy, CommentPolicy, ReviewPolicy (auto-discovered)
- 4 FormRequests: PlaceBidRequest (runtime min), StoreLotRequest, StoreCommentRequest, StoreReviewRequest
- 4 Controllers: BidController (DB::transaction + lockForUpdate), WatchlistController (AJAX toggle), CommentController, LotManageController (CRUD + Storage upload)
- 13 routes у auth+verified middleware group

**Phase 5 — Admin panel:**
- AuditLog model + audit_logs migration + AuditObserver (created/updated/deleted/restored hooks on Lot, Category, User)
- AuditServiceProvider у bootstrap/providers.php
- 5 admin controllers (Dashboard, LotModeration, UserAdmin, CategoryAdmin, AuditLog)
- 15 admin routes prefix /admin з admin+auth+verified
- layouts/admin.blade.php з Chart.js CDN + dark navbar
- 5 admin views з bulk actions, soft delete + Trash, ban/unban/promote, CSV streaming export

**Phase 6 — Events/Queue/Mail/API:**
- 3 Events: BidPlaced, AuctionEnded, LotCancelled
- 3 Listeners (ShouldQueue): NotifyOutbidUser, SendWinnerNotification, SendSellerNotification
- 3 Notifications (database + mail channels): Outbid, AuctionWon, AuctionEndedSeller
- Event::listen registrations у AppServiceProvider::boot
- Scheduled command `auctions:close`: closes expired active lots inside DB::transaction, fixes winner from highest bid, dispatches AuctionEnded
- routes/console.php: Schedule::command('auctions:close')->everyMinute()->withoutOverlapping()
- Sanctum API: install:api + HasApiTokens trait
- API Controllers: AuthApiController (login/logout, throttle 5/min), LotApiController, BidApiController, WatchlistApiController
- 2 API Resources: LotResource, BidResource
- 9 API endpoints з throttle 60/min

**Phase 7 — i18n + Tests + Docs:**
- lang/uk/messages.php + lang/en/messages.php (nav, lot, search, auth keys)
- SetLocale middleware (cookie-based, ?lang= switcher) + UA/EN nav buttons
- 4 PHPUnit feature tests: BidTest (6 cases), AuctionCloseTest (3), ApiAuthTest (5), PolicyTest (5) = 19 tests, 44 total з Breeze, 103 assertions all green
- docs/routes.md — full route table (50+ routes)
- docs/features-checklist.md — повний mapping на coursework/feature-catalog.md
- database/er-diagram.md — Mermaid ER + constraints + indexes

**Phase 8 — Cross-repo integration:**
- Updated [php-labs/coursework/README.md](../../php-labs/coursework/README.md) — додано секцію "🎬 Робочий приклад: AuctioHub"
- Updated [php-labs/CHANGELOG.md](../../php-labs/CHANGELOG.md) + [.claude/CHANGELOG.md](../../.claude/CHANGELOG.md)
- Updated [.claude/plane/backlog.md](../../.claude/plane/backlog.md) — Done entry + Next Session Context оновлено

8 commits total, ~250 файлів, 19 нових feature-тестів, ~30-40 годин autonomous JARVIS work за одну сесію.

## 2026-05-19

### Session 2 — Quality + UX/UI + a11y + clone-and-run

8 додаткових commits (всього 19):

**docs(readme/student):**
- 3 DB options (SQLite/MySQL native/Docker) для 2-курсу без Docker
- "Що таке artisan?" секція у STUDENT_QUICKSTART
- Force-add `.env`, `database/database.sqlite`, `public/build/` для clone-and-run

**fix(search):** case-insensitive LIKE через `LOWER()` + `mb_strtolower()` — на MySQL працює, на SQLite обмежено (no ICU).

**test(e2e):** full QA pass 20 тестів — anonymous (5), user (7), admin (7), mail (1). 19 pass, 1 SQLite bug (виправлено для MySQL).

**feat(quality):** code-review + UX/UI + a11y:
- User flow map ([docs/user-flow.md](../docs/user-flow.md)) — 9 Mermaid діаграм
- 10 SVG placeholders 1200×900 + Alpine.js lightbox (Esc/Arrows, counter, aspect-ratio 4:3)
- Повний 4-колонковий dark footer
- 6 code-review fixes: CSV N+1, edit a11y labels, observer password redact, cancel status guard, admin search LOWER, Comment eager load
- A11y: skip-link, focus-visible, ARIA, color contrast — 0 axe violations
- [docs/accessibility-audit.md](../docs/accessibility-audit.md) — WCAG 2.2 AA report

**test(final):** post-fix verification — 24 routes (9 public 200, 8 protected 302, 6 admin authed 200), PHPUnit 44/44, fresh screenshots.

**chore(seed):** clean re-seed — 21 users, 50 lots, 234 bids.

### Final state

- 19 commits
- Phase 0-8 + quality pass complete
- WCAG 2.2 AA met (0 axe violations)
- Production-ready demo для курсової
- Clone-and-run для 2-курсу (.env + sqlite + build closed-committed)

### Test accounts (after `migrate:fresh --seed`)

- Admin: `admin@auctiohub.test` / `password`
- Users: `user1@auctiohub.test` ... `user5@auctiohub.test` / `password`
