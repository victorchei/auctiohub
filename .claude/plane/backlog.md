# Backlog

## In Progress

(жодних — усі 8 фаз завершено)

## Done

- [x] Phase 0: Repo init (composer scaffold + .claude bootstrap + docker-compose) — 2026-05-18
- [x] Phase 1: Breeze auth + role enum + AdminMiddleware + EnsureNotBanned — 2026-05-18
- [x] Phase 2: 7 migrations + 7 models + factories + seeders (21 users, 20 categories, 50 lots, 233 bids) — 2026-05-18
- [x] Phase 3: Public browse (7 controllers, layouts/public, lot-card + countdown) — 2026-05-18
- [x] Phase 4: User actions (bid transaction, watchlist toggle, comments, lot CRUD + upload, 4 policies, 4 FormRequests) — 2026-05-18
- [x] Phase 5: Admin panel (dashboard + Chart.js, 5 controllers, 15 routes, AuditObserver) — 2026-05-18
- [x] Phase 6: Events/Queue/Mail/API (3 events, 3 listeners, 3 notifications, scheduled auctions:close, Sanctum API) — 2026-05-18
- [x] Phase 7: i18n + 19 PHPUnit tests + docs (routes, features-checklist, ER diagram) — 2026-05-18
- [x] Phase 8: Cross-repo integration з coursework docs — 2026-05-18

## TODO (для майбутніх покращень — НЕ блокери)

- [ ] WebSocket / Laravel Reverb real-time bid updates (наразі AJAX polling)
- [ ] Stripe sandbox integration для "оплати виграного лоту" (🔴 з § 15)
- [ ] Deploy на Railway / Heroku (§ 19 «Максимальна версія»)
- [ ] Screenshots у docs/screenshots/ (через Chrome DevTools MCP — потребує chrome-debug)
- [ ] Конвертація PHPUnit → Pest (плановано, але PHPUnit функціонально рівноцінний)
- [ ] PHP 8.4 binary fallback (PHP 8.5 кидає deprecation warnings про PDO константи)
- [ ] Lighthouse audit + accessibility WCAG AA
- [ ] Email верифікація з реальним Mailtrap (зараз log driver)
