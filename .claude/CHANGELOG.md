# Changelog

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
