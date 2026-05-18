# Маршрути AuctioHub

Згенеровано: `php artisan route:list --except-vendor`.

## Public (anonymous + authenticated)

| Method | URI | Name | Controller@Action |
|---|---|---|---|
| GET | `/` | home | HomeController@index |
| GET | `/lots` | lots.index | LotController@index |
| GET | `/lots/{slug}` | lots.show | LotController@show |
| GET | `/categories/{slug}` | categories.show | CategoryController@show |
| GET | `/search` | search | SearchController@index |
| GET | `/faq` | faq | FaqController@index |
| GET | `/contact` | contact.show | ContactController@show |
| POST | `/contact` | contact.send | ContactController@send |

## Auth (Breeze)

| Method | URI | Name |
|---|---|---|
| GET | `/login` | login |
| POST | `/login` | — |
| GET | `/register` | register |
| POST | `/register` | — |
| POST | `/logout` | logout |
| GET | `/forgot-password` | password.request |
| POST | `/forgot-password` | password.email |
| GET | `/reset-password/{token}` | password.reset |
| POST | `/reset-password` | password.store |
| GET | `/verify-email` | verification.notice |
| GET | `/verify-email/{id}/{hash}` | verification.verify |
| POST | `/email/verification-notification` | verification.send |
| GET | `/confirm-password` | password.confirm |
| POST | `/confirm-password` | — |
| PUT | `/password` | password.update |

## User Actions (auth + verified)

| Method | URI | Name | Controller@Action |
|---|---|---|---|
| GET | `/dashboard` | dashboard | (closure) |
| GET/PATCH/DELETE | `/profile` | profile.{edit,update,destroy} | ProfileController |
| GET | `/lots-manage/create` | lots.create | LotManageController@create |
| POST | `/lots-manage` | lots.store | LotManageController@store |
| GET | `/lots-manage/{slug}/edit` | lots.edit | LotManageController@edit |
| PUT | `/lots-manage/{slug}` | lots.update | LotManageController@update |
| DELETE | `/lots-manage/{slug}` | lots.destroy | LotManageController@destroy |
| POST | `/lots/{slug}/bids` | bids.store | BidController@store |
| GET | `/watchlist` | watchlist.index | WatchlistController@index |
| POST | `/watchlist/{slug}/toggle` | watchlist.toggle | WatchlistController@toggle |
| POST | `/lots/{slug}/comments` | comments.store | CommentController@store |
| DELETE | `/comments/{id}` | comments.destroy | CommentController@destroy |

## Admin (auth + verified + admin)

| Method | URI | Name | Controller@Action |
|---|---|---|---|
| GET | `/admin/dashboard` | admin.dashboard | Admin\DashboardController@index |
| GET | `/admin/lots` | admin.lots.index | Admin\LotModerationController@index |
| POST | `/admin/lots/{lot}/cancel` | admin.lots.cancel | LotModerationController@cancel |
| DELETE | `/admin/lots/{lot}` | admin.lots.destroy | LotModerationController@destroy |
| POST | `/admin/lots/{id}/restore` | admin.lots.restore | LotModerationController@restore |
| POST | `/admin/lots/bulk-delete` | admin.lots.bulkDelete | LotModerationController@bulkDelete |
| GET | `/admin/lots/export` | admin.lots.export | LotModerationController@exportCsv |
| GET | `/admin/users` | admin.users.index | Admin\UserAdminController@index |
| POST | `/admin/users/{user}/ban` | admin.users.ban | UserAdminController@ban |
| POST | `/admin/users/{user}/unban` | admin.users.unban | UserAdminController@unban |
| POST | `/admin/users/{user}/promote` | admin.users.promote | UserAdminController@promote |
| GET | `/admin/categories` | admin.categories.index | Admin\CategoryAdminController@index |
| POST | `/admin/categories` | admin.categories.store | CategoryAdminController@store |
| DELETE | `/admin/categories/{cat}` | admin.categories.destroy | CategoryAdminController@destroy |
| GET | `/admin/audit-log` | admin.audit.index | Admin\AuditLogController@index |

## API (Sanctum, prefix `/api`)

| Method | URI | Auth | Rate limit | Controller@Action |
|---|---|---|---|---|
| POST | `/api/login` | — | 5/min | Api\AuthApiController@login |
| POST | `/api/logout` | sanctum | 60/min | Api\AuthApiController@logout |
| GET | `/api/user` | sanctum | 60/min | (closure) |
| GET | `/api/lots` | — | 60/min | Api\LotApiController@index |
| GET | `/api/lots/{slug}` | — | 60/min | Api\LotApiController@show |
| GET | `/api/lots/{slug}/bids` | — | 60/min | Api\LotApiController@bids |
| POST | `/api/lots/{slug}/bids` | sanctum | 60/min | Api\BidApiController@store |
| GET | `/api/me/watchlist` | sanctum | 60/min | Api\WatchlistApiController@index |
| POST | `/api/lots/{slug}/watchlist` | sanctum | 60/min | Api\WatchlistApiController@toggle |

## Middleware aliases (bootstrap/app.php)

- `admin` → EnsureAdmin (role === 'admin', else 403)
- `not_banned` → EnsureNotBanned (banned_at != null → logout + redirect to /login)

Web group appends: `EnsureNotBanned`, `SetLocale`.

## Scheduled (routes/console.php)

| Schedule | Command |
|---|---|
| Every minute, without overlapping | `auctions:close` — closes expired active lots, fixes winner, dispatches AuctionEnded |
