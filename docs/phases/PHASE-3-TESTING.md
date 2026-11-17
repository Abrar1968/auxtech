# Phase 3 — Full Site Testing and Launch

**Owner:** Senior QA / Test Engineer
**Specification:** [`../04-TESTING.md`](../04-TESTING.md)
**Status:** testing complete (17 Nov 2026). UAT and launch are with Auxtech: [`../08-UAT-AND-LAUNCH.md`](../08-UAT-AND-LAUNCH.md).
**Results:** [`../07-TEST-REPORT.md`](../07-TEST-REPORT.md) · **Traceability:** [`../06-TRACEABILITY.md`](../06-TRACEABILITY.md)

## 3.1 Test readiness

- [x] Staging matches production: Apache with `.htaccess`, PHP 8.3 (8.2 in CI), MariaDB, production mode, installed from the release zip through `/install`
- [x] Deterministic seed data (`DatabaseSeeder`: 32 projects, services, solutions, industries)
- [x] Traceability: every SRS requirement mapped (`06-TRACEABILITY.md`)
- [x] Browser tests, SEO crawl and migration rollback added to CI (see the CI note below)

## 3.2 Backend verification

- [x] Coverage reviewed: 90.7% → 95.0%; added account and two-factor, Meta Conversions API, Pusher outage, installer and system-tool failures, search on every admin module
- [x] Lead pipeline including failure injection (mail, Meta, Pusher down) and concurrent identical submissions (defect D2)
- [x] Authorisation: role matrix on every admin route, guessed IDs, mass assignment
- [x] Upload and rich-text abuse cases
- [x] Migrations on MariaDB 10.4 (CI) and 10.11 (sandbox) with `migrate:reset` and re-apply (defect D7). MySQL 8 not run

## 3.3 Frontend and end-to-end

- [x] Journeys: case study → WhatsApp, contact form, estimator, work filters, language switch, admin sign-in, create content, edit with validation, settings
- [x] Two-factor sign-in end to end (backend test: set-up, confirm, challenge, recovery code)
- [x] Responsive sweep: 18 pages × 320/360/768/1280/1920 px, English and Bangla (defects D4, D5)
- [x] Reduced motion and no-JavaScript checks
- [ ] Visual regression baseline: not set up; screenshots were reviewed by hand

## 3.4 Non-functional

- [x] Accessibility: axe WCAG 2.2 AA on 18 pages in light and dark (defect D3). Screen-reader pass is a UAT item
- [x] Performance: Lighthouse mobile on six pages in production mode (test report). WebPageTest from South Asia needs the live domain
- [x] Load: `ab` browse and lead-burst scenarios (k6 not available; `ab` used instead)
- [x] Security: CSP with nonces, headers, CSRF, injection, XSS, IDOR, production errors, cookies, `composer audit`, `npm audit`. OWASP ZAP not run
- [x] Resilience: Pusher unreachable, mail failing, Meta failing; lead always kept, failures retryable
- [x] Release zip: clean install, private paths 403, update over an existing install with data kept and stale files removed (defects D1, D6)

## 3.5 SEO and AI-visibility verification

- [x] Head tags, hreflang, canonical on every sitemap URL (SEO crawl, 134 URLs, 0 errors)
- [x] Structured data parsed on every page. Google's Rich Results Test needs the live domain
- [x] Sitemap, robots (defect D1), llms.txt, llms-full.txt, feed
- [x] Redirects and slug changes
- [x] No duplicate titles apart from untranslated Bangla pairs (content to translate)
- [x] Name, address and phone identical across pages and schema. Google Business Profile at launch

## 3.6 UAT with Auxtech

- [ ] Staff enter a real project, post and testimonial
- [ ] Sales handles a test lead end to end
- [ ] Content review in both languages; placeholders removed; Bangla translations added
- [ ] Sign-off recorded

## 3.7 Launch

- [x] `auxtech-production.zip` (1.2.0) built and rehearsed
- [ ] Domain renewed; Auxtech extracts the zip and completes `/install`
- [ ] Production checks and launch-day smoke test (`08-UAT-AND-LAUNCH.md` Part B)
- [ ] Search Console and Bing verified; sitemap submitted
- [ ] Uptime monitor
- [x] Test report delivered

## CI note

CI_NOTE_PLACEHOLDER

## Phase 3 exit criteria

Automated testing is complete and every defect found is fixed. The phase closes when Auxtech signs off the acceptance test and the launch checklist is done.
