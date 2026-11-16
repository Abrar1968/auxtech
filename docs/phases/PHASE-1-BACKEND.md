# Phase 1 — Full Backend

**Owner:** Senior Backend Developer
**Specification:** [`../02-BACKEND-ARCHITECTURE.md`](../02-BACKEND-ARCHITECTURE.md), requirements in [`../01-SRS.md`](../01-SRS.md)
**Outcome:** a complete, tested backend. Every public page returns real data through Inertia, the admin API is complete, and all SEO feeds work. Frontend pages in this phase are plain, unstyled placeholders that only prove the data contract.

Work top to bottom. Each step ends with passing tests.

## 1.0 Preparation

- [ ] Install Larastan (`composer require --dev larastan/larastan`), add `phpstan.neon` at level 6
- [ ] Add MariaDB 10.4 job to CI (already defined in `ci.yml`), confirm green
- [ ] `AppServiceProvider`: `Model::shouldBeStrict()` outside production, immutable dates, HTTPS forcing in production

## 1.1 Foundations

- [ ] Enums: `Locale`, `PublishStatus`, `ProjectType`, `LeadStatus`, `LeadSource`, `EventType`
- [ ] Model concerns: `HasSlug`, `Publishable` (scopes `published()`, `scheduled()`), `Sortable`, `HasSeo`
- [ ] `settings` table + `SettingsRepository` (typed getters, cache, public subset)
- [ ] `SetLocale` middleware and localised route group (`/` and `/bn`), `alternateUrls` helper
- [ ] `SecurityHeaders` middleware (CSP with nonce, HSTS, etc.)
- [ ] Shared Inertia props: locale, settings, navigation, seo, flash
- [ ] Root Blade template prints all SEO tags and JSON-LD from the `seo` prop

**Done when:** locale switching, shared props and headers are covered by feature tests.

## 1.2 Auth and access control

- [ ] Fortify with Inertia pages for login, forgot/reset password, 2FA challenge (minimal markup)
- [ ] Roles and permissions seeder (Super Admin, Admin, Editor, Sales)
- [ ] `EnsureTwoFactorEnabled` for Admin roles; login throttling; `last_login_at`
- [ ] Policies for every model; `can` map shared to admin pages
- [ ] Users CRUD (Super Admin only)

**Done when:** the role × module authorisation matrix test passes (TC-A01–A04).

## 1.3 Content domain

Migrations, models, factories, resources, policies, observers and admin controllers for:

- [ ] Industries, technologies
- [ ] Services (nested) with features and process steps
- [ ] Solutions, feature groups, features
- [ ] Clients
- [ ] Projects (relations, metrics, media collections, ordering, featured)
- [ ] Testimonials
- [ ] Posts, categories, tags
- [ ] Pages (blocks), FAQs (morph), pricing plans, team members
- [ ] Locations and SEO landing pages
- [ ] Careers: openings and applications (private CV storage)
- [ ] Media library: conversions (WebP, responsive widths), alt text per locale, admin endpoints
- [ ] Rich-text purification on write
- [ ] Publish scheduling command (every minute)

**Done when:** CRUD feature tests pass for every module (success, validation, authorisation) and N+1 assertions pass on list endpoints.

## 1.4 Public site controllers

- [ ] Home (sections driven by settings), Work index with filters, Case study
- [ ] Services index/detail, Solutions index/detail, Ecommerce flagship, Auxify
- [ ] Industries, Pricing, About, FAQ, Contact, Estimator, Careers
- [ ] Blog index, category, tag, post
- [ ] CMS pages and landing pages (catch-all, last in route order)
- [ ] Search endpoint
- [ ] Tagged caching per entity and locale with observer invalidation
- [ ] Custom 404/500/503 responses through Inertia

**Done when:** every route returns 200 in both locales with the documented prop shape (TC-P01), and drafts are never exposed (TC-P05).

## 1.5 Leads and tracking

- [ ] `leads`, `lead_notes`, `conversion_events`, `newsletter_subscribers`
- [ ] `CaptureAttribution` middleware (first-touch cookie)
- [ ] `StoreLeadRequest`: honeypot, time trap, throttle, optional Turnstile, BD phone normaliser
- [ ] `CaptureLead` action with 24-hour de-duplication
- [ ] `LeadCaptured` event → mail + database notification, Meta CAPI job, optional Telegram
- [ ] `WhatsApp\LinkBuilder` with templates per context and locale
- [ ] `/api/events` beacon endpoint and `/go/whatsapp` redirect
- [ ] Admin: leads list, filters, kanban data, detail, notes, status, assignment, CSV export, spam
- [ ] Dashboard metrics queries

**Done when:** TC-L01–L13 pass, including the "mail server down" case.

## 1.6 SEO and feeds

- [ ] `seo_metas` + admin SEO endpoints; missing-meta report
- [ ] `SchemaBuilder` for all page types
- [ ] Sitemap index and child sitemaps with `hreflang` and images; regeneration job
- [ ] `robots.txt` from settings; `llms.txt` and `llms-full.txt`
- [ ] Blog RSS feed
- [ ] Redirects table, `HandleRedirects` middleware, automatic slug redirects
- [ ] Open Graph image generator
- [ ] IndexNow job

**Done when:** TC-S01–S09 pass at the HTTP level.

## 1.7 Settings, activity and operations

- [ ] Settings admin: company, contact, socials, WhatsApp templates, tracking IDs, homepage sections and stats, mail
- [ ] Activity log on all admin mutations
- [ ] Scheduler entries; database backup command
- [ ] Deployment notes in `README.md` verified on a clean machine

## 1.8 Seed data

- [ ] Roles, admin user, settings
- [ ] Services, solutions, **23 Auxtech Ecommerce modules in 4 groups**
- [ ] Industries, technologies
- [ ] **32 clients and projects** with screenshots from `resources/seed/portfolio/` (demo projects typed `demo`; case-study text left as draft)
- [ ] Starter FAQs and legal pages
- [ ] Re-capture screenshots with popups dismissed, plus mobile versions (`scripts/capture-sites.cjs`)

**Done when:** `php artisan migrate:fresh --seed` yields a browsable site on XAMPP MySQL.

## Phase 1 exit criteria

1. `pint --test`, `phpstan`, and `pest` pass on SQLite and MariaDB 10.4.
2. ≥ 80% coverage on Actions, Services and Http.
3. TypeScript types for all Resources exist in `resources/js/types/models.d.ts`.
4. Open questions Q1–Q3 from the SRS are answered or have agreed placeholders.
