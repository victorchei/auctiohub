# Backlog

## In Progress

(жодних — проект production-ready як демо)

## Done

- [x] Phase 0: Repo init — 2026-05-18
- [x] Phase 1: Breeze auth + role enum + middleware — 2026-05-18
- [x] Phase 2: 7 migrations + models + factories + seeders — 2026-05-18
- [x] Phase 3: Public browse (anonymous) — 2026-05-18
- [x] Phase 4: User actions (bid, watchlist, comments, lot CRUD) — 2026-05-18
- [x] Phase 5: Admin panel + audit log — 2026-05-18
- [x] Phase 6: Events/Queue/Mail/Sanctum API — 2026-05-18
- [x] Phase 7: i18n + Pest tests + docs — 2026-05-18
- [x] Phase 8: Cross-repo integration з coursework — 2026-05-18
- [x] Visual verification: 11 screenshots — 2026-05-18
- [x] E2E QA: 20 тестів anonymous+user+admin+mail — 2026-05-18
- [x] BUG-1 fix: case-insensitive search via LOWER() — 2026-05-18
- [x] 3 DB options + STUDENT_QUICKSTART — 2026-05-18
- [x] artisan explanation у STUDENT_QUICKSTART — 2026-05-19
- [x] User flow map (9 Mermaid диаграм) — 2026-05-19
- [x] Working gallery + Alpine lightbox + 10 SVG — 2026-05-19
- [x] Full 4-column footer — 2026-05-19
- [x] Code-review fixes (6 critical/high) — 2026-05-19
- [x] Accessibility WCAG 2.2 AA — 0 axe violations — 2026-05-19
- [x] Clean re-seed для session close — 2026-05-19

## TODO (optional — non-blocking)

**Medium (з code-review):**
- [ ] `LotManageController@update` — image upload (note в edit.blade.php)
- [ ] Slug collision на create — `time()` → `Str::random(6)`
- [ ] StoreLotRequest — explicit `mimes:jpg,jpeg,png,gif,webp`
- [ ] `Lot::scopeActive` — `where('ends_at', '>', now())`
- [ ] `CloseExpiredAuctions` — eager-load highest bids
- [ ] Chart.js conditional load через `@push('scripts')`

**Low:**
- [ ] WebSocket / Laravel Reverb real-time bid updates
- [ ] Stripe sandbox для "оплати виграного лоту"
- [ ] Deploy на Railway / Heroku
- [ ] PHPUnit → Pest convertion
- [ ] PHP 8.4 fallback (8.5 PDO deprecations)
- [ ] Lighthouse full audit (a11y вже покрив axe-core)
- [ ] Mailtrap SMTP integration
- [ ] `x-trap` Alpine plugin для focus trap у lightbox
- [ ] Повний переклад UI у `lang/uk/messages.php`

## Next Session Context

### S3 — наступна сесія (якщо буде)

> **Last completed (S2 — 2026-05-19):** Quality + UX/UI + a11y comprehensive pass. 8 commits поверх Phase 0-8 (19 total).
>
> **Continue with (на вибір):**
> 1. Medium-priority TODOs з code-review — 2-3 години
> 2. Інший проект / нова курсова — AuctioHub самодостатній demo
> 3. WebSocket real-time bidding (Laravel Reverb) — +6-10 годин
> 4. Stripe sandbox — +6-10 годин
>
> **Key docs:**
> - [STUDENT_QUICKSTART.md](../../STUDENT_QUICKSTART.md)
> - [docs/user-flow.md](../../docs/user-flow.md)
> - [docs/accessibility-audit.md](../../docs/accessibility-audit.md)
> - [docs/screenshots/e2e/README.md](../../docs/screenshots/e2e/README.md)
>
> **Active PRs:** жодних — local-only.
>
> **Blockers/risks:**
> - SQLite case-insensitive search обмежено (потребує MySQL)
> - PHP 8.5 deprecation warnings у vendor (не блокер)
>
> **Skills для preload:** `laravel-specialist`, `eloquent-best-practices`, `web-frontend-quality`, `secure-code-guardian`, `code-review`
