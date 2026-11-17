# Phase 1 — Full Backend

**Owner:** Senior Backend Developer
**Specification:** [`../02-BACKEND-ARCHITECTURE.md`](../02-BACKEND-ARCHITECTURE.md), requirements in [`../01-SRS.md`](../01-SRS.md)
**Status:** complete (17 Nov 2026), with the deferred items listed at the end.

The backend is finished and tested. Every public page returns real data, the admin panel's endpoints are complete, the SEO feeds work, and the production zip installs through the browser. The pages themselves are plain placeholders that print the data they receive; Phase 2 replaces them with the designed interface.

## What was built

| Area | Delivered |
|---|---|
| Data model | 30+ tables: services, solutions and their feature groups, industries, clients, projects, testimonials, blog, pages, landing pages, FAQs, pricing plans, team, stats, leads, conversion events, settings, SEO overrides, redirects, failed tasks |
| Languages | English at `/`, Bangla at `/bn`; translatable JSON columns with English fallback; UI strings in `lang/bn.json` |
| Admin | One module framework (`app/Admin`) serving 20 content modules: list, search, filter, create, edit, delete, restore, reorder, image upload, SEO panel |
| Admin extras | Dashboard metrics, leads (filters, pipeline, notes, assignment, CSV export), settings with encrypted secrets, activity log, SEO report, system tools |
| Access | Sign-in with throttling, four roles, permission checks on every admin route, private live-updates channel |
| Public pages | Home, work (with filters), case study, services, solutions, industries, pricing, about, FAQ, contact, estimator, blog, CMS and landing pages, search, branded errors |
| Crawlable content | Server-printed title, description, canonical, `hreflang`, Open Graph and JSON-LD, plus a plain-HTML content fallback for every public page type |
| Leads | Store-first capture, Bangladeshi phone normalisation, honeypot and time trap, rate limits, 24-hour de-duplication, first-touch attribution, hashed IP |
| After-response work | Email to auxtechbd@gmail.com, in-app notifications, Pusher events, Meta Conversions API; failures recorded and retryable |
| WhatsApp | Context- and language-aware message templates, click recording, `/go/whatsapp` for no-JavaScript visitors |
| SEO feeds | `sitemap.xml` (both languages, images), `robots.txt` (AI crawlers allowed), `llms.txt`, `llms-full.txt`, `feed.xml`, redirect manager, automatic slug redirects |
| Shared-hosting delivery | Browser installer at `/install`, `scripts/build-release.sh`, root `.htaccess`, uploads without a symlink, one-click database update |
| Seed data | Roles, 8 services, 6 solutions, the 23 Auxtech Ecommerce modules in 4 groups, 11 industries, 32 clients and projects with screenshots, 4 factual FAQs, draft legal pages. No stats, testimonials or prices |

## Verification

| Check | Result |
|---|---|
| Automated tests on SQLite | 173 passed |
| Same suite on MariaDB 10.11 | 172 passed (the full-install test always uses its own SQLite file) |
| Browser smoke tests (Chromium desktop and Pixel 7) | 6 passed: home, content without JavaScript, admin sign-in |
| Code style (Pint), type check, ESLint, production build | Passing |
| Fresh `migrate:fresh --seed` on MariaDB | 32 projects, 23 modules, 32 screenshots |
| **Production zip rehearsal** on Apache + PHP 8.3 + MariaDB, no shell used | See below |

**Zip rehearsal (TC-N13 to TC-N15).** The zip was extracted straight into an Apache document root.

1. Before installing: every URL redirected to `/install`; `/.env`, `/.env.production`, `/vendor/…`, `/storage/…`, `/config/…`, `/app/…`, `/composer.json`, `/artisan` returned 403.
2. A wrong database password produced a plain-language message and left nothing half-installed.
3. Installation through `POST /install` took about 12 seconds; `.env` was written with mode 600.
4. Afterwards: public pages, feeds and the admin panel returned 200, `/install` returned 404, sign-in worked, and a submitted lead was stored.
5. A newer zip was extracted over the installed site: `.env`, the lock file and all uploads were untouched; the admin panel reported one pending database change and applied it with one click.

The rehearsal found three defects that the unit tests could not, all fixed: the cache pointed at the pre-install database during installation, the `notifications` table was missing, and links were forced to HTTPS on an HTTP address.

MariaDB also exposed a case-sensitive search on translated columns, fixed in `App\Support\Search`.

## Deferred

None of these block Phase 2. Each is recorded so it is not forgotten.

| Item | State | Planned for |
|---|---|---|
| Two-factor authentication | Fortify's endpoints are enabled; there is no enforcement rule or screen yet | Phase 2 (screens), then enforce for admin roles |
| Larastan (static analysis) | Not installed: it cannot be downloaded in the build sandbox | First step on a developer machine |
| Content Security Policy header | Other security headers are sent; CSP needs the final script list from Phase 2 | End of Phase 2 |
| Google Tag Manager, Meta Pixel, consent banner | Settings keys exist; the tags are loaded by the frontend | Phase 2 |
| Meta Conversions API | Implemented and called after each lead when a pixel ID and token are saved; not yet verified against Meta | Phase 3 |
| Generated Open Graph images | Not built; pages use their cover image or the site icon | Phase 2 if wanted |
| Standalone media library | Not built; images are managed inside each record | Phase 2 if wanted |
| Page builder blocks | CMS pages have one rich-text body | Later, if needed |
| Careers, newsletter, IndexNow | Not built (all "could have") | Later |
| Cloudflare Turnstile | Not built; honeypot, time trap and rate limits are active | If spam appears |
| Database backup button | Not built; use cPanel's backup | Later |
| Full-page HTML cache | Not built; navigation, settings, redirects and feeds are cached | Phase 3 if load testing asks for it |
| Clean screenshots | Seeded screenshots are research captures; some show promotional popups, and there are no mobile versions | Replace through the admin panel or re-capture |
| Test coverage percentage | Not measured (no coverage driver in the sandbox) | Phase 3 |
| Bangla default texts | Written by the developer; need review by a native speaker | Before launch |

## Notes for Phase 2

- Page components are in `resources/js/pages`; every one currently renders `components/dev/Placeholder.vue`.
- Data shapes are typed in `resources/js/types/models.d.ts`.
- The admin form and list screens are schema-driven: the backend sends `fields`, `columns` and `filters`, so one well-built pair of screens (`Admin/Crud/Index`, `Admin/Crud/Form`) serves all 20 modules.
- Each public page type has a Blade fallback in `resources/views/fallback/site`. When a page's content changes in Phase 2, keep its fallback in step.
