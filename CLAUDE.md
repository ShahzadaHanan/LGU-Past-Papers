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
- Positioning: `app/views/partials/footer.php` now carries a hard
  disclaimer — "Independent student resource — not an official Lahore
  Garrison University platform." This note used to say the opposite (copy
  calling it just "a student resource for LGU," deliberately avoiding a
  hard disclaimer); that changed at some point and this doc had gone
  stale — `/graphify --update`'s Surprising Connections caught the drift
  by diffing CLAUDE.md's claim against the real rendered footer. If you
  change the disclaimer wording again, update this note in the same commit.

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
pass `$this->request->all()` straight through. `SubjectController` is a
second, more recent reference for a module that needs an *extra* dependency
beyond its own service (it also takes `SubDepartmentService`, for the Degree
dropdown/filter) — wire it as its own standalone `$this->container->singleton()`
block right after the module it depends on, same as `SubDepartmentController`
does for `DepartmentService`, rather than trying to force it into the generic
`$modules` loop.

### Department → Degree → Subject catalogue (roadmap-driven)

Three levels: `departments` → `sub_departments` (labelled "Degrees" in the
admin UI and nav — the underlying table/class/route names still say
`sub_department`, only the user-facing copy changed) → `subjects` (new
table, migration `011_create_subjects_table.sql`). Subjects are a separate,
admin-managed course catalogue per degree (`/admin/subjects`, filterable by
Degree) — independent of `papers.subject_name`, which stays free-text on
each paper and is not migrated to a `subjects_id` FK.

The real content for this comes from `HomeController::ROADMAPS` /
`SEMESTER_CURRICULA` (the same constants that power the public `/roadmaps`
pages, transcribed from lgu.edu.pk's own roadmap pages — see the doc-comment
on `ROADMAPS`). `database/seed.php` pulls both constants via
`ReflectionClass` rather than re-typing the data a second time, then:
1. Upserts every roadmap-listed degree by slug into `sub_departments`
   (renaming placeholder/generic names to the real roadmap title where an
   old slug doesn't match, inserting missing ones, always by `department_id`
   resolved from a real department row) — see the `$degreeSlugRenames` and
   `$roadmapDeptSlugMap` arrays in `seed.php` for the exact reconciliation
   table, including the one department-slug mismatch tied to the known
   duplicate "Department of Computer Science" row (see below).
2. Flattens each degree's `SEMESTER_CURRICULA` entry (deduped by course
   title) into real `subjects` rows.
Both steps are idempotent (safe to re-run) and only touch degrees the real
roadmap data actually covers — departments/degrees outside that dataset
(e.g. Islamic Sciences, Physics, Urdu) are left untouched.

**Known duplicate department, still unresolved:** two `departments` rows
exist for Computer Science — `id=2` (`department-of-computer-science`,
active, holds all the real content) and `id=17` (`computer-science`,
deactivated, created by an earlier `seed.php` slug mismatch). `seed.php`'s
own department-seeding loop still targets the `computer-science` slug (so
it silently writes to the inactive duplicate on every run) — the roadmap
reconciliation above works around this with an explicit slug remap rather
than fixing the root cause. Delete/merge/restore is still the user's call.

### Public visitor accounts (separate from admin auth)

`users` table + `App\Models\User` + `UserAuthService` + `UserAuthController`
— a second, independent auth system for site visitors (register/login),
**not** connected to `admins`/`AuthService`/the admin panel in any way:
different table, different session key (`site_user` vs `auth`), different
routes (`/account/login`, `/account/register`, `/account/logout`, all under
`App\Controllers`, not `App\Controllers\Admin`). `Controller::render()`
injects the logged-in visitor (if any) as `$currentSiteUser` for the `app`
layout only, the same way it already injects `navDepartments`/`siteSettings`.

The login/signup UI lives inline in `app/views/partials/header.php` as a
hover/focus dropdown (reusing the same `.dropdown`/`.dropdown-menu` CSS the
Departments nav already used) with Login/Sign Up tabs
(`initHeaderAuth()`/`[data-auth-tab]` in `site.js`) rather than a page
navigation — both forms carry a hidden `redirect_to` field
(`UserAuthController::redirectTarget()`, open-redirect-guarded to same-origin
paths only) so submitting from any page lands back on that same page, with
the result surfaced via a new global flash-message block in
`layouts/app.php` (right after the header) since the dialog itself has no
persistent open/closed state across a redirect. The mobile slide-out nav
gets a plain `/account/login` link instead — the dropdown depends on hover,
which doesn't exist on touch, matching how the Departments dropdown is
already mobile-exempt via the separate `<details>`-based mobile nav.

Registration collects name/email/phone/password+confirm/optional profile
picture (`FileUploadService::image($file, 'user_avatars')`). Google Sign-In
(Identity Services button + `POST /account/google`, verified via Google's
`tokeninfo` endpoint — no OAuth SDK dependency) is fully wired but only
renders when `GOOGLE_CLIENT_ID` is set in `.env` (blank by default — this
repo has no real Google OAuth client, see `.env.example` for the console
setup steps). A Google-created account has `password_hash = NULL` and
`auth_provider = 'google'`; `UserAuthService::login()` guards that case
before calling `password_verify()`.

Admin visibility: `/admin/site-users` (read-only + delete, "Registered
Users" in the sidebar — deliberately named to not collide with `/admin/users`,
which manages *admin* accounts, unrelated and pre-existing) shows every
visitor's avatar, phone, and sign-up method (Email vs Google). Adding a new
admin/staff account (as opposed to a visitor account) was already a working
feature before this — `/admin/users/create`, `AdminUserController`, gated by
`RoleMiddleware` — nothing new needed there.

### Admin dashboard

`DashboardController` — expanded stat set (departments, degrees, subjects,
papers, videos, bookings, registered users, admins, newsletter subscribers,
pending submissions, alumni) plus four charts, all plain CSS (bar rows,
column bars, `conic-gradient()` donuts in `admin.css`'s `.charts-grid`
block) — no charting library. If a real chart library ever becomes
worthwhile, replace incrementally per-chart; the CSS approach was a
deliberate "no new dependency" call for what's a handful of simple
aggregates, not a general recommendation against ever adding one.

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
- `GOOGLE_CLIENT_ID` (blank by default) — see "Public visitor accounts"
  above. Setup steps are in `.env.example`.

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

**Found and fixed in later passes (content/admin-panel work, not the
original security pass):**
- `app/views/admin/departments/edit.php` only rendered a `name` input —
  `DepartmentService::update()` unconditionally writes
  `description`/`meta_title`/`meta_description`/`display_order`/`is_active`
  from whatever was submitted, so every edit silently wiped those fields
  back to null/0. Fixed by adding the missing fields (all backend support
  already existed); this had been quietly destroying department content on
  every save.
- `App\Models\SiteSetting`'s constructor had `?int $id = null` before the
  required `string $setting_key` — a deprecation warning on every
  instantiation (PHP 8 implicitly treats a required param after an
  optional one as required, which works but warns). Reordered.
- `HomeController::paper()` incremented `views_count` in the DB but kept
  serving the pre-increment value from the already-fetched `$paper` array
  — the page displayed a stale count until the next visit. Fixed by
  bumping the in-memory value too.
- Admin `/admin/papers/create` and `/admin/papers/{id}/edit` fatal-errored
  (`Cannot use object of type SubDepartment as array`) — the degree
  `<select>` used `$sd['id']` array syntax against objects returned by
  `SubDepartmentService::getAllSubDepartments()`. Fixed to `$sd->id`.

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
- Google Sign-In (public visitor login) is fully wired but inert until a
  real `GOOGLE_CLIENT_ID` is set — this repo has no Google Cloud OAuth
  client configured. See "Public visitor accounts" above.
- `livepaper.html` at the project root is an untracked (`git status` shows
  it as `??`), stray saved-HTML snapshot of a rendered `/paper/...` page —
  not part of the app, not referenced by any route or template. Looks like
  a leftover debugging artifact from an earlier session's manual
  verification. Safe to delete whenever someone confirms it's not wanted;
  left in place here since removing files outside what was asked isn't
  this session's call to make.
- Papers admin form fields are `subject_name` free text, not FK'd to the
  new `subjects` catalogue — see "Department → Degree → Subject catalogue"
  above for why that wasn't merged.

## Conventions already followed correctly (keep doing these)

- PDO prepared statements everywhere for *values*.
- `password_hash()`/`password_verify()` for admin auth.
- Views (`app/views/**/*.php`) are plain PHP with no auto-escaping — call
  `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` on every dynamic string;
  there is no `e()` helper.
- `declare(strict_types=1)` at the top of every PHP file.
- Real LGU brand tokens in `variables.css` — don't hardcode colors/hexes in
  templates; use the CSS custom properties.
