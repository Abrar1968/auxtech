# 07 — Test Report (Phase 3)

**Release tested:** 1.2.0 · **Date:** 17 Nov 2026 · **Tester:** Senior QA (Phase 3)
**Plan:** [`04-TESTING.md`](04-TESTING.md) · **Traceability:** [`06-TRACEABILITY.md`](06-TRACEABILITY.md)

## Summary

The site is ready for user acceptance testing and launch. Every automated check passes. Phase 3 found **eight defects**, all fixed and covered by new tests (below). What remains needs the real domain, the owner's accounts or a person: delivery to Gmail, Meta Events Manager, Google Search Console, other browsers, a screen-reader pass, and content entry.

| Area | Result |
|---|---|
| Backend tests (Pest) | **216 passed** on SQLite and on MariaDB 10.4–10.11; 0 skipped |
| Line coverage (pcov) | **95.0%** (Phase 2: 90.7%) |
| Frontend unit tests (Vitest) | 5 passed: every interface string has Bangla, icons in step, i18n helpers |
| Browser tests (Playwright, Chromium) | **83 passed**: 29 journeys on desktop and Android, 18 pages × 5 widths without sideways scrolling, axe WCAG 2.2 AA in light and dark on 18 pages |
| SEO crawl (`scripts/seo-crawl.mjs`) | 134 sitemap URLs, **0 errors**; 19 content warnings (below) |
| Security | CSP with nonces, headers, CSRF, injection, XSS, IDOR, mass assignment, error pages: all pass. `composer audit`: 0. `npm audit` for shipped packages: 0 |
| Load (`ab`, Apache + MariaDB, 20 concurrent users) | 0 failed requests; lead burst limited correctly with no errors after the deadlock fix |
| Release zip | 73 MB. Clean install through `/install`, update over an existing site, private paths 403: pass |
| Production-mode browser check | No CSP violations or console errors; Clarity records; Pusher live updates arrive in the admin |

## Defects found and fixed in Phase 3

| # | Severity | Defect | Fix | Test |
|---|---|---|---|---|
| D1 | High | Laravel's stock `public/robots.txt` was served by Apache instead of the generated one, so the sitemap link, the `/admin` and `/install` rules and the AI-crawler rules never reached crawlers | File removed; the Admin > System database update deletes it from existing installs | `Seo/FeedsTest`: no public file may shadow a route; update removes old files |
| D2 | High | Two identical lead submissions at the same moment (a double tap) deadlocked in MariaDB: one visitor got an error and duplicate leads were possible | The existing lead row is locked and the transaction retried; 5 simultaneous submissions now give 1 lead and 4 notes | Load test burst (repeated 6 times) |
| D3 | Medium | Dark theme: brand-blue text failed WCAG contrast on 13 pages | Theme-aware `text-link` colour (#0145FF light, #8EB4FF dark) | `e2e/quality` axe scan, dark theme |
| D4 | Medium | At 320 px the menu button sat 20 px off-screen on every page | Smaller logo and tighter spacing below 360 px | `e2e/quality` clipped header controls |
| D5 | Low | At 320 px a long URL widened the work grid by 7 px (sideways scrolling) | Explicit single-column grid on phones (work and blog) | `e2e/quality` overflow sweep |
| D6 | Medium | 169 MB of local Inertia debugging files and an empty test folder went into the release zip | Excluded in `build-release.sh`; storage ships as empty folders | Zip contents checked |
| D7 | Low | The media table could not be rolled back (`migrate:reset` left it behind and a fresh migrate then failed) | `down()` added | CI step: reset and re-apply on MariaDB 10.4 |
| D8 | Low | All 11 industry pages shared the home page's meta description | Generated, translated description when none is written | `Seo/SeoTest` |

Earlier in Phase 3 (already committed): a nonce-based Content Security Policy replaced the plain header set, and the server now prints the page hero before JavaScript runs (Lighthouse performance on the home page rose from 82 to 98 in local mode).

## Lighthouse (mobile preset: slow 4G, 4× CPU)

Measured on the production install (Apache, `APP_ENV=production`, Clarity on), 17 Nov 2026.

| Page | Performance | Accessibility | Best practices | SEO | LCP | CLS |
|---|---|---|---|---|---|---|
| Home | 88–90 | 100 | 77 | 100 | 0.6–0.7 s | 0 |
| Work | 82–84 | 100 | 77 | 100 | 4.2 s | 0 |
| Case study | 99 | 100 | 77 | 100 | 0.6 s | 0 |
| Auxtech Ecommerce | 95 | 100 | 77 | 100 | 0.6 s | 0 |
| Contact | 90–95 | 100 | 77 | 100 | 2.8–3.2 s | 0 |
| Home (Bangla) | 90 | 100 | 77 | 100 | 0.8 s | 0.018 |

- **Best practices 77** is caused only by Microsoft Clarity's cookies (third-party cookie audit). Without Clarity (local mode) every page scores 100. Turning Clarity's cookie setting off (see launch checklist) removes the cookies until a visitor accepts the consent banner.
- **Clarity also costs about 320 ms of main-thread time** on the simulated phone. Loading it later was tried and made no measurable difference, so the standard snippet is kept (it records the whole visit).
- **Work page LCP 4.2 s:** the largest element is the intro paragraph, which is drawn again in the web font when the page starts and measures larger than the server-printed copy. Recorded rather than tuned further; the page is visible at 0.6 s.
- **JavaScript:** home 192 kB compressed (budget 170 kB), work 152 kB, contact 148 kB, plus 26 kB for Clarity.

## Load test

ApacheBench, 400 requests per page, 20 concurrent, keep-alive, against Apache + MariaDB in this sandbox (not the real host):

| Page | Failed | Requests/s | 95th percentile |
|---|---|---|---|
| `/` | 0 | 49 | 552 ms |
| `/work` | 0 | 42 | 593 ms |
| `/work/gearzen` | 0 | 73 | 334 ms |
| `/bn` | 0 | 47 | 541 ms |
| `/sitemap.xml` | 0 | 75 | 303 ms |

Lead burst (30 submissions, 10 at a time, one address): 5 accepted and the rest answered 429 (the limit is 5 a minute and 30 a day per address). After fix D2: no server errors.

Shared hosting will differ; the numbers show the application has no errors under concurrency, not the host's capacity.

## SEO crawl warnings (content, not code)

- 46 Bangla pages repeat the English title or description because that content has not been translated yet (hreflang links each pair). Translate in the admin.
- A few project descriptions are shorter than 50 characters, and one project title is 70 characters. Edit in the admin.

## Not tested, and why

| Item | Reason | Who and when |
|---|---|---|
| Email arriving in auxtechbd@gmail.com | Needs the Google app password, entered in the installer or Settings | Owner, launch day ("Send a test email") |
| Meta Conversions API against Meta | Needs the Pixel ID and access token | Owner, after launch (Events Manager) |
| GTM and GA4 events | Needs the owner's GTM container | Owner, after launch |
| Rich Results Test, Search Console, Bing | Need the public domain | Launch day |
| Firefox, Safari, Samsung Internet, real iPhone | Only Chromium is available in the test sandbox | UAT on staff phones and laptops |
| Screen reader (TalkBack, VoiceOver) | Manual | UAT |
| Real hosting performance and HTTPS | The domain auxtechbd.com has expired | After renewal |
| CI browser-test job | Added in Phase 3; see the CI note in `PHASE-3-TESTING.md` | — |
