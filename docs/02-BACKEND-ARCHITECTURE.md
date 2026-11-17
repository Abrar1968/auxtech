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
| Frontend bridge | `inertiajs/inertia-laravel` v3, **no Node on the server** (§3.1) | Controllers return `Inertia::render()`; no separate REST API for pages. Page components live in `resources/js/pages`; `ensure_pages_exist` is on, so a wrong component name fails fast |
| Routes in JS | `tightenco/ziggy` | |
| Auth | `laravel/fortify` (headless) | Login, password reset, TOTP 2FA |
| Authorisation | `spatie/laravel-permission` | Roles + permissions, enforced through policies |
| Media | `spatie/laravel-medialibrary` v11 | Conversions to WebP, responsive `srcset` |
| Translations | `spatie/laravel-translatable` | JSON columns holding `{"en": "...", "bn": "..."}` |
| Audit | `spatie/laravel-activitylog` | |
| HTML sanitising | `mews/purifier` | All rich text is purified on write |
| Queue | `sync` + after-response execution (`defer()` / `dispatchAfterResponse`) | No queue worker, cron or SSH is required on the host |
| Real-time | Pusher Channels (`pusher/pusher-php-server`, `laravel-echo`, `pusher-js`), cluster `ap2` | Live admin notifications (§6.1). Works on shared hosting because the server only makes outbound HTTPS calls |
| Analytics | Microsoft Clarity (project `zehuflmwqj`), GTM, Meta Pixel | Clarity loads on public pages in production only |
| Cache | `database` / `file` | |
| Quality | Pest 3, Pint, Larastan | Larastan is still to be installed (Phase 1, step 1.0) |

Sitemap, robots, `llms.txt`, feeds and JSON-LD are built in-house (small, and we need full control over the output).

## 2. Application structure

```
app/
├── Actions/                 Use cases with a single handle(): Content/SaveRecord, Leads/CaptureLead, Tracking/RecordConversionEvent
├── Admin/                   Admin module framework
│   ├── Field.php            One form field: describes itself to the UI and produces its validation rules
│   ├── Module.php           Base class: model, fields, list columns, filters, hooks
│   ├── ModuleRegistry.php   Modules listed in config/admin.php
│   └── Modules/             One small class per content type (ProjectModule, PostModule, UserModule ...)
├── Enums/                   PublishStatus, ProjectType, LeadStatus, LeadSource, EventType
├── Events/                  Pusher broadcasts: LeadCaptured, LeadUpdated, WhatsAppClicked
├── Http/
│   ├── Controllers/Site/    Public pages and the lead/event endpoints
│   ├── Controllers/Admin/   CrudController (all modules), Dashboard, Lead, Settings, System, Report
│   ├── Controllers/Feeds/   Sitemap, robots, llms.txt, RSS
│   ├── Controllers/InstallController.php
│   ├── Middleware/          EnsureInstalled, SetLocale, HandleInertiaRequests, HandleRedirects, CaptureAttribution, SecurityHeaders, EnsureAdmin
│   ├── Requests/            StoreLeadRequest, Admin/CrudRequest
│   └── Resources/           Public data shapes (never pass raw models to pages)
├── Models/ (+ Concerns/)    HasSlug, Publishable, Sortable, HasSeo, HasImages
├── Notifications/           NewLeadNotification (mail + in-app)
├── Services/                Settings\SettingsRepository, Seo\SeoManager, Seo\SchemaBuilder, WhatsApp\LinkBuilder, Meta\ConversionsApi
├── Support/                 AfterResponse, Installer, Installation, MailConfigurator, Realtime, Search, Phone, SiteCache, helpers
└── Tasks/                   Work run after the response: SendLeadEmail, NotifyAdminsOfLead, SendMetaLeadEvent, BroadcastConversion
```

### 2.1 Admin modules

Twenty content types share one controller, one form request and one save action. A module is a short class:

```php
class StatModule extends Module
{
    public function key(): string { return 'stats'; }
    public function model(): string { return Stat::class; }
    public function fields(): array
    {
        return [
            Field::text('value')->max(40)->required(),
            Field::text('label')->translatable()->required(),
            Field::toggle('is_active', 'Visible')->default(true),
        ];
    }
}
```

From that definition the framework derives the validation rules (`CrudRequest`), saves attributes, translations, relations, images and SEO overrides (`SaveRecord`), sanitises rich text, writes the activity log, clears caches, and sends the field schema to the admin UI. Routes are `/admin/{module}`; permissions are `{module}.view|create|update|delete`.

**To add a content type:** create the migration and model, write a module class, add it to `config/admin.php`, and run `php artisan db:seed --class=PermissionSeeder`.

Leads, settings, the dashboard and system tools have their own controllers because they are not plain CRUD.

Rules:

- Controllers stay thin: validate, call an action, return a resource or redirect.
- Models never reach a page raw. Public data goes through a Resource; admin data through the module's field list.
- `Model::shouldBeStrict()` is on outside production, so lazy loading and missing attributes fail loudly in tests.
- Authorisation is permission-based and checked in the controller for every admin action; Super Admin passes through `Gate::before`.

## 3. Request lifecycle

**Public page**

```
Request → SecurityHeaders → HandleRedirects → SetLocale → CaptureAttribution
        → HandleInertiaRequests (shared props) → Site controller
        → cached query → Resource → Inertia::render() → Blade root (SEO tags + content fallback) → HTML
```

**Shared Inertia props** (every public response): `locale`, `locales`, `alternateUrls`, `settings` (public subset: company, contact, socials, WhatsApp number), `navigation`, `seo` (title, description, canonical, og, robots, JSON-LD graph), `flash`, `consent`.

**Root Blade template** (`resources/views/app.blade.php`) prints `<title>`, meta tags, `hreflang` links and the JSON-LD `<script>` from the `seo` prop **on the server**, independent of Vue.

### 3.1 Rendering without Node: the Blade content fallback

The site is deployed by unzipping a file on shared hosting, so no Node process can run there and Inertia SSR is off (`INERTIA_SSR_ENABLED=false`). To keep the content visible to search engines and AI crawlers that do not run JavaScript:

1. Every public controller passes its page data to Inertia as usual.
2. The root Blade template renders a **plain semantic HTML version of the same data** inside the app root (`<div id="app">`): headings, paragraphs, lists, links, images with alt text. Each page type has a small Blade partial in `resources/views/fallback/` (home, work index, case study, service, solution, post, page, etc.).
3. In the browser, Vue mounts and replaces that markup with the designed, animated page.

The fallback contains exactly the content the visitor sees, so it is not cloaking. It is unstyled apart from a minimal inline style that avoids a flash before Vue mounts. Feature tests assert that every public page's fallback contains its `h1`, main text and internal links (FR-S01).

**App shell (added in Phase 3).** Without SSR the largest paint waited for the JavaScript bundle. `resources/views/shell/_hero.blade.php` now prints the top of each public page (the hero: eyebrow, title, lead) inside `#app` with the same Tailwind classes Vue uses, marked `aria-hidden` and `data-shell`. Vue replaces it on mount, so the visitor sees styled content at once and nothing shifts. With JavaScript off the shell is hidden (`html:not(.js) [data-shell]`) and the fallback is the page. Keep the shell's classes in step with `HomeHero.vue` and `PageHero.vue`.

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
| `leads` | `uuid`, `name`, `phone` (E.164), `email`, `company`, `service_id`, `solution_id`, `project_id`, `budget_range`, `message`, `source` (enum: contact_form, email, demo_request, estimator, whatsapp, newsletter, career), `status` (enum: new, contacted, qualified, proposal, won, lost, spam), `assigned_to`, `locale`, `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`, `gclid`, `fbclid`, `referrer`, `landing_url`, `page_url`, `ip_hash`, `user_agent`, `meta`(json: estimator answers), `contacted_at`, `closed_at` |
| `lead_notes` | `lead_id`, `user_id`, `body` |
| `conversion_events` | `type` (enum: whatsapp_click, email_click, demo_click, form_start, form_submit, estimator_step), `placement`, `page_url`, `visitor_id`, `lead_id`, `locale`, `device`, UTM columns, `created_at` |
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
      ├─ NotifySales (after response)   email to auxtechbd@gmail.com + database notification
      ├─ BroadcastLead (after response) Pusher `lead.captured` on the private admin channel
      ├─ SendMetaCapiEvent (after resp.) server-side Lead event, event_id shared with the Pixel
      └─ optional Telegram / WhatsApp Cloud API ping
      │
      ▼
Response: lead uuid + ready-made WhatsApp URL ("continue on WhatsApp")
```

- The lead row is committed **before** any external call. External calls run after the response; failures are recorded for retry and never lose a lead (NFR-11).
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
| Public page data | Cache keys per entity type and locale (e.g. `projects:en`), tracked in a small key registry because the database/file cache has no tags; invalidated by model observers |
| Full responses | Optional full-page HTML cache for anonymous GET requests, cleared on content change |
| Images | Conversions generated at upload time; `srcset` widths 480/768/1280/1920; long-lived immutable cache headers |
| Database | Eager loading enforced; indexes on every `slug`, `status + published_at`, `sort_order`, foreign key |
| HTTP | Brotli/gzip, `Cache-Control` for assets, `103 Early Hints`/preload for the hero image and fonts where the server supports it |

## 10. Security (NFR-09, NFR-10)

- `SecurityHeaders` middleware: Content Security Policy with a fresh nonce per request (`Vite::useCspNonce()`): `script-src 'self' 'nonce-…' 'strict-dynamic'`, so the Vite bundle, the inline theme script and Clarity run, and any script they add (GTM, Meta Pixel, Pusher) is trusted through `strict-dynamic` instead of a host list; `object-src 'none'`, `base-uri 'self'`, `form-action 'self'`, `frame-ancestors 'self'`. Over HTTPS also HSTS and `upgrade-insecure-requests`. Plus `X-Content-Type-Options`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy`. Every inline `<script>` in Blade must carry `nonce="{{ Vite::cspNonce() }}"` (a test checks this).
- CSRF on every state-changing route; `SameSite=Lax`, `Secure`, `HttpOnly` cookies.
- Validation in Form Requests only; only validated fields that a module declares are ever written.
- Rich text purified on write with an allow-list; output escaped by Vue by default; `v-html` only for purified fields.
- Uploads: JPG, PNG and WebP only, 6 MB limit, and each file is opened as an image before it is accepted, so a renamed script or an SVG is rejected.
- Rate limits: leads 5/min and 30/day per IP; events 60/min; search 30/min; login 5/min.
- Secrets only in `.env`; tracking tokens (CAPI) stored encrypted in settings.
- `/admin` responses send `X-Robots-Tag: noindex, nofollow`.

## 11. Environments and deployment

**Local (XAMPP):** steps are in the root `README.md`. Uses MySQL on `127.0.0.1:3306`, user `root`, no password, database `auxtech`. `composer run dev` starts the PHP server, log tail and Vite together.

### Production: one zip, unzip, done

Hosting is shared cPanel. **No SSH, Composer, Node or cron is required on the server.** The deliverable is a single file, `auxtech-production.zip`, built by `scripts/build-release.sh` on the developer machine.

**What the zip contains:** application code, `vendor/` (production dependencies only, optimised autoloader), compiled frontend assets (`public/build`), the root `.htaccess`, and `.env.production` (a ready-made environment template that includes the Pusher keys and Clarity ID). It contains no `node_modules`, tests, docs or development files.

**Installation (done once, in the browser):**

1. In cPanel create a MySQL database and user (cPanel → MySQL Databases).
2. Upload the zip to the domain's folder with File Manager and extract it.
3. Open `https://auxtechbd.com/install`. The installer:
   - checks PHP version (8.2+), extensions and folder permissions, and explains anything missing;
   - asks for the database name, user and password and tests the connection;
   - asks for the admin name, email and password;
   - optionally asks for the Gmail app password used to send email (§11.2);
   - writes `.env` from `.env.production`, generates the app key, runs all migrations and the seeders;
   - locks itself (writes `storage/installed`) so it can never run again.
4. The site is live; `/admin` is ready.

**Updates:** upload and extract a newer zip over the old files (`.env`, `storage/` and uploads are never in the zip, so nothing is overwritten). On the next admin login a Super Admin sees "Database update available" and runs pending migrations with one click (`/admin/system/update`). Caches are cleared automatically when the release version changes.

**Folder layout and security.** The zip can be extracted directly into the domain's document root. The root `.htaccess` (in the repository) forwards every request to `public/` and returns 403 for dotfiles (`.env`), `app/`, `config/`, `storage/`, `vendor/` and other private paths. If the host allows it, pointing the domain's document root at `public/` is even better and needs no other change.

**Uploads without a symlink.** `php artisan storage:link` needs a shell, and many shared hosts disable `symlink()`. The public upload disk therefore writes directly to `public/uploads/`; private files (CVs, backups) stay in `storage/app/private`.

**No background processes.**

| Need | How it works without a worker or cron |
|---|---|
| Lead emails, Pusher events, Meta CAPI calls | The lead is saved first, the response is sent, then the slow work runs in the same PHP process after the response (`defer()`). Failures are logged in a `failed_tasks` table and can be retried from the admin panel |
| Image conversions (WebP, responsive sizes) | Run during upload in the admin panel, with a progress indicator |
| Scheduled publishing | Not a job: public queries filter on `published_at <= now()` |
| Sitemap, `llms.txt`, feeds | Built on request and cached; cache cleared when content changes |
| Database backup, log pruning | "Run now" buttons in Admin → System, also triggered opportunistically once a day by the first admin request |

A cron entry (`php artisan schedule:run`, addable from cPanel → Cron Jobs without SSH) is supported as an optional improvement but nothing depends on it.

### 11.1 Real-time with Pusher

Pusher Channels app `2212282`, cluster `ap2`. The key and cluster are public and live in `.env.example`; **the secret is never committed** — it is in the developer's local `.env` and in the release zip's `.env.production`.

| Event (private channel `admin`) | Trigger | Admin panel reaction |
|---|---|---|
| `lead.captured` | New lead stored | Toast + sound, notification badge, leads table and kanban update live |
| `whatsapp.clicked` | WhatsApp CTA click recorded | Dashboard counters and "live activity" feed tick up |
| `lead.updated` | Status, assignee or note changed | Other admins see the change without reloading |

Channel access is authorised through `/broadcasting/auth` for signed-in admin users with the matching permission. Public pages never load `pusher-js`. If Pusher is unreachable the site and lead capture work normally; only the live updates pause.

### 11.2 Email

All notifications go to **auxtechbd@gmail.com**; no hosting mailbox is used.

- **Sending:** Gmail SMTP (`smtp.gmail.com:587`, TLS) signed in as `auxtechbd@gmail.com` with a **Google App Password** (Google Account → Security → 2-Step Verification → App passwords). The password is entered in the installer or in Admin → Settings → Mail and stored encrypted. A "Send test email" button verifies it.
- **Until an app password is set**, leads are still stored, shown in the admin panel and pushed live through Pusher; only the email copy is skipped, and the dashboard shows a reminder.
- **Email as a CTA:** `mailto:auxtechbd@gmail.com` links with a pre-filled subject, plus the contact form.

### 11.3 Other production rules

`APP_DEBUG=false`; HTTPS forced (cPanel AutoSSL) with HSTS; `public/.htaccess` adds compression and long cache headers for `/build`; `auxtechsoftware.com` and `www` variants 301 to `https://auxtechbd.com` (managed in Admin → SEO → Redirects or `.htaccess`).

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
