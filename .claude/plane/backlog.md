# Backlog

## In Progress

- [x] Phase 0: Repo init (composer scaffold + .claude bootstrap + docker-compose)
- [ ] Phase 1: Breeze auth + role enum + AdminMiddleware
- [ ] Phase 2: Domain migrations + models + factories + seeders
- [ ] Phase 3: Public browse (anonymous)
- [ ] Phase 4: User actions (bid, watchlist, comments)
- [ ] Phase 5: Admin panel
- [ ] Phase 6: Events/Queue/Mail/API
- [ ] Phase 7: i18n + Pest tests + docs + screenshots
- [ ] Phase 8: Cross-repo integration з coursework docs

## Done

(порожньо до завершення Phase 0)

## Next Session Context

### S1 — 2026-05-18
- **Last completed:** Phase 0 (Laravel scaffold + bootstrap)
- **Continue with:** Phase 1 — `composer require laravel/breeze --dev && php artisan breeze:install blade`
- **Key docs:** [.claude/CLAUDE.md](../CLAUDE.md), `../../.claude/plane/async-leaping-wilkinson.md` (master plan)
- **Blockers/risks:** PHP 8.5 deprecation warnings (PDO константи) — потрібно або toggle `error_reporting`, або wait for Laravel patch
