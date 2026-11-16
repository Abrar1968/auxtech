# 02 — Backend Architecture

**Role:** Senior Backend Developer
**Implements:** Phase 1 ([`phases/PHASE-1-BACKEND.md`](phases/PHASE-1-BACKEND.md))
**Requirement IDs** refer to [`01-SRS.md`](01-SRS.md).

---

## 1. Stack

| Concern | Choice | Notes |
|---|---|---|
| Framework | Laravel 12 | `composer.json` pins `config.platform.php` to 8.2.12 so the lock file installs on XAMPP's PHP 8.2 |
| Database | MySQL 8 **or MariaDB 10.4+** | XAMPP's "MySQL" is MariaDB. See §4.4 for the compatibility rules |
| Frontend bridge | `inertiajs/inertia-laravel` v3 + SSR | Controllers return `Inertia::render()`; no separate REST API for pages. Page components live in `resources/js/pages`; `ensure_pages_exist` is on, so a wrong component name fails fast |
| Routes in JS | `tightenco/ziggy` | |
| Auth | `laravel/fortify` (headless) | Login, password reset, TOTP 2FA |
| Authorisation | `spatie/laravel-permission` | Roles + permissions, enforced through policies |
| Media | `spatie/laravel-medialibrary` v11 | Conversions to WebP, responsive `srcset` |
| Translations | `spatie/laravel-translatable` | JSON columns holding `{"en": "...", "bn": "..."}` |
| Audit | `spatie/laravel-activitylog` | |
| HTML sanitising | `mews/purifier` | All rich text is purified on write |
| Queue | `database` driver locally, Redis in production | |
| Cache | `database`/`file` locally, Redis in production | |
| Quality | Pest 3, Pint, Larastan | Larastan is still to be installed (Phase 1, step 1.0) |

Sitemap, robots, `llms.txt`, feeds and JSON-LD are built in-house (small, and we need full control over the output).

## 2. Application structure

Domain logic is kept out of controllers. Controllers validate (Form Request), call an Action, and return an Inertia response or redirect.

```
app/
├── Actions/                 One class per use case, single public handle()
│   ├── Leads/               CaptureLead, UpdateLeadStatus, AssignLead, ExportLeads
│   ├── Tracking/            RecordConversionEvent, ResolveAttribution
│   ├── Seo/                 BuildSitemap, BuildLlmsTxt, BuildSchemaGraph, GenerateOgImage
│   └── Content/             PublishProject, ReorderItems, SyncSlugRedirect
├── Data/                    Readonly DTOs passed between layers (LeadData, AttributionData, SeoData)
├── Enums/                   LeadStatus, LeadSource, PublishStatus, ProjectType, EventType, Locale
├── Events/ Listeners/       LeadCaptured → NotifySales, SendMetaCapiEvent
├── Http/
│   ├── Controllers/Site/    Public pages (invokable or small resource controllers)
│   ├── Controllers/Admin/   Admin CRUD
│   ├── Controllers/Feeds/   Sitemap, Robots, Llms, Rss, OgImage
│   ├── Middleware/          SetLocale, HandleInertiaRequests, CaptureAttribution, SecurityHeaders, HandleRedirects
│   ├── Requests/            Form Requests (Site/, Admin/)
│   └── Resources/           API Resources shaping Inertia props (never pass raw models)
├── Jobs/                    SendLeadNotification, SendMetaCapiEvent, PingIndexNow, RegenerateSitemap
├── Models/  (+ Concerns/)   HasSeo, HasSlug, Publishable, Sortable, HasTranslatableSlug
├── Notifications/           NewLeadNotification (mail + database)
├── Observers/               Cache invalidation, slug-change redirects, sitemap regeneration
├── Policies/                One per model
├── Services/                WhatsApp\LinkBuilder, Seo\SchemaBuilder, Settings\SettingsRepository, Meta\ConversionsApi
└── Support/                 Phone normaliser, helpers
```

Rules:

- Strict types and `final` classes by default; constructor injection; no facades inside Actions where an injected contract exists.
- Models never leave the backend raw. Every Inertia prop goes through a Resource so the frontend contract is explicit and lazy relations cannot leak.
- Every query that feeds a list page is eager-loaded; `Model::preventLazyLoading()` is on outside production.
- Enums are backed strings and cast on the model.

## 3. Request lifecycle

**Public page**

```
Request → SecurityHeaders → HandleRedirects → SetLocale → CaptureAttribution
        → HandleInertiaRequests (shared props) → Site controller
        → cached query → Resource → Inertia::render() → SSR server → HTML
```

**Shared Inertia props** (every public response): `locale`, `locales`, `alternateUrls`, `settings` (public subset: company, contact, socials, WhatsApp number), `navigation`, `seo` (title, description, canonical, og, robots, JSON-LD graph), `flash`, `consent`.

**Root Blade template** (`resources/views/app.blade.php`) prints `<title>`, meta tags, `hreflang` links and the JSON-LD `<script>` from the `seo` prop **on the server**, independent of Vue. Search engines therefore get correct metadata even if the SSR process is down (§11).

## 4. Data model

All content tables carry `id`, timestamps, and where stated `status`, `published_at`, `sort_order`, soft deletes. Columns marked **(t)** are translatable JSON.

### 4.1 Content

| Table | Key columns | Relations |
|---|---|---|
| `services` | `parent_id`, `slug`, `title`(t), `tagline`(t), `excerpt`(t), `body`(t), `icon`, `features`(json), `process_steps`(json), `is_featured` | self-nesting; `projects` (m:n); `faqs` (morph) |
| `solutions` | `slug`, `name`(t), `tagline`(t), `summary`(t), `body`(t), `demo_url`, `is_flagship` | `feature_groups`, `plans`, `projects`, `faqs` |
| `solution_feature_groups` | `solution_id`, `key`, `name`(t), `description`(t) | `features` |
| `solution_features` | `group_id`, `slug`, `title`(t), `description`(t), `icon` | `projects` (m:n, "modules used") |
| `industries` | `slug`, `name`(t), `description`(t), `icon` | `clients`, `projects` |
| `technologies` | `slug`, `name`, `category`, logo (media) | `projects` (m:n) |
| `clients` | `slug`, `name`, `website_url`, `industry_id`, `location`, `show_logo`, `is_featured` | `projects`, `testimonials` |
| `projects` | `client_id`, `solution_id`, `industry_id`, `slug`, `type` (enum: live, demo, product), `title`(t), `tagline`(t), `summary`(t), `challenge`(t), `approach`(t), `outcome`(t), `live_url`, `metrics`(json), `launched_at`, `is_featured` | `services`, `technologies`, `features` (m:n); media collections `cover`, `desktop`, `mobile`, `gallery` |
| `testimonials` | `client_id`, `project_id`, `author_name`, `author_role`(t), `quote`(t), `rating`, `video_url`, `is_featured` | |
| `posts` | `author_id`, `category_id`, `slug`, `title`(t), `excerpt`(t), `body`(t), `reading_minutes`, `is_featured` | `tags` (m:n) |
| `post_categories`, `tags` | `slug`, `name`(t) | |
| `pages` | `slug`, `title`(t), `template`, `blocks`(t json) | |
| `landing_pages` | `slug`, `service_id`, `industry_id`, `location_id`, `h1`(t), `intro`(t), `body`(t) | `faqs` |
| `locations` | `slug`, `name`(t), `type` (city, area) | |
| `faqs` | `faqable_type`, `faqable_id` (nullable morph), `group`, `question`(t), `answer`(t) | |
| `pricing_plans` | `planable` morph, `name`(t), `price`, `currency`, `period`, `features`(t json), `is_popular`, `cta_type` | |
| `team_members` | `name`, `role`(t), `bio`(t), `socials`(json) | |
| `job_openings`, `job_applications` | standard careers fields; CV in a private media disk | |

### 4.2 Leads and tracking

| Table | Key columns |
|---|---|
| `leads` | `uuid`, `name`, `phone` (E.164), `email`, `company`, `service_id`, `solution_id`, `project_id`, `budget_range`, `message`, `source` (enum: contact_form, callback, demo_request, estimator, whatsapp, newsletter, career), `status` (enum: new, contacted, qualified, proposal, won, lost, spam), `assigned_to`, `locale`, `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`, `gclid`, `fbclid`, `referrer`, `landing_url`, `page_url`, `ip_hash`, `user_agent`, `meta`(json: estimator answers), `contacted_at`, `closed_at` |
| `lead_notes` | `lead_id`, `user_id`, `body` |
| `conversion_events` | `type` (enum: whatsapp_click, call_click, demo_click, form_start, form_submit, estimator_step), `placement`, `page_url`, `visitor_id`, `lead_id`, `locale`, `device`, UTM columns, `created_at` |
| `newsletter_subscribers` | `email`, `token`, `confirmed_at`, `unsubscribed_at` |

Indexes: `leads(status, created_at)`, `leads(phone)`, `leads(assigned_to)`, `conversion_events(type, created_at)`, `conversion_events(visitor_id)`.

### 4.3 System

| Table | Purpose |
|---|---|
| `users` | + `is_active`, `last_login_at`, Fortify 2FA columns |
| `roles`, `permissions`, pivots | spatie/permission |
| `settings` | `group`, `key`, `value`(json), `is_public`; cached forever, busted on write |
| `seo_metas` | morph: `title`(t), `description`(t), `canonical`, `robots`, `og_image` (media), `schema_extra`(json) |
| `redirects` | `from_path` (unique), `to_url`, `status_code`, `hits`, `last_hit_at` |
| `media` | spatie/medialibrary; custom property `alt` per locale |
| `activity_log` | spatie/activitylog |
| `cache`, `jobs`, `failed_jobs`, `sessions`, `notifications` | Laravel defaults |

### 4.4 MariaDB 10.4 compatibility rules (XAMPP)

1. JSON columns are stored as `LONGTEXT` with a JSON check. Use Eloquent casts; **do not** use `->` JSON path expressions in raw SQL or JSON indexes.
2. Unique slugs are plain `VARCHAR(191)` columns, not generated columns.
3. Charset `utf8mb4`, collation `utf8mb4_unicode_ci` (Bangla text and emoji).
4. No window functions in required code paths, no `CHECK` constraints relied upon for logic, no functional indexes.
5. Full-text search uses `FULLTEXT` indexes on denormalised `search_text` columns; fall back to `LIKE` under SQLite in tests.
6. CI runs the test suite on SQLite (fast) **and** on MariaDB 10.4 (compatibility).

## 5. Routing

```
routes/
├── web.php        Public site, wrapped in the locale group
├── admin.php      /admin, middleware: auth, verified-2fa, can:access-admin
├── feeds.php      sitemap, robots, llms, rss, og-image (no locale, no Inertia)
└── api.php        Small JSON endpoints used by the public frontend
```

**Localisation:** one route definition serves both locales. English has no prefix; Bangla uses `/bn`. `SetLocale` reads the prefix, sets the app locale and exposes `alternateUrls`. Slugs are shared across locales (Latin slugs are more shareable and stable).

**Public JSON endpoints** (all throttled, CSRF-protected, return 422 with field errors):

| Method | URI | Action |
|---|---|---|
| POST | `/api/leads` | `CaptureLead` |
| POST | `/api/events` | `RecordConversionEvent` (sent with `navigator.sendBeacon`) |
| POST | `/api/newsletter` | subscribe |
| GET | `/api/search?q=` | site search |
| GET | `/go/whatsapp` | records the click server-side and 302s to `wa.me` (no-JS fallback; `rel="nofollow"`) |

**Admin routes** are resourceful: `admin.projects.index|create|store|edit|update|destroy`, plus `reorder`, `publish`, `duplicate` where relevant.

## 6. Lead pipeline (FR-B04–B06, FR-A15, FR-I01–I04)

```
Form submit / WhatsApp click
      │
      ▼
StoreLeadRequest ── honeypot, min-time, throttle, Turnstile (optional), phone normalise
      │
      ▼
CaptureLead ── DB transaction: create Lead (+ attribution from CaptureAttribution cookie)
      │          de-duplicate: same phone within 24h → append to existing lead as a note
      ▼
LeadCaptured event (after commit)
      ├─ SendLeadNotification (queue)   mail + database notification to sales role
      ├─ SendMetaCapiEvent (queue)      server-side Lead event, event_id shared with the Pixel
      └─ optional Telegram / WhatsApp Cloud API ping
      │
      ▼
Response: lead uuid + ready-made WhatsApp URL ("continue on WhatsApp")
```

- The lead row is committed **before** any external call. External failures retry on the queue and never lose a lead (NFR-11).
- `CaptureAttribution` middleware stores first-touch UTM, click IDs, referrer and landing URL in an encrypted first-party cookie for 90 days, only overwriting when a new campaign parameter arrives.
- `WhatsApp\LinkBuilder` builds `https://wa.me/8801805019415?text=...` from a template key (`default`, `service`, `project`, `plan`, `estimator`), locale and context. Templates live in settings.
- IP addresses are stored as `hash_hmac('sha256', ip, app_key)`; raw IPs are never persisted.

## 7. SEO and GEO services (FR-S01–S12)

| Component | Responsibility |
|---|---|
| `HasSeo` trait | Gives a model a `seo` morph relation and `toSeoData()` with fallbacks (title → name + brand; description → excerpt, trimmed to 155 chars; image → cover) |
| `Seo\SchemaBuilder` | Builds one JSON-LD `@graph` per page: `Organization` + `LocalBusiness` (`ProfessionalService`) with name, address, geo, phone, hours, `sameAs`; `WebSite`; `BreadcrumbList`; plus the page type (`Service`, `SoftwareApplication`, `CreativeWork`, `Article`, `FAQPage`, `JobPosting`, `ItemList`) |
| `BuildSitemap` | Sitemap index + `pages`, `work`, `services`, `solutions`, `blog`, `landing` sitemaps with `xhtml:link` alternates and `image:image`; cached, rebuilt by observer |
| `RobotsController` | Serves admin-managed rules; defaults allow all, disallow `/admin`, and list the sitemap. `GPTBot`, `OAI-SearchBot`, `ChatGPT-User`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`, `Bingbot` are explicitly allowed |
| `BuildLlmsTxt` | `/llms.txt`: company summary, key facts, links to main pages. `/llms-full.txt`: plain-text dump of services, solutions, modules, case-study summaries and FAQs |
| `GenerateOgImage` | 1200×630 PNG with GD: brand background, logo, page title; cached on disk |
| `HandleRedirects` | Looks up the request path in cached redirects before routing; counts hits |
| `SyncSlugRedirect` | On slug change, inserts a 301 from the old URL and collapses chains |
| `PingIndexNow` | Queued on publish |

## 8. Admin, auth and authorisation

- Fortify handles login, reset and 2FA; views are Inertia pages. Registration is disabled; users are created by a Super Admin.
- Login: 5 attempts per minute per email + IP. 2FA is mandatory for Super Admin and Admin roles.
- Roles and default permissions:

| Role | Can |
|---|---|
| Super Admin | Everything, including users, roles, settings |
| Admin | All content, leads, SEO, settings except users/roles |
| Editor | Content, media and SEO; no leads, no settings |
| Sales | Leads and dashboard only |

- Permissions follow `{resource}.{view|create|update|delete|publish}`; each model has a Policy; controllers call `authorize()`; the frontend receives a `can` map per resource.
- All admin mutations are recorded by activitylog with old and new values.

## 9. Caching and performance

| Layer | Strategy |
|---|---|
| Settings, navigation, redirects | `Cache::rememberForever`, flushed by observers |
| Public page data | Tagged cache per entity type and locale (e.g. `projects:en`); invalidated by model observers |
| Full responses | Optional micro-cache (60 s) for anonymous GET requests in production |
| Images | Conversions generated on a queue; `srcset` widths 480/768/1280/1920; long-lived immutable cache headers |
| Database | Eager loading enforced; indexes on every `slug`, `status + published_at`, `sort_order`, foreign key |
| HTTP | Brotli/gzip, `Cache-Control` for assets, `103 Early Hints`/preload for the hero image and fonts where the server supports it |

## 10. Security (NFR-09, NFR-10)

- `SecurityHeaders` middleware: CSP with nonces (allow-list for GTM, GA, Meta, Turnstile, Maps), HSTS, `X-Content-Type-Options`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy`, frame-ancestors `'self'`.
- CSRF on every state-changing route; `SameSite=Lax`, `Secure`, `HttpOnly` cookies.
- Validation in Form Requests only; mass assignment guarded by explicit `$fillable`.
- Rich text purified on write with an allow-list; output escaped by Vue by default; `v-html` only for purified fields.
- Uploads: MIME + extension + size checks, images re-encoded, SVG uploads sanitised or rejected, CVs on a private disk served through signed URLs.
- Rate limits: leads 5/min and 30/day per IP; events 60/min; search 30/min; login 5/min.
- Secrets only in `.env`; tracking tokens (CAPI) stored encrypted in settings.
- `/admin` responses send `X-Robots-Tag: noindex, nofollow`.

## 11. Environments and deployment

**Local (XAMPP):** steps are in the root `README.md`. Uses MySQL on `127.0.0.1:3306`, user `root`, no password, database `auxtech`. `composer run dev` starts the PHP server, queue worker, log tail and Vite together.

**Production (recommended): VPS** with Nginx, PHP-FPM 8.2+, MySQL 8 or MariaDB, Redis, Node 20+ and Supervisor running:

```
php artisan queue:work --tries=3
php artisan inertia:start-ssr
```

plus the scheduler cron (`* * * * * php artisan schedule:run`).

**Fallback: shared cPanel hosting without Node.** The site still works: Inertia falls back to client-side rendering automatically, and titles, meta tags, `hreflang` and JSON-LD are still printed server-side by the Blade root template. Body content is then rendered by JavaScript, which Google handles but most AI crawlers do not. SSR on a VPS is therefore strongly recommended for goals G2 and G3.

**Scheduled tasks:** sitemap rebuild (daily, plus on change), queue pruning, publish scheduled content (every minute), activity-log pruning (monthly), database backup (daily).

**Deploy script:** `composer install --no-dev -o` → `npm ci && npm run build` → `php artisan migrate --force` → `php artisan optimize` → restart queue and SSR.

## 12. Seed data

`DatabaseSeeder` creates a usable site on first run:

1. Roles, permissions, one Super Admin (credentials from `.env`).
2. Settings: company name, address, WhatsApp number, WhatsApp templates.
3. Industries, technologies, the 3 services with sub-services.
4. Solutions, with **Auxtech Ecommerce and its 23 modules in 4 groups** (see `00-RESEARCH.md` §3).
5. The **32 clients and projects** from `docs/research/client-sites.json`, with screenshots from `resources/seed/portfolio/`. Demo/staging projects get `type = demo`. Case-study text is seeded as drafts for Auxtech to complete; nothing invented is published.
6. Starter FAQs and legal pages.

## 13. Backend definition of done

- Migration runs clean on MariaDB 10.4 and MySQL 8, forwards and backwards.
- Every endpoint has a Form Request, a Policy check (admin) and feature tests for success, validation failure and authorisation failure.
- `pint --test`, `phpstan analyse` (level 6) and `pest` pass.
- No N+1 queries on any list page (asserted in tests).
- Resource shape documented as a TypeScript type in `resources/js/types/` for the frontend.
