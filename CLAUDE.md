# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository Layout

This is a Docker-based Laravel application. The repository root holds Docker orchestration; the actual application lives in `src/`.

- `src/` — Laravel 12 + PHP 8.2 application (Composer + npm)
- `docker/` — Apache + PHP config mounted into containers
- `docker-compose.yml` — services: `app` (php-fpm), `webserver` (Apache, port 8080), `mysql` (port 3305 → 3306), `redis` (port 6378 → 6379), `mongodb` (port 27016 → 27017). Vite dev server runs in `app` on port 9600.
- `Makefile` — wraps every `docker-compose exec app …` invocation; almost everything is run through it
- `docs/` — repo-level docs (e.g., toast/modal usage)

Note `.gitignore` excludes `src/vendor/`, `src/node_modules/`, `src/.env`. Almost all real work happens under `src/`.

## Commands

All commands assume containers are up (`make up`). The Makefile's catch-all `%: @:` target lets you pass extra arguments through (e.g. `make artisan migrate:fresh`).

### Container lifecycle
```bash
make up              # docker-compose up -d
make down
make rebuild         # down + build --no-cache + up
make shell           # bash into the app container
make status          # docker-compose ps
make logs
```

### Laravel / Composer
```bash
make artisan <cmd>           # e.g. make artisan migrate, make artisan tinker
make composer <cmd>          # e.g. make composer require vendor/pkg
make migrate                 # artisan migrate
make fresh                   # artisan migrate:fresh --seed
make clear                   # cache:clear + config:clear + route:clear + view:clear
```

### Frontend (Vite + Vue 3)
```bash
make dev             # npm run dev — Vite on 0.0.0.0:9600 (strictPort)
make build-assets    # npm run build
make npm <cmd>       # arbitrary npm command inside container
make npx <cmd>
```

### Tests & lint
```bash
make test            # php artisan test (PHPUnit 11)
make pint            # ./vendor/bin/pint — Laravel code style fixer
```

Run a single test: `make artisan test --filter=TestClassName` or `make artisan test tests/Feature/Path/SomeTest.php`. The test suite uses MySQL (`laravel_test` db on the `mysql` host) per `src/phpunit.xml`, not SQLite — the test DB must exist in the container.

### DB / cache shells
```bash
make db-shell        # mysql root shell
make redis-cli
```

## Application Architecture

Laravel 12 server-rendered Blade app with **per-page Vue 3 islands** mounted into Blade. Not a SPA.

### Domain
Multi-role tourism marketplace: travelers book products (tours) from vendors, with guides handling check-in/operation, and admins moderating. Core domains: products (with translations, prices, schedules, images, categories, regions), bookings, reviews, messages, blog, wishlist, notifications.

Roles (`App\Enums\UserRole`): `admin`, `vendor`, `guide`, `traveler`. Enforced via `spatie/laravel-permission` and the `role:` middleware alias (see `bootstrap/app.php`).

### Routing structure (`src/routes/`)
- `web.php` — Blade-rendered pages. Public traveler-facing routes are **locale-prefixed** under `/{locale}` where locale ∈ `ko|en|zh|ja` (constrained to `App\Enums\Language::values()` at runtime — currently only `ko` and `en` are enabled). Role-scoped groups: `/vendor/*` (`role:vendor,admin`), `/admin/*` (`role:admin`), `/guide/*` (`role:guide,admin`).
- `api.php` — JSON endpoints under `/api`. Uses `auth:sanctum` + `user.active`. Sanctum stateful middleware is prepended to the API group, so the SPA islands authenticate via session cookies, not bearer tokens. Same role-scoped groups under `/api/vendor`, `/api/guide`, `/api/admin`.

Controllers mirror the route hierarchy: `App\Http\Controllers\{Admin,Vendor,Guide,Traveler,Auth,Api\*}`. Many entities have **both** a web controller (returns a view) and an `Api\*` controller (returns JSON) — keep them in sync when changing behavior.

### Middleware aliases (`bootstrap/app.php`)
- `role` → `CheckRole` — accepts comma-separated role list: `role:vendor,admin`
- `user.active` → `EnsureUserIsActive` — blocks deactivated/blocked accounts
- `locale` → `SetLocale` — applied to all web routes AND inside the locale-prefixed group

### Models & i18n
- Eloquent models in `App\Models`. SoftDeletes on `User` (and others). User uses Sanctum's `HasApiTokens` + Spatie's `HasRoles`.
- Translatable entities use sidecar tables: `Product` ↔ `ProductTranslation`, `ProductCategory` ↔ `ProductCategoryTranslation`, `Region` ↔ `RegionTranslation`. When adding translatable fields, modify the translation migration and table — not the parent.
- Blade translations live in `resources/lang/{ko,en,zh,ja}/*.php`. The locale prefix sets `app()->setLocale()` via `SetLocale` middleware.

### Service layer
Business logic lives in `App\Services\*Service` (AuthService, BookingService, ProductService, ReviewService, InventoryService, MessageService, NotificationService, BlogService, ImageService). Controllers should be thin — delegate to services. `App\Repositories` and `App\DTOs` exist but are mostly empty scaffolding from the Makefile's `setup-dirs` target.

### Frontend integration
- `src/vite.config.js` — Vite entries: `resources/css/app.css`, `resources/js/app.js`, `resources/js/pages/products-index.js`. Alias `@` → `/resources/js`, and `vue` resolves to `vue.esm-bundler.js` (template-compilation enabled). Manual chunks for `vendor`, `fullcalendar`, `lodash`.
- Blade layouts under `resources/views/components/layouts` (used as `<x-layouts.app>`). Pages extend these.
- Vue mounting pattern (`resources/js/app.js`):
  - One Pinia instance shared across all islands
  - Global helpers exposed on `window`: `window.api` (axios wrapper from `services/api.js`), `window.$toast`, `window.$modal` (backed by Pinia stores in `resources/js/stores/`)
  - `window.createVueApp(component, selector, props)` helper for ad-hoc mounting from Blade
  - Auto-mounting via `DOMContentLoaded`: e.g. `#booking-form-container` and `[data-booking-actions]` elements get hydrated automatically, reading props from `data-*` attributes
- `resources/js/services/api.js` — central axios instance with `baseURL: '/api'`, `withCredentials: true`, CSRF auto-injection from `meta[name="csrf-token"]`, and global 401 → `/login`, 419 → reload handling. All API endpoints are organized as `api.{domain}.{action}` (e.g. `api.products.list`, `api.wishlist.toggle`). Add new endpoints here rather than calling axios directly from components.

### Auth flow
Session-based for both web and API (Sanctum stateful). Social login via `laravel/socialite` at `/auth/{provider}/redirect` and `/callback`. Password reset is accessible to both guests and authenticated users.

## Conventions

- **Toast & Modal**: use `window.$toast.{success,error,warning,info}(message, duration?)` and `window.$modal.{confirm,confirmDelete,confirmSuccess,alert}(options)`. See `docs/toast-modal-usage.md`. Don't roll your own notification UI.
- **Adding routes**: if a feature needs both a Blade page and a JSON endpoint (most do), add the web route in `web.php` and the JSON route in `api.php`, and add the API endpoint to `resources/js/services/api.js`.
- **Adding API endpoints**: register the route in `api.php` under the right role-prefix group, then extend `api` in `resources/js/services/api.js`.
- **Translatable content**: add columns to the `*_translations` table, not the parent table. The Region migration history (`0001_05_124327` → `0001_05_134132`) shows the pattern of migrating an existing column off the parent and onto a translation/sidecar table.
- **Locale URLs**: don't hardcode locale segments. Use the existing `Route::prefix('{locale}')->where(['locale' => 'ko|en|zh|ja'])` group. Switching locale goes through `/locale/{locale}` which preserves and rewrites the current path.
- **Tests use MySQL**, not SQLite — `phpunit.xml` points to `laravel_test` on the `mysql` host. Don't write tests that assume SQLite-specific behavior.
- **Don't sync from this repo to upstream**: the `.claude/rules/github-operations.md` rule blocks PRs/issues against `automazeio/ccpm`. Only ever sync with this project's own origin.

## CCPM (Claude Code PM)

`.claude/` contains a CCPM installation (PRDs, epics, issue commands under `.claude/commands/pm/`, rules under `.claude/rules/`). When running PM commands (`/pm:*`), follow the rule files — especially `path-standards.md` (no absolute paths in synced content), `datetime.md` (always real UTC ISO 8601 timestamps), and `strip-frontmatter.md` (strip YAML before posting to GitHub).
