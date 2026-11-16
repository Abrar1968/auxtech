# Phase 3 — Full Site Testing and Launch

**Owner:** Senior QA / Test Engineer
**Specification:** [`../04-TESTING.md`](../04-TESTING.md)
**Starts when:** Phase 2 exit criteria are met and staging is deployed.
**Outcome:** a release that has passed every P1 test case, with evidence, and is live.

## 3.1 Test readiness

- [ ] Staging environment matches production (shared-hosting-like: Apache, PHP 8.2, MySQL/MariaDB, no shell, HTTPS), installed from the release zip
- [ ] `TestSeeder` with deterministic data; staging loaded with real content
- [ ] Traceability sheet: every SRS requirement mapped to at least one `TC-` case
- [ ] E2E job added to CI (Chromium on every push, full matrix nightly)

## 3.2 Backend verification

- [ ] Review Phase 1 coverage; add missing negative and boundary cases
- [ ] Lead pipeline: TC-L01–L13, including failure injection (mail, queue, CAPI down)
- [ ] Authorisation matrix: every role against every admin route (TC-A04)
- [ ] Upload and rich-text abuse cases (TC-A07, TC-A08)
- [ ] Migration and seed on MariaDB 10.4 and MySQL 8, with rollback (TC-N11)

## 3.3 Frontend and end-to-end

- [ ] Journeys: visitor → case study → WhatsApp; visitor → estimator → lead; work filtering; language switch; blog reading
- [ ] Admin journeys: login with 2FA; create and publish a project; handle a lead from new to won
- [ ] Responsive sweep at 320, 360, 768, 1280, 1920 px in EN and BN (TC-U01–U03)
- [ ] Reduced motion, hydration and no-JavaScript checks (TC-U04, TC-U06, TC-P12)
- [ ] Visual regression baseline for key pages in both themes

## 3.4 Non-functional

- [ ] Accessibility: axe on every page; manual keyboard and screen-reader pass (TC-U08, TC-U09)
- [ ] Performance: Lighthouse CI on six key pages; WebPageTest from South Asia on 4G; bundle budget (TC-N01, TC-N02)
- [ ] Load: k6 browse and lead-burst scenarios (TC-N03)
- [ ] Security: headers, CSRF, injection, IDOR, ZAP baseline, dependency audits, production config (TC-N04–N09, TC-N12)
- [ ] Resilience: Pusher unreachable, mail not configured, cache flushed (TC-N10, TC-N16)
- [ ] Release zip: clean install, private paths blocked, update over an existing install (TC-N13–N15)

## 3.5 SEO and AI-visibility verification

- [ ] Head tags, `hreflang`, canonical on every page (TC-S01, TC-S02)
- [ ] Structured data validated automatically and in Google's Rich Results Test (TC-S03, TC-S11)
- [ ] Sitemap, robots, `llms.txt`, feed (TC-S04–S06)
- [ ] Redirects and slug changes (TC-S07)
- [ ] Full-site crawl: no broken links, orphans or duplicate titles (TC-S10)
- [ ] Name, address and phone identical across pages, schema and Google Business Profile

## 3.6 UAT with Auxtech

- [ ] Staff enter a real project, post and testimonial through the admin panel
- [ ] Sales handles a test lead end to end
- [ ] Content review of all pages in both languages; all placeholder text removed
- [ ] Sign-off recorded

## 3.7 Launch

- [ ] Deliver `auxtech-production.zip`; Auxtech extracts it on the hosting account and completes `/install`
- [ ] Production checks: `APP_DEBUG=false`, HTTPS, private paths return 403, backup works, Gmail test email received, Pusher live updates working, Clarity recording
- [ ] Launch-day smoke test (`04-TESTING.md` §10)
- [ ] Search Console and Bing verified; sitemap submitted
- [ ] Monitoring: uptime check, error log alerts, failed-job alerts
- [ ] Test report delivered: cases run, pass rate, open defects, Lighthouse and axe results

## Phase 3 exit criteria

The release criteria in `04-TESTING.md` §4 are met and the launch checklist above is complete.
