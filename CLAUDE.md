# LGU Hub — Past Papers & Online Lectures Platform

Custom PHP 8.3 MVC platform for Lahore Garrison University — past papers by
Department → Degree → Exam Type, online lectures, paid class bookings, paper
submissions, newsletter. **No framework** (no Laravel) — everything below
(router, DI container, ORM-lite, view engine, CSRF, validator) is hand-rolled.

A full architecture graph of this repo lives in `graphify-out/` (built with
`/graphify`). For "how does X connect to Y" or "what calls Z" questions, run
`graphify query "<question>"` before grepping by hand — see
`graphify-out/GRAPH_REPORT.md` for god nodes, communities, and surprising
cross-file connections. Re-run `/graphify --update` after a large batch of
changes (like the one this file describes) to keep it current.

## Stack

PHP 8.3 (strict_types everywhere) · PDO/MySQL 8 · Composer (PHPMailer,
vlucas/phpdotenv, ezyang/htmlpurifier, monolog) · vanilla JS/CSS frontend,
Poppins typeface · Docker (Apache + MySQL + phpMyAdmin), app container
bind-mounts the repo rather than baking `vendor/` into the image.

## Brand identity

Pulled from LGU's own live CSS at `admissions.lgu.edu.pk`, not invented:
- **Primary:** Garrison green `#0d3e02` · **Accent:** gold `#fdbf1e`
- **Font:** Poppins (loaded via Google Fonts in `layouts/app.php`/`auth.php`)
- **Assets:** `public/assets/images/brand/` — `lgu-crest.png` (official
  seal), `campus-hero.jpg` (real drone photo of the DHA Phase VI campus),
  `student-1.jpg`/`student-2.jpg` (real admissions-marketing portraits, used
  on alumni testimonials), `lgu-crest-white.jpg` (dark-background variant).
  All tokens live in `public/assets/css/variables.css` — change the palette
  there, not by hunting hex codes through templates.
- Positioning: the footer/about copy calls this "a student resource for
  LGU," not a hard "unofficial, not affiliated" disclaimer — that's a
  deliberate call given the site uses LGU's real crest; revisit if this
  platform's relationship with the university's admin changes.

## Architecture

```
Controller → Service → Repository → Database (PDO) → MySQL
```

- **Controllers** (`app/controllers/`, `app/controllers/Admin/`) — thin.
  Every controller extends `App\Core\Controller` (`view`, `session`, `csrf`
  always injected; `render()` also auto-injects `seo` and, for the `app`
  layout, `navDepartments`/`siteSettings` — see below).
- **Services** (`app/services/`) — validation + orchestration, and the
  layer responsible for **whitelisting** which fields reach a repository.
  `DepartmentService::create()` is the reference pattern; every service now
  follows it (see Known Issues history below — this used to not be true).
- **Repositories** (`app/repositories/`) — thin PDO wrappers, extend
  `App\Core\Repository` for generic `all/find/create/update/delete`.
  `Repository::create()/update()` also defensively reject any column name
  that isn't `[a-zA-Z_][a-zA-Z0-9_]*` as a second layer under the Service
  whitelist.
- **`App\Core\App::registerCoreServices()`** — the entire DI graph is wired
  by hand in one method (`app/core/App.php`). `Container::singleton()` is
  **lazy** (stores the factory, resolves on first `make()`) so registering
  ~40 controllers/services/repositories at boot doesn't construct all of
  them on every request — new modules should still follow the existing
  per-module block pattern, they just won't pay for it until used.
- **CSRF**: every mutating controller action calls
  `$this->verifyCsrf()` (defined once on the base `Controller`, reads
  `_token` from `$_POST`/`$_GET`) before doing anything. `SubDepartmentController`
  used to be the only one that did this — it's now the pattern everyone
  follows, not the exception. Any new write action must call it too.
- **SEO**: `Controller::render()` merges in a `SeoService` instance (pass
  your own via `'seo' => (new SeoService())->description(...)->jsonLd(...)`
  in the data array, or it falls back to sane defaults). `layouts/app.php`
  reads `$seo->data()` for the full `<head>` — meta description, canonical,
  OG/Twitter tags, JSON-LD, and a GA4 snippet gated on
  `site_settings.ga_measurement_id` being non-empty. Breadcrumbs go through
  `app/views/components/breadcrumb.php` (pass `$crumbs` before requiring
  it) — it renders both the visible nav and matching `BreadcrumbList`
  JSON-LD from one input.
- **404s** are real (`Controller::notFound()` — used when a department/
  degree/paper slug doesn't resolve; `Dispatcher::dispatch()` — used when no
  route matches at all), not the old 302-to-homepage soft-404 pattern.

### Adding a new admin CRUD module

Follow the `$modules` loop pattern in `App::registerCoreServices()` and the
route-group pattern in `routes/web.php`. Copy `SubDepartmentController` +
`DepartmentService` as the template: every mutating action starts with
`if (!$this->verifyCsrf()) { redirect; return; }`, and every service method
builds an explicit whitelisted array before calling the repository — never
pass `$this->request->all()` straight through.

## Environment

- `.env` (gitignored) drives `App\Core\Config::get()`. Now includes
  `DB_ROOT_PASSWORD` (docker-compose reads DB creds from `.env`, no more
  hardcoded credentials in `docker-compose.yml` — rotate the placeholder
  root password in `.env` before any real deployment).
- Docker: `docker-compose up -d`, app on `:8080`, phpMyAdmin on `:8081`.
  The `app` service mounts the whole repo, so `vendor/` must already exist
  on the host (`composer install` locally first) — the container never
  runs Composer itself.
- Migrations: `php database/migrate.php` (also seeds the one default admin,
  `admin@lgu.edu.pk` / `Admin123!`, if `admins` is empty — `database/seeders/AdminSeeder.php`
  is just a consistent fallback for `seed.php`, not a second account).
  Content: `php database/seed.php` — idempotent, seeds all 15 real LGU
  departments/degrees/papers; safe to re-run.

## Known Issues — fixed in this pass (kept for history/context)

All 9 originally-found issues, plus a few more found while fixing them,
were resolved and verified against the running app (CSRF-less requests
rejected with no DB write, RoleMiddleware blocking a real editor session
from `/admin/users`, mass-assignment payload dropped, etc.):

1. ~~`composer.json`/`.gitignore` merge-conflict markers~~ — resolved.
2. ~~CSRF decorative everywhere except one controller~~ — `verifyCsrf()` on
   every mutating action, all controllers.
3. ~~Mass-assignment → privilege escalation via Admin Users~~ — every
   service whitelists fields explicitly; `Repository` also validates column
   names defensively.
4. ~~`RoleMiddleware` wired to zero routes~~ — protects `/admin/users`.
   Ceiling: it only supports one hardcoded role gate (`super_admin`) since
   `Route`/`Dispatcher` carry no per-route middleware parameters yet — see
   the comment on `RoleMiddleware::handle()` for the upgrade path if a
   second restricted role is needed later.
5. ~~Two parallel login flows~~ — `AuthController` deleted; `/login` and
   `/logout` now alias to `LoginController`.
6. ~~Duplicate CSRF implementations~~ — `App\Core\Security` deleted (was
   dead/unused); `App\Core\Csrf` is the one implementation.
7. ~~`Container::singleton()` eager resolution~~ — now lazy.
8. ~~`FileUploadService` ignoring `config/upload.php`~~ — now reads
   `max_size` and enforces it before `move_uploaded_file()`; extension is
   mapped from the verified MIME type, not the client-supplied filename.
9. ~~Hardcoded Docker credentials~~ — read from `.env` now.
10. ~~`Repository::execute()`/`Database::execute()` treating `rowCount() > 0`
    as success~~ — fixed to report the PDO statement's own result.

**Also found and fixed while doing the above:**
- `SubDepartmentRepository::create()/update()` never wrote `slug`
  (`NOT NULL UNIQUE`) — any admin-created sub-department failed. Fixed.
- `HomeController::submitPaper()` called
  `FileUploadService::upload()`, which didn't exist — the public paper
  submission form was a guaranteed fatal error. Added the alias method.
- `app/config/session.php`, `app/config/database.php`, `routes/admin.php`
  were empty/dead — deleted.
- Two divergent seeded admin accounts (`migrate.php`'s `admin@lgu.edu.pk`
  vs `seed.php`'s fallback `admin@lgu.local`) — `AdminSeeder.php` now
  matches `migrate.php`'s credentials so the fallback path can't create a
  second, different account.

## Remaining, not blocking

- No rate limiting beyond the 3-attempt admin lockout, no CSP/security
  headers, no 2FA (README already called these "planned").
- No `session_set_cookie_params()` before `session_start()` in
  `app/bootstrap/app.php` — fine on `http://localhost`, set `Secure`/
  `SameSite` explicitly before a real HTTPS deployment.
- `public/robots.txt` is a static file with a hardcoded `Sitemap:` URL
  (can't read `.env`) — update it to the real domain before launch.
- SEO: `rel=prev`/`rel=next` on `/lectures` pagination and `FAQPage`/
  `CollegeOrUniversity` schema on department pages weren't added (canonical
  tags + `WebSite`/`LearningResource`/`BreadcrumbList` schema were) — low
  priority, easy to add later via the same `SeoService` methods.
- No `courses` table — "course" pages are `papers` grouped by
  `subject_name` within a sub-department. Fine at current scale; revisit
  only if course-level admin metadata (credit hours, instructor, etc.)
  becomes a real requirement.
- Content depth is tiered on purpose: Computer Science + Software
  Engineering have deep paper coverage (multiple subjects × 2 exam types ×
  2 sessions), every other department has a real but lighter starter set.
  Expand through the (now security-fixed) admin panel — `database/seed.php`
  documents the `depth` tiers if you want to re-run/extend it.
- `video_categories` are seeded per pilot department; no video rows are
  seeded (no fabricated YouTube IDs) — add real lecture videos via
  `/admin/videos`.

## Conventions already followed correctly (keep doing these)

- PDO prepared statements everywhere for *values*.
- `password_hash()`/`password_verify()` for admin auth.
- Views (`app/views/**/*.php`) are plain PHP with no auto-escaping — call
  `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` on every dynamic string;
  there is no `e()` helper.
- `declare(strict_types=1)` at the top of every PHP file.
- Real LGU brand tokens in `variables.css` — don't hardcode colors/hexes in
  templates; use the CSS custom properties.
