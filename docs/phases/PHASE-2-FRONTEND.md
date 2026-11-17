# Phase 2 — Full Frontend

**Owner:** Senior Frontend Developer with the UI/UX Designer
**Specification:** [`../03-FRONTEND-DESIGN.md`](../03-FRONTEND-DESIGN.md)
**Status:** complete (17 Nov 2026), with the gaps listed at the end.

## What was built

| Area | Delivered |
|---|---|
| Design system | Tokens in `resources/css/app.css` (brand scale from #0145FF, theme-aware surfaces, glass, aurora, grid, Bangla typography), `Button`, `Reveal`, `SectionHeading`, `AppImage`, `Icon`, motion helpers |
| Site shell | Header (glass on scroll, hides on scroll down, mega menus, language and theme switch), full-screen mobile menu, footer, floating WhatsApp button with one-time hint, phone action bar (WhatsApp, email, estimate), consent banner loading GTM and Meta Pixel with Consent Mode v2, Lenis smooth scrolling on pointer devices, client-side SEO tag sync |
| Home | Sections in the order set in Settings; hero with stacked client screenshots and live module chips, client marquee, stats, services bento, module explorer for the 23 modules, pinned horizontal work showcase, solutions, scroll-drawn process, testimonials, reasons, pricing and estimator band, posts, FAQ, closing CTA. Empty sections hide themselves |
| Inner pages | Work (animated filters, shareable URLs), case study, services, service detail, solutions, solution detail, industries, pricing, about, FAQ, contact (WhatsApp first, lead form), four-step estimator, blog index and article (reading progress, table of contents), CMS page, SEO landing page, branded error pages |
| Conversion | Every WhatsApp and email click is recorded (`sendBeacon`) with its placement; WhatsApp messages are pre-filled per page and language; lead form with inline validation, spam traps and a WhatsApp hand-off; Meta Pixel `Lead` event shares its ID with the server-side Conversions API call |
| Bangla | Every interface string translated in `lang/bn.json` (also used by the server fallback); Bangla digits and dates; a test fails if a string is missing |
| Admin | Sidebar layout, notifications, live lead toasts with sound over Pusher, schema-driven list and form screens for all 20 content types (English/Bangla tabs, rich text, images with alt text, galleries, repeaters, search-appearance panel, drag ordering, trash, unsaved-changes guard, Ctrl/Cmd+S), dashboard with KPIs, charts and live click feed, leads table, pipeline board and detail, tabbed settings, system tools, activity log, SEO report, account page with password change and two-factor setup, sign-in screens |
| Admin-editable copy | Added settings for every home-section heading, listing-page headings, process steps, reasons, about story and the closing call to action, so no public text is fixed in code apart from interface labels |

## Verification

| Check | Result |
|---|---|
| Backend tests | 174 passed on SQLite, 173 on MariaDB |
| Frontend unit tests (Vitest) | 5 passed: translations complete, icons complete, i18n helpers |
| Browser tests (Playwright, Chromium desktop and Pixel 7) | 29 passed: home, WhatsApp click recording, work filters, contact form success and validation, estimator, language switch, no-JavaScript content, 404, reduced motion, phone menu and action bar, admin sign-in, dashboard, create stat, edit project with validation, settings |
| Type check, lint, Pint, production build | Clean |
| Lighthouse, mobile, through Apache with compression | See below |
| Production zip | Rebuilt and installed on Apache + MariaDB through `/install`; every page returned 200, private files 403 |
| Visual review | Desktop, phone and tablet; light and dark; English and Bangla |

**Lighthouse (mobile preset: slow 4G, 4x CPU slowdown).**

| Page | Performance | Accessibility | Best practices | SEO |
|---|---|---|---|---|
| Home | 82–83 | 100 | 100 | 100 |
| Work | 81–85 | 100 | 100 | 100 |
| Case study | 90–91 | 100 | 100 | 100 |
| Auxtech Ecommerce | 88–89 | 100 | 100 | 100 |
| Contact | 94–95 | 100 | 100 | 100 |
| Home (Bangla) | 72–74 | 100 | 100 | 100 |

Cumulative layout shift is 0 everywhere. JavaScript on the home page is about 190 kB compressed.

## Gaps against the plan

| Item | State |
|---|---|
| Performance 90+ on every page | Not met on Home, Work and Bangla pages. The page is drawn by JavaScript because the host cannot run Node (no SSR), so the largest paint waits for the bundle (about 4 s in Lighthouse's slow-4G simulation). Bangla pages also download a 152 kB font. Options: a full-page HTML cache that pre-renders pages, a subset Bangla font, or trimming the animation library from the hero |
| GSAP | Not used. Scroll effects (pinned showcase, drawn timeline, parallax) are built with `motion-v`, which was already needed; GSAP was removed |
| Style guide page | Not built |
| Command palette in the admin | Not built |
| Firefox, Safari and Samsung Internet | Not tested in this environment (only Chromium is installed); Phase 3 |
| Screen-reader pass | Not done; automated accessibility checks pass |
| Admin panel language | English only, as staff tooling |
| Two-factor enforcement | Available as a setting (off by default); the dashboard reminds users who have not set it up |
