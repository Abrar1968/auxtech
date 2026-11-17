# Auxtech Website — Documentation

| # | Document | For | Contents |
|---|---|---|---|
| 00 | [Research](00-RESEARCH.md) | Everyone | Audit of the 32 client projects, positioning, inspiration, search and AI landscape |
| 01 | [SRS](01-SRS.md) | Everyone | Goals, personas, functional and non-functional requirements, site map, open questions |
| 02 | [Backend Architecture](02-BACKEND-ARCHITECTURE.md) | Senior Backend Developer | Stack, structure, data model, routes, lead pipeline, SEO services, security, deployment |
| 03 | [Frontend & UI/UX Design](03-FRONTEND-DESIGN.md) | Senior Frontend Developer, UI/UX Designer | Design language, tokens, page blueprints, motion, conversion design, admin UX, frontend architecture |
| 04 | [Testing](04-TESTING.md) | Senior QA Engineer | Strategy, tools, device matrix, test cases, CI, launch smoke test |
| 05 | [SEO & AI Visibility](05-SEO-GEO-STRATEGY.md) | Everyone, especially marketing | Keyword map, on-site SEO, content plan, off-site work, measurement |
| 06 | [Traceability](06-TRACEABILITY.md) | QA, project owner | Every requirement mapped to the tests that verify it |
| 07 | [Test Report](07-TEST-REPORT.md) | Everyone | Phase 3 results: tests, defects fixed, Lighthouse, load, what was not tested |
| 08 | [Acceptance & Launch](08-UAT-AND-LAUNCH.md) | Auxtech staff | Acceptance test, installing the zip, settings, launch-day checks, after launch |

## Delivery phases

| Phase | Plan | Scope | Status |
|---|---|---|---|
| 0 | — | Research, environment, project setup, documentation | **Done** |
| 1 | [Full Backend](phases/PHASE-1-BACKEND.md) | Data model, admin API, public controllers, leads, SEO feeds, installer, seed data | **Done** |
| 2 | [Full Frontend](phases/PHASE-2-FRONTEND.md) | Design system, public site, admin panel UI, Bangla, performance | **Done** |
| 3 | [Full Testing & Launch](phases/PHASE-3-TESTING.md) | Backend + frontend verification, non-functional testing, UAT, launch | **Testing done**; UAT and launch with Auxtech |

## Research data

- [`research/client-sites.json`](research/client-sites.json) — title, description, navigation and text captured from each client site on 16 Nov 2026
- `resources/seed/portfolio/*.webp` — 1280 px screenshots of all 32 sites (research grade; some show promotional popups and will be re-captured in Phase 1)
