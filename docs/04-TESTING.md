# 04 — Test Strategy and Test Plan

**Role:** Senior QA / Test Engineer
**Implements:** Phase 3 ([`phases/PHASE-3-TESTING.md`](phases/PHASE-3-TESTING.md)); unit and feature tests are written alongside Phases 1 and 2.
**Requirement IDs** refer to [`01-SRS.md`](01-SRS.md).

---

## 1. Objectives

1. Prove every **Must** requirement works on real device classes, in both languages.
2. Prove that **no lead is ever lost** and every WhatsApp click is recorded.
3. Prove SEO output is correct by machine checks, not by eye.
4. Keep regressions out with an automated suite that runs on every push.

## 2. Test levels and tools

| Level | Scope | Tool | Runs | Owner |
|---|---|---|---|---|
| Static analysis | Types, style, obvious bugs | Pint, Larastan (level 6), ESLint, `vue-tsc` | Every push | Dev |
| Unit (PHP) | Actions, services, value objects | Pest | Every push | Backend |
| Feature (PHP) | HTTP endpoints, policies, validation, DB | Pest + Inertia assertions | Every push | Backend |
| Unit (JS) | Composables, utilities, components | Vitest + Vue Test Utils | Every push | Frontend |
| End-to-end | User journeys in a real browser | Playwright | Every push (Chromium), nightly (all projects) | QA |
| Accessibility | WCAG 2.2 AA | `@axe-core/playwright` + manual keyboard/screen-reader pass | Every push + manual per release | QA |
| Visual regression | Key pages and components | Playwright screenshots | Nightly | QA |
| Performance | Core Web Vitals, budgets | Lighthouse CI, WebPageTest (Dhaka/Mumbai, 4G) | Per release | QA |
| SEO | Meta, schema, sitemap, links | Playwright checks, Google Rich Results Test, Schema validator, link crawler | Per release | QA |
| Security | OWASP Top 10 | OWASP ZAP baseline, `composer audit`, `npm audit`, manual checks | Per release | QA + Backend |
| Load | Public pages and lead endpoint | k6 | Before launch | QA |
| UAT | Real content, real staff | Checklist with Auxtech | Before launch | Auxtech |

**Target mix:** roughly 70% unit/feature, 20% component, 10% end-to-end. Coverage targets: ≥ 80% lines on `app/Actions`, `app/Services`, `app/Http`; 100% of lead-pipeline branches.

## 3. Environments

| Environment | Database | Purpose |
|---|---|---|
| Local | MySQL/MariaDB (XAMPP) | Development |
| CI | SQLite in memory **and** MariaDB 10.4 service | Automated suite, compatibility |
| Staging | Same engine and version as production | E2E, performance, UAT |
| Production | MySQL 8 / MariaDB | Smoke tests after deploy, monitoring |

Test data comes from factories and a dedicated `TestSeeder` (deterministic). E2E tests run against a freshly migrated and seeded database. No test depends on live client websites or on WhatsApp itself: we assert the generated `wa.me` URL and the recorded event.

## 4. Entry and exit criteria

**Entry to Phase 3:** Phases 1 and 2 complete; CI green; staging deployed with seed content.

**Exit (release) criteria:**

- 100% of P1 test cases pass; ≥ 95% of P2 pass with no open Critical or High defects.
- Lighthouse mobile: Performance ≥ 90, Accessibility ≥ 95, Best Practices ≥ 95, SEO 100 on Home, Work, Case study, Ecommerce, Blog post, Contact.
- axe: zero serious or critical violations.
- Zero broken internal links; all structured data valid.
- UAT signed off by Auxtech.

## 5. Defect management

| Severity | Definition | Fix target |
|---|---|---|
| Critical | Lead lost, site down, data leak, admin bypass | Immediately; blocks release |
| High | A Must requirement fails, WhatsApp CTA broken, page not indexable | Before release |
| Medium | Feature works with a workaround; layout break on one supported device | Before release if time, else next patch |
| Low | Cosmetic, copy | Backlog |

Each defect records: ID, title, environment, steps, expected vs actual, evidence (screenshot/trace), severity, requirement ID.

## 6. Device and browser matrix

| Class | Devices / viewports | Browsers |
|---|---|---|
| Small Android (priority) | 360×800, 393×851 (Pixel 7), low-end throttled | Chrome, Samsung Internet |
| iPhone | 375×667 (SE), 390×844 (14), 430×932 (Pro Max) | Safari |
| Tablet | 768×1024, 820×1180 | Safari, Chrome |
| Laptop | 1280×800, 1366×768, 1440×900 | Chrome, Edge, Firefox, Safari |
| Large | 1920×1080, 2560×1440 | Chrome |

Playwright projects in `playwright.config.ts` cover Chrome, Firefox, WebKit, Pixel 7, iPhone 14 and iPad. Samsung Internet and real low-end Android are tested manually. Each page is also checked at 320 px width, at 200% zoom, in landscape on phones, in dark theme and in Bangla.

## 7. Test cases

Priority: **P1** blocks release, **P2** should pass, **P3** nice to have. Type: **A** automated, **M** manual.

### 7.1 Leads and WhatsApp (highest risk)

| ID | Case | Expected | Req | Pri | Type |
|---|---|---|---|---|---|
| TC-L01 | Submit contact form with valid data | 201; lead stored with status `new`; notification queued; success state shows WhatsApp button | FR-B05 | P1 | A |
| TC-L02 | Submit with empty/invalid fields | 422 with field errors shown inline; nothing stored | FR-B05 | P1 | A |
| TC-L03 | Phone formats `01805019415`, `+8801805019415`, `8801805019415`, with spaces/dashes | All stored as `+8801805019415`; `0180501941` rejected | FR-B12 | P1 | A |
| TC-L04 | Honeypot filled, or form submitted in under 2 seconds | Response looks successful; no lead stored (or stored as spam) | FR-B11 | P1 | A |
| TC-L05 | 6 submissions in a minute from one IP | 6th returns 429 with a friendly message | FR-B11 | P1 | A |
| TC-L06 | Visit with `?utm_source=facebook&utm_campaign=eid`, browse 3 pages, submit | Lead has UTM values, landing URL and referrer | FR-B06 | P1 | A |
| TC-L07 | Mail server down during submit | Lead still stored; job retried; user sees success | NFR-11 | P1 | A |
| TC-L08 | Same phone submits twice within 24 h | One lead; second message added as a note | FR-A15 | P2 | A |
| TC-L09 | Click each WhatsApp placement (header, hero, FAB, sticky bar, case study, pricing) | `href` is `https://wa.me/8801805019415?text=…`; a `conversion_events` row with the right `placement` exists; `dataLayer` has `whatsapp_click` | FR-B02–B04 | P1 | A |
| TC-L10 | WhatsApp text on a case study, a plan, and a Bangla page | Message names the project/plan; Bangla page sends Bangla text; text is URL-encoded correctly | FR-B03 | P1 | A |
| TC-L11 | Complete the estimator | Steps validate; back keeps answers; lead stored with answers in `meta`; WhatsApp message summarises them | FR-P13 | P2 | A |
| TC-L12 | `/go/whatsapp` with JavaScript disabled | Click recorded; 302 to `wa.me` | FR-B04 | P2 | A |
| TC-L13 | Stored lead never contains a raw IP | `ip_hash` is a 64-char hash | NFR-10 | P1 | A |

### 7.2 Public pages

| ID | Case | Expected | Req | Pri | Type |
|---|---|---|---|---|---|
| TC-P01 | Every public route returns 200 in EN and BN | No 404/500; correct `lang` attribute | FR-P01–P18, FR-B01 | P1 | A |
| TC-P02 | Home renders all enabled sections in order | Sections present; disabled ones absent | FR-P01, FR-A21 | P1 | A |
| TC-P03 | Work filters by industry, solution, service and combinations | Results and counts correct; URL updates; back button restores; empty state shown when none | FR-P02 | P1 | A |
| TC-P04 | Case study shows client, modules, screenshots, related projects | All present; live link opens in new tab with `rel="noopener"` | FR-P03 | P1 | A |
| TC-P05 | Draft or scheduled-in-future content | Not visible publicly; not in sitemap; visible via admin preview | FR-A03 | P1 | A |
| TC-P06 | Demo projects | Labelled "Demo" on cards and case study | SRS §8 | P1 | A |
| TC-P07 | Ecommerce page lists all 23 modules in 4 groups | Counts match seed data | FR-P06 | P1 | A |
| TC-P08 | Language switch on any page | Lands on the same page in the other language; choice persists | FR-B01 | P1 | A |
| TC-P09 | Theme switch | Persists across pages and reloads; no flash of wrong theme | FR-B08 | P2 | A |
| TC-P10 | Blog index pagination, category, tag, post page | Correct posts; reading time; related posts | FR-P11 | P2 | A |
| TC-P11 | Unknown URL | Branded 404 with status 404 and WhatsApp CTA | FR-P18 | P1 | A |
| TC-P12 | JavaScript disabled | Main content, navigation and WhatsApp links present in HTML | FR-S01 | P1 | A |
| TC-P13 | Cookie consent declined | No GA/Meta requests fired; site fully usable | FR-B10 | P1 | A |

### 7.3 SEO and AI visibility

| ID | Case | Expected | Req | Pri | Type |
|---|---|---|---|---|---|
| TC-S01 | Each indexable page | Exactly one `<title>` (≤ 60 chars), one meta description (70–160), one canonical (absolute, self-referencing), one `h1` | FR-S02 | P1 | A |
| TC-S02 | `hreflang` | `en`, `bn` and `x-default` present and reciprocal | FR-B01 | P1 | A |
| TC-S03 | JSON-LD on each page type | Parses; contains expected `@type`s; Organization name is exactly "Auxtech"; address and phone match settings | FR-S03, FR-S11 | P1 | A |
| TC-S04 | `/sitemap.xml` | Valid XML; every published URL present in both languages; no drafts, no `/admin`; all URLs return 200 | FR-S04 | P1 | A |
| TC-S05 | `/robots.txt` | Disallows `/admin`; links sitemap; AI crawlers allowed | FR-S05 | P1 | A |
| TC-S06 | `/llms.txt`, `/llms-full.txt` | 200, `text/plain`; contain company name, services, address, key links | FR-S06 | P2 | A |
| TC-S07 | Change a slug in admin | Old URL 301s to the new one; no redirect chains | FR-S07 | P1 | A |
| TC-S08 | Open Graph and Twitter tags | Present; `og:image` returns 200 and is 1200×630 | FR-S02, FR-S09 | P2 | A |
| TC-S09 | Admin and preview pages | `noindex` header/meta | NFR-09 | P1 | A |
| TC-S10 | Crawl the whole site | No broken internal links, no orphan pages, no duplicate titles | — | P1 | A |
| TC-S11 | Google Rich Results Test on Home, Service, Case study, Post, FAQ | No errors | FR-S03 | P1 | M |
| TC-S12 | Images | Every `<img>` has `alt`, `width`, `height`; below-the-fold images lazy | NFR-04 | P1 | A |

### 7.4 Admin

| ID | Case | Expected | Req | Pri | Type |
|---|---|---|---|---|---|
| TC-A01 | Login with valid, invalid, and 6 rapid attempts | Success; error; throttled | FR-A01 | P1 | A |
| TC-A02 | 2FA enable, login with code, recovery code | Works; Admin roles cannot skip 2FA | FR-A01 | P1 | A |
| TC-A03 | Guest opens any `/admin` URL | Redirect to login | FR-A01 | P1 | A |
| TC-A04 | Each role opens each module | Access matches the role table in doc 02 §8; forbidden returns 403 | FR-A19 | P1 | A |
| TC-A05 | CRUD for every content module (create, edit EN + BN, publish, unpublish, delete, restore) | Reflected on the public site; cache invalidated | FR-A03–A14 | P1 | A |
| TC-A06 | Reorder projects by drag and drop | Order persists and shows on the site | FR-A03 | P2 | A |
| TC-A07 | Upload JPEG/PNG/WebP; upload `.php`, oversized file, fake extension | Images converted with responsive sizes; invalid files rejected | FR-A16 | P1 | A |
| TC-A08 | Rich text containing `<script>` and `onerror` | Stripped on save; not executed on site | NFR-09 | P1 | A |
| TC-A09 | Leads: filter, change status, assign, add note, export CSV, mark spam | All work; activity logged; CSV opens correctly with Bangla text | FR-A15 | P1 | A |
| TC-A10 | Settings: change WhatsApp number | All CTAs use the new number immediately | FR-A18 | P1 | A |
| TC-A11 | SEO panel | Preview updates live; length warnings; saved values appear in page HTML | FR-A17 | P1 | A |
| TC-A12 | Schedule a post for the future | Publishes automatically at the set time | FR-A09 | P2 | A |
| TC-A13 | Dashboard numbers | Match database counts for the period | FR-A02 | P2 | A |
| TC-A14 | Activity log | Records user, action, before/after | FR-A20 | P2 | A |

### 7.5 UI, responsive and motion

| ID | Case | Expected | Req | Pri | Type |
|---|---|---|---|---|---|
| TC-U01 | Every page at 320, 360, 768, 1280, 1920 px | No horizontal scroll, no overlap, no clipped text (EN and BN) | NFR-06 | P1 | A + M |
| TC-U02 | Touch targets on mobile | ≥ 44×44 px | NFR-06 | P1 | A |
| TC-U03 | Sticky mobile bar and floating button | Never cover form fields, consent banner or footer links; respect safe area | FR-B02 | P1 | M |
| TC-U04 | `prefers-reduced-motion` | No parallax, pinning or marquee; content fully visible | FR-B09 | P1 | A |
| TC-U05 | Scroll through Home on a throttled low-end profile | No frozen frames > 200 ms; no layout shift from animations | NFR-01 | P1 | M |
| TC-U06 | Hydration | No hydration mismatch warnings in the console on any page | — | P1 | A |
| TC-U07 | Visual regression of key pages, both themes | Within threshold | — | P2 | A |
| TC-U08 | Keyboard-only walkthrough: nav, mega menu, filters, carousel, accordion, forms, modal | Everything reachable; focus visible; focus trapped in modals; Escape closes | NFR-08 | P1 | M |
| TC-U09 | Screen reader (NVDA, VoiceOver) on Home and Contact | Landmarks, headings, labels and errors announced | NFR-08 | P2 | M |
| TC-U10 | Bangla rendering | Conjuncts render correctly; no fallback fonts; numerals as designed | NFR-13 | P1 | M |

### 7.6 Performance, security, reliability

| ID | Case | Expected | Req | Pri | Type |
|---|---|---|---|---|---|
| TC-N01 | Lighthouse CI on six key pages (mobile) | Thresholds in §4 | NFR-02 | P1 | A |
| TC-N02 | Bundle size check in CI | Public entry ≤ 170 kB gzip | NFR-03 | P1 | A |
| TC-N03 | k6: 100 virtual users browsing for 5 minutes; 20 leads/second burst | p95 < 800 ms; error rate < 1%; throttling works; no lost leads | NFR-05, NFR-11 | P2 | A |
| TC-N04 | Security headers | CSP, HSTS, `X-Content-Type-Options`, `Referrer-Policy` present; rated A on securityheaders.com | NFR-09 | P1 | A |
| TC-N05 | POST without CSRF token | 419 | NFR-09 | P1 | A |
| TC-N06 | SQL injection and XSS payloads in search, filters and forms | Safely handled; no error leakage | NFR-09 | P1 | A |
| TC-N07 | Access another user's resource by ID; mass-assign `status`, `role` fields | 403 / ignored | NFR-09 | P1 | A |
| TC-N08 | OWASP ZAP baseline scan on staging | No High findings | NFR-09 | P1 | A |
| TC-N09 | `composer audit`, `npm audit --omit=dev` | No known high-severity vulnerabilities | NFR-09 | P1 | A |
| TC-N10 | SSR process stopped | Pages still load (client-rendered); title, meta and JSON-LD still in HTML | doc 02 §11 | P1 | A |
| TC-N11 | Migrate fresh + seed on MariaDB 10.4 and MySQL 8; rollback | All succeed | doc 02 §4.4 | P1 | A |
| TC-N12 | Production config | `APP_DEBUG=false`; no stack traces; `.env` and `/storage` not web-accessible | NFR-09 | P1 | M |

## 8. Automation layout

```
tests/
├── Unit/                 Pure PHP: Actions, Services, Support
├── Feature/
│   ├── Site/             Public pages, leads, events, feeds
│   ├── Admin/            Auth, authorisation matrix, CRUD per module
│   └── Seo/              Meta, schema, sitemap, robots, redirects
├── e2e/
│   ├── journeys/         visitor-to-whatsapp, estimator, work-filter, language-switch
│   ├── admin/            login-2fa, project-crud, lead-workflow
│   ├── seo/              head-tags, structured-data, sitemap-crawl
│   ├── a11y/             axe scan per page
│   └── visual/           screenshots
resources/js/**/*.test.ts Component and composable tests (Vitest)
```

Naming: Pest tests read as sentences (`it('stores a lead before sending notifications')`). Each automated test references its `TC-` ID in a comment or tag for traceability.

## 9. CI pipeline

`.github/workflows/ci.yml` runs on every push and pull request:

1. **Backend:** Pint → Pest on SQLite → Pest on MariaDB 10.4.
2. **Frontend:** ESLint → `vue-tsc` → Vitest → production build (client + SSR).
3. **E2E (added in Phase 3):** start app + SSR → Playwright (Chromium) → axe; nightly: full matrix + visual + Lighthouse CI.

A red pipeline blocks merging. Tests are never skipped or disabled to get a green build.

## 10. Launch-day smoke test (production)

1. Home, Work, one case study, Ecommerce, Contact load in EN and BN over HTTPS.
2. Submit a real test lead → appears in admin → email received → mark as test.
3. Tap WhatsApp on a phone → chat opens with the correct number and message.
4. `/sitemap.xml`, `/robots.txt`, `/llms.txt` return 200.
5. View source on Home → title, description, canonical, JSON-LD present.
6. Submit sitemap in Google Search Console and Bing Webmaster Tools; request indexing for Home.
7. GTM preview shows `whatsapp_click` and `generate_lead` events; Meta Events Manager shows deduplicated Pixel + CAPI events.

## 11. Current status

| Item | Status |
|---|---|
| Pest configured, 3 environment tests | Passing |
| Playwright configured with 6 device projects, 1 smoke spec | Passing on Chromium desktop and Pixel 7 |
| Vitest, ESLint, `vue-tsc`, Pint configured | Passing |
| Larastan | **Not installed yet** (see root `README.md`, "Known gaps"); install as the first step of Phase 1 |
| Firefox and WebKit browsers | Install locally with `npx playwright install` |
