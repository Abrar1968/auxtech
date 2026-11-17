# 06 — Requirements Traceability

Every requirement in [`01-SRS.md`](01-SRS.md) mapped to the tests that verify it. Updated at the end of Phase 3 (17 Nov 2026).

**Status:** ✅ automated (runs in CI) · ◐ automated in part, the rest checked by hand or at UAT · ✋ manual (UAT or launch day) · — not built (all are "could have" or recorded as deferred)

**Where the tests are.** Backend (Pest): `tests/Feature/<Area>/<File>Test.php`, written below as `Area/File`. Browser (Playwright): `tests/e2e/<file>.spec.ts`, written as `e2e/<file>`. Unit: `tests/Unit`. Other checks: `scripts/seo-crawl.mjs` (SEO crawl), Lighthouse, `ab` load tests and the release-zip rehearsal, all recorded in [`07-TEST-REPORT.md`](07-TEST-REPORT.md).

## Public pages

| Req | Summary | Status | Verified by |
|---|---|---|---|
| FR-P01 | Home sections, hidden when empty | ✅ | `Site/PagesTest` builds the home page from admin-managed content; `e2e/site` home; `e2e/admin` new stat appears on home |
| FR-P02 | Work index with filters in the URL | ✅ | `Site/PagesTest` filters work; `e2e/site` work filters update the list and the address |
| FR-P03 | Case study page | ✅ | `Site/PagesTest` every public page; `Site/ContentPagesTest` technology on a case study; `e2e/quality` |
| FR-P04 | Services index and detail | ✅ | `Site/PagesTest` every public page (`/services`, `/services/website-development`) |
| FR-P05 | Solutions | ✅ | `Site/PagesTest` every public page |
| FR-P06 | Auxtech Ecommerce: 23 modules in four groups | ✅ | `Site/PagesTest` lists all 23 modules; `e2e/site` module explorer tab |
| FR-P07 | Auxify page | ✅ | `Site/PagesTest` redirects `/auxify` to the solution page |
| FR-P08 | Industries | ✅ | `Site/PagesTest`; `Seo/SeoTest` industry pages get their own description |
| FR-P09 | Pricing | ✅ | `Site/PagesTest`; `Admin/CrudTest` attaches pricing plans |
| FR-P10 | About: story, team, values, map | ✅ | `Site/ContentPagesTest` team on the about page |
| FR-P11 | Blog: index, category, tag, post | ◐ | `Site/PagesTest`; `Site/ContentPagesTest` posts by tag. No posts exist yet; reading a real post is a UAT item |
| FR-P12 | Contact: WhatsApp first, form, map, hours | ✅ | `Site/LeadTest`; `e2e/site` contact form stores a lead and offers WhatsApp |
| FR-P13 | Four-step estimator | ✅ | `Site/LeadTest` stores estimator answers; `e2e/site` estimator walks through four steps |
| FR-P14 | FAQ page | ✅ | `Site/PagesTest`; `Admin/CrudTest` FAQs |
| FR-P15 | Careers | — | Not built (could have) |
| FR-P16 | Legal and custom pages | ✅ | `Site/PagesTest` CMS pages from the top-level URL space |
| FR-P17 | SEO landing pages | ✅ | `Site/PagesTest` landing pages without shadowing real routes |
| FR-P18 | Branded 404, 500, 503 | ◐ | `Site/PagesTest` and `e2e/site` 404; `Security/SecurityTest` production 500 hides details. 503 is the maintenance view, checked by hand |
| FR-P19 | Site search | ✅ | `Site/PagesTest` answers site search; `Security/SecurityTest` injection payloads |

## Public behaviour

| Req | Summary | Status | Verified by |
|---|---|---|---|
| FR-B01 | English and Bangla, switcher, hreflang | ✅ | `Site/PagesTest` both languages and fallback; `Seo/SeoTest` reciprocal hreflang; `e2e/site` Bangla pages |
| FR-B02 | WhatsApp primary CTA, email secondary only | ✅ | `e2e/site` home CTAs and phone action bar; `Site/LeadTest` settings number used everywhere |
| FR-B03 | Pre-filled WhatsApp messages by context | ✅ | `Site/PagesTest` pre-fills WhatsApp messages; `e2e/site` "a site like this" link |
| FR-B04 | Every WhatsApp click recorded | ✅ | `Site/LeadTest` records CTA clicks, click without JavaScript; `e2e/site` beacon before the chat opens |
| FR-B05 | Lead forms: validate, store, notify, WhatsApp hand-off | ✅ | `Site/LeadTest`; `e2e/site` contact form success and invalid phone |
| FR-B06 | First-touch attribution | ✅ | `Site/LeadTest` stores first-touch attribution |
| FR-B07 | Newsletter | — | Not built (could have) |
| FR-B08 | Light and dark theme | ✅ | `e2e/quality` axe scan in both themes |
| FR-B09 | Reduced motion; animations never hide content | ✅ | `e2e/site` reduced motion leaves all content visible |
| FR-B10 | Cookie consent (Consent Mode v2) | ◐ | Checked in the browser on the rehearsal install, including Clarity `consentv2` (see test report). No automated test |
| FR-B11 | Spam protection | ✅ | `Site/LeadTest` honeypot, time trap, throttling; load test lead burst. Turnstile not built (optional) |
| FR-B12 | Bangladeshi phone formats | ✅ | `Unit/PhoneTest`; `Site/LeadTest` every common way of writing a number |

## SEO and AI visibility

| Req | Summary | Status | Verified by |
|---|---|---|---|
| FR-S01 | Content in the HTML without JavaScript | ✅ | `Site/PagesTest` plain HTML for crawlers; `e2e/site` readable without JavaScript; SEO crawl checks every sitemap URL |
| FR-S02 | Title, description, canonical, robots, OG, Twitter | ✅ | `Seo/SeoTest` one title/description/canonical, SEO panel overrides; SEO crawl |
| FR-S03 | JSON-LD types | ◐ | `Seo/SeoTest` valid JSON-LD and page-specific types; SEO crawl parses every page. Google's Rich Results Test needs the public domain (launch) |
| FR-S04 | Sitemap with hreflang, rebuilt on change | ✅ | `Seo/SeoTest` sitemap, rebuilds cached feeds |
| FR-S05 | robots.txt, AI crawlers allowed | ✅ | `Seo/SeoTest` robots.txt; `Seo/FeedsTest` no public file shadows it, old file removed on update |
| FR-S06 | llms.txt and llms-full.txt | ✅ | `Seo/SeoTest` serves llms.txt and llms-full.txt |
| FR-S07 | Redirect manager, automatic 301 on slug change | ✅ | `Seo/SeoTest` redirects in both languages, 301 on slug change; `Admin/CrudTest` manages redirects |
| FR-S08 | Blog feed | ✅ | `Seo/SeoTest` serves the blog feed |
| FR-S09 | Generated Open Graph images | — | Not built; pages use their cover image or the site icon |
| FR-S10 | Core Web Vitals | ◐ | Lighthouse lab runs (test report). Field data needs real visitors after launch |
| FR-S11 | Consistent name, address, phone | ◐ | `Seo/SeoTest` organisation facts on every page; SEO crawl NAP check. Matching Google Business Profile is a launch item |
| FR-S12 | IndexNow | — | Not built (could have) |

## Admin panel

| Req | Summary | Status | Verified by |
|---|---|---|---|
| FR-A01 | Sign-in, 2FA, reset, throttling | ✅ | `Admin/AccessTest`; `Admin/AccountTest` 2FA set-up, challenge, recovery codes, enforcement, password reset. Session list not built |
| FR-A02 | Dashboard | ✅ | `Admin/LeadsAndSettingsTest` numbers match the database; `e2e/admin` dashboard |
| FR-A03 | Projects | ✅ | `Admin/CrudTest` create with translations, images, SEO; soft delete; reorder; `e2e/admin` edit with validation |
| FR-A04 | Clients | ✅ | `Admin/AccessTest` every module renders; `Admin/RobustnessTest` search every module |
| FR-A05 | Services with sub-services | ✅ | `Admin/CrudTest` service cannot be its own parent |
| FR-A06 | Solutions, feature groups, plans | ✅ | `Admin/CrudTest` FAQs and plans on a solution |
| FR-A07 | Industries, technologies | ✅ | `Admin/AccessTest` every module; `Site/ContentPagesTest` technologies |
| FR-A08 | Testimonials | ✅ | `Admin/CrudTest` dynamic home content |
| FR-A09 | Blog | ✅ | `Site/PagesTest` scheduled publishing; `Admin/CrudTest` rich text sanitised |
| FR-A10 | Pages | ◐ | Rich-text CMS pages tested; the block page builder was not built |
| FR-A11 | FAQs | ✅ | `Admin/CrudTest` |
| FR-A12 | Pricing plans | ✅ | `Admin/CrudTest` |
| FR-A13 | Team | ✅ | `Site/ContentPagesTest` team on the about page |
| FR-A14 | Careers | — | Not built (could have) |
| FR-A15 | Leads: list, board, notes, assignment, CSV, spam | ✅ | `Admin/LeadsAndSettingsTest` pipeline, CSV, permissions; `Security/SecurityTest` lead access by role |
| FR-A16 | Media: WebP, sizes, alt text | ◐ | `Admin/CrudTest` replace, re-caption, remove, dangerous uploads rejected. No standalone library (images live in each record) |
| FR-A17 | SEO panel, redirects, missing-meta report | ✅ | `Seo/SeoTest` panel overrides; `Admin/CrudTest` SEO report |
| FR-A18 | Settings | ✅ | `Admin/LeadsAndSettingsTest` saves and applies settings, secrets encrypted; `e2e/admin` settings tabs |
| FR-A19 | Users and roles | ✅ | `Admin/AccessTest` role matrix; `Admin/CrudTest` users; `Security/SecurityTest` guessed IDs |
| FR-A20 | Activity log | ✅ | `Admin/CrudTest` logs who changed what; `Admin/LeadsAndSettingsTest` activity log |
| FR-A21 | Homepage manager | ✅ | `Site/PagesTest` home from admin-managed content |
| FR-A22 | Newsletter | — | Not built (could have) |
| FR-A23 | Web installer | ✅ | `InstallerTest`; `Admin/RobustnessTest` installer failures; `Install/FullInstallTest`; zip rehearsal |
| FR-A24 | System tools | ◐ | `Admin/LeadsAndSettingsTest`, `Admin/RobustnessTest`, `Seo/FeedsTest`; zip rehearsal update. "Backup now" not built: use cPanel backups |
| FR-A25 | Fully dynamic content | ◐ | `Site/PagesTest` home from admin content; empty sections hide. Removing every placeholder is a UAT item |

## Notifications and integrations

| Req | Summary | Status | Verified by |
|---|---|---|---|
| FR-I01 | Lead email to auxtechbd@gmail.com, in-app notification | ◐ | `Site/LeadTest` email sent, failure recorded for retry, in-app notification; `Admin/AccountTest` notifications read. Delivery through real Gmail is a launch item |
| FR-I02 | Telegram or WhatsApp Cloud API alert | — | Not built (could have) |
| FR-I03 | GTM with consent-aware events | ◐ | Click events are tested (FR-B04). GTM preview is a launch item (needs the owner's container) |
| FR-I04 | Meta Pixel and Conversions API with deduplication | ◐ | `Site/IntegrationsTest` event to Meta with hashed details and shared event ID, Meta outage and retry. Meta Events Manager check is a launch item |
| FR-I05 | GA4, Search Console and Bing tags | ✋ | Settings exist; verified at launch |
| FR-I06 | Map loaded on interaction | ✋ | Checked by hand on the contact page |
| FR-I07 | Real-time admin updates (Pusher) | ✅ | `Site/LeadTest` broadcasts; `Site/IntegrationsTest` Pusher outage keeps the lead; `Admin/AccessTest` private channel; live toast checked on the rehearsal install |
| FR-I08 | Microsoft Clarity in production | ✅ | `Site/PagesTest` loads Clarity in production only; recording checked on the rehearsal install |

## Non-functional

| Req | Summary | Status | Verified by |
|---|---|---|---|
| NFR-01 | LCP, INP, CLS at p75 | ◐ | Lighthouse lab: CLS ≤ 0.02 everywhere; LCP see test report. Field data after launch |
| NFR-02 | Lighthouse targets | ◐ | Accessibility 100 and SEO 100 everywhere. Performance and best practices: see test report |
| NFR-03 | Initial JS budget | ◐ | Measured at each build (test report); no automated budget check |
| NFR-04 | Responsive images, hero preload | ✅ | `Admin/CrudTest` conversions; app shell and preload checked in Lighthouse |
| NFR-05 | TTFB ≤ 300 ms (cached) | ◐ | Load test on Apache (test report); depends on the host |
| NFR-06 | 320–2560 px, no horizontal scroll | ✅ | `e2e/quality` 18 pages × 5 widths, clipped header controls |
| NFR-07 | Browsers | ◐ | Chromium (desktop and Android emulation) automated. Firefox, Safari, Samsung Internet by hand at UAT |
| NFR-08 | WCAG 2.2 AA | ◐ | `e2e/quality` axe in light and dark on 18 pages; Lighthouse accessibility 100. Screen-reader pass by hand |
| NFR-09 | Security | ✅ | `Security/SecurityTest` CSP, HSTS, CSRF, injection, XSS, mass assignment, IDOR, errors, cookies; `Admin/CrudTest` uploads and rich text; `composer audit` and `npm audit` |
| NFR-10 | Privacy | ✅ | `Site/LeadTest` never stores a raw IP; consent before tags; leads deletable (`Admin/LeadsAndSettingsTest`) |
| NFR-11 | Lead never lost | ✅ | `Site/LeadTest` email failure; `Site/IntegrationsTest` Meta and Pusher outages; load test (concurrent identical submissions) |
| NFR-12 | Maintainability, coverage ≥ 80% | ◐ | 95% line coverage (pcov); TypeScript type check and ESLint in CI. Larastan could not be installed in the sandbox |
| NFR-13 | Localisation | ✅ | Vitest translation coverage (every interface string has Bangla); `Site/PagesTest` Bangla fallback |
| NFR-14 | Zip and browser installer, no shell | ✅ | `Install/FullInstallTest`; zip rehearsal: install, update over an existing site, private paths 403 |
