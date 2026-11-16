# 01 — Software Requirements Specification (SRS)

**Project:** Auxtech company website and admin panel
**Version:** 1.0 (16 Nov 2026)
**Audience:** everyone on the project. Role-specific detail lives in documents 02–04.

---

## 1. Purpose and goals

Build Auxtech's public website: a bilingual, animated, search-optimised portfolio that turns visitors into WhatsApp conversations, with an admin panel that lets non-developers manage every piece of content.

| # | Business goal | How we measure it |
|---|---|---|
| G1 | Generate organic leads | WhatsApp click-through rate ≥ 5% of sessions; leads per week tracked in admin |
| G2 | Rank for high-intent searches in Bangladesh | Page-one positions for the target keyword set in `05-SEO-GEO-STRATEGY.md` within 6 months |
| G3 | Be extractable and citable by AI assistants | Valid structured data on 100% of public pages; `llms.txt` served; facts consistent everywhere |
| G4 | Present 32 projects so a new visitor trusts Auxtech in under 30 seconds | Work section reachable in one click; ≥ 40% of sessions view at least one project |
| G5 | Let staff run the site without a developer | All content in section 5 is editable in the admin panel |

## 2. Users

| Persona | Who | What they need |
|---|---|---|
| **Merchant Mahin** | F-commerce seller moving off Facebook-only sales; mobile, Bangla-first, price-sensitive | "How much, how fast, show me a shop like mine", then WhatsApp |
| **Brand owner Nusrat** | Established fashion or gadget brand with a weak site | Proof in her industry, POS/inventory/courier features, a demo |
| **Founder Farhan** | Wants a custom platform (reseller, LMS, SaaS) | Evidence of complex builds, process, tech stack |
| **Corporate officer** | Needs a company website or internal tool | Credibility, address, formal contact route |
| **Marketing lead** | Needs ads, Pixel/CAPI, SEO | Digital marketing services and results |
| **Admin / editor / sales** | Auxtech staff | Manage content, respond to leads |
| **Crawlers** | Googlebot, Bingbot, GPTBot, ClaudeBot, PerplexityBot | Server-rendered HTML, structured data, sitemap |

## 3. Scope

**In scope:** public website (EN + BN), admin panel, lead capture and CRM-lite, blog, SEO/GEO tooling, analytics integrations, automated tests.

**Out of scope for v1:** client login portal, online payment, live chat widget other than WhatsApp, native mobile app, multi-tenant features.

## 4. Technology (fixed)

| Layer | Choice |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Database | MySQL 8 / MariaDB 10.4+ (XAMPP locally) |
| Bridge | Inertia.js v3 with **server-side rendering** |
| Frontend | Vue 3 (Composition API, TypeScript), Tailwind CSS v4 |
| Motion | Motion for Vue (`motion-v`, the Vue port of Framer Motion), GSAP ScrollTrigger, Lenis |
| Tests | Pest, Vitest, Playwright |

Rationale and alternatives are in documents 02 and 03.

## 5. Functional requirements

Priority: **M** = must (launch blocker), **S** = should, **C** = could.

### 5.1 Public site — pages

| ID | Requirement | Pri |
|---|---|---|
| FR-P01 | **Home**: hero, client logos, stats, services, flagship product explorer, featured work, other solutions, process, testimonials, pricing teaser, blog teaser, FAQ, closing CTA | M |
| FR-P02 | **Work index**: all published projects; filter by industry, solution and service without page reload; URL reflects filters; filter counts shown | M |
| FR-P03 | **Case study page**: client, industry, live link, problem → solution → result, modules used, tech stack, desktop + mobile screenshots, testimonial, related projects, CTA | M |
| FR-P04 | **Services index + detail** for Website Development, App Development, Digital Marketing, plus sub-services (e.g. E-commerce development, SEO, Meta ads) | M |
| FR-P05 | **Solutions**: product-style pages for Auxtech Ecommerce, Reseller/Dropshipping Platform, LMS, POS, Corporate Website, Custom Software | M |
| FR-P06 | **Auxtech Ecommerce page**: all 23 modules in four groups, interactive explorer, live demo links, stores built on it, pricing, FAQ | M |
| FR-P07 | **Auxify page**: SaaS overview, plans, link to auxify.store | M |
| FR-P08 | **Industries**: Fashion, Gadgets & Electronics, Food & Grocery, General Retail, Education, Travel, Corporate; each lists matching projects | S |
| FR-P09 | **Pricing**: packages per solution, managed in admin; "from ৳" pricing or "Get a quote" per plan | S |
| FR-P10 | **About**: story, team, values, office, map | M |
| FR-P11 | **Blog**: index, category, tag, post; reading time, table of contents, author box, related posts, share links | M |
| FR-P12 | **Contact**: WhatsApp first, form, phone, address, map, office hours | M |
| FR-P13 | **Project estimator**: 4-step wizard (type → features → timeline/budget → contact) ending in a pre-filled WhatsApp message and a stored lead | S |
| FR-P14 | **FAQ** page aggregating FAQs by topic | S |
| FR-P15 | **Careers**: openings and application form with CV upload | C |
| FR-P16 | **Legal** pages (privacy, terms) and any custom page built from content blocks | M |
| FR-P17 | **SEO landing pages** for service × industry × location combinations, each with unique content | S |
| FR-P18 | Branded **404 / 500 / 503** pages with navigation and WhatsApp CTA | M |
| FR-P19 | **Site search** across projects, services, solutions and posts | C |

### 5.2 Public site — behaviour

| ID | Requirement | Pri |
|---|---|---|
| FR-B01 | **Bilingual**: English at `/`, Bangla at `/bn/...`; switcher keeps the current page; `hreflang` on every page | M |
| FR-B02 | **WhatsApp CTA** in header, hero, every section end, floating button, and sticky bottom bar on mobile | M |
| FR-B03 | WhatsApp messages are **pre-filled by context** (page, service, project, plan, language); templates editable in admin | M |
| FR-B04 | Every WhatsApp click is **recorded** (placement, page, UTM, device) before the user leaves | M |
| FR-B05 | **Lead forms** (contact, callback, demo request, estimator) validate inline, store the lead, notify staff, then offer "continue on WhatsApp" | M |
| FR-B06 | First-touch **attribution**: UTM parameters, referrer and landing page are stored with each lead | M |
| FR-B07 | **Newsletter** signup with double opt-in | C |
| FR-B08 | **Light and dark theme**, following the system and switchable; choice remembered | S |
| FR-B09 | Animations respect `prefers-reduced-motion` and never block content | M |
| FR-B10 | **Cookie consent** controlling analytics and marketing tags (Google Consent Mode v2) | M |
| FR-B11 | Spam protection: honeypot, time trap, rate limit, optional Cloudflare Turnstile | M |
| FR-B12 | Phone numbers accept Bangladeshi formats (`01XXXXXXXXX`, `+8801XXXXXXXXX`) and are normalised | M |

### 5.3 SEO and AI visibility

| ID | Requirement | Pri |
|---|---|---|
| FR-S01 | All public pages are **server-rendered**; content is present in the HTML response | M |
| FR-S02 | Per-page title, description, canonical, robots, Open Graph and Twitter tags; editable in admin with sensible generated defaults | M |
| FR-S03 | **JSON-LD**: Organization + LocalBusiness (site-wide), WebSite, BreadcrumbList, Service, Product/SoftwareApplication, CreativeWork (case studies), Article, FAQPage, JobPosting | M |
| FR-S04 | `sitemap.xml` (index + per-type, with `hreflang` and image entries), regenerated on content change | M |
| FR-S05 | `robots.txt` managed in admin; AI crawlers explicitly allowed | M |
| FR-S06 | `llms.txt` and `llms-full.txt` generated from live content | S |
| FR-S07 | **Redirect manager** (301/302) and automatic redirect when a slug changes | M |
| FR-S08 | RSS/Atom feed for the blog | S |
| FR-S09 | Generated **Open Graph images** per page (title + brand) when none is uploaded | S |
| FR-S10 | Core Web Vitals targets met (section 6.1) | M |
| FR-S11 | Consistent name, address and phone on every page, matching Google Business Profile | M |
| FR-S12 | IndexNow ping on publish | C |

### 5.4 Admin panel

| ID | Module | Requirement | Pri |
|---|---|---|---|
| FR-A01 | Auth | Email + password login, 2FA (TOTP), password reset, login throttling, session list | M |
| FR-A02 | Dashboard | Leads today/7d/30d, WhatsApp clicks by placement, conversion rate, top pages, lead sources, recent activity | M |
| FR-A03 | Projects | CRUD, EN/BN fields, cover/desktop/mobile/gallery images, metrics, modules used, tech, client, industry, featured flag, drag-to-order, draft/scheduled/published, preview | M |
| FR-A04 | Clients | CRUD, logo, site URL, industry, show-in-marquee flag | M |
| FR-A05 | Services | CRUD with nested sub-services, features, process steps, FAQs | M |
| FR-A06 | Solutions | CRUD, feature groups and features, demo links, plans | M |
| FR-A07 | Industries, technologies | CRUD | M |
| FR-A08 | Testimonials | CRUD, rating, photo, link to client/project, featured | M |
| FR-A09 | Blog | Posts, categories, tags, rich editor, scheduled publish, author | M |
| FR-A10 | Pages | Block-based page builder for custom and legal pages | S |
| FR-A11 | FAQs | CRUD, attach to any page/service/solution, ordering | M |
| FR-A12 | Pricing plans | CRUD per solution/service | S |
| FR-A13 | Team | CRUD, ordering | S |
| FR-A14 | Careers | Openings CRUD, applications inbox | C |
| FR-A15 | **Leads** | List + kanban by status, filters, detail with timeline, notes, assignment, one-click WhatsApp/call, CSV export, spam marking | M |
| FR-A16 | Media library | Upload, auto WebP/AVIF + responsive sizes, alt text (EN/BN), search, usage | M |
| FR-A17 | SEO | Per-entity SEO panel with SERP preview and length checks; redirects; robots editor; sitemap status; missing-meta report | M |
| FR-A18 | Settings | Company info, contact, hours, socials, WhatsApp number and templates, tracking IDs (GTM, GA4, Meta Pixel, CAPI token), homepage stats, mail, maintenance mode | M |
| FR-A19 | Users & roles | Users CRUD, roles (Super Admin, Admin, Editor, Sales), granular permissions | M |
| FR-A20 | Activity log | Who changed what and when, with before/after | S |
| FR-A21 | Homepage manager | Reorder and toggle home sections, edit hero copy | S |
| FR-A22 | Newsletter | Subscribers list, export | C |

### 5.5 Notifications and integrations

| ID | Requirement | Pri |
|---|---|---|
| FR-I01 | New lead → email to sales address; in-app notification in admin | M |
| FR-I02 | New lead → optional Telegram or WhatsApp Cloud API message to the sales team | C |
| FR-I03 | GTM container with consent-aware `dataLayer` events (`whatsapp_click`, `generate_lead`, `estimator_step`, `demo_click`) | M |
| FR-I04 | Meta Pixel + **server-side Conversions API** `Lead` and `Contact` events with deduplication | S |
| FR-I05 | GA4 via GTM; Search Console and Bing verification tags from settings | M |
| FR-I06 | Google Maps embed loaded only on interaction | S |

## 6. Non-functional requirements

### 6.1 Performance

| ID | Requirement |
|---|---|
| NFR-01 | LCP ≤ 2.0 s, INP ≤ 200 ms, CLS ≤ 0.05 at the 75th percentile on a mid-range Android phone over 4G |
| NFR-02 | Lighthouse (mobile): Performance ≥ 90, Accessibility ≥ 95, Best Practices ≥ 95, SEO = 100 |
| NFR-03 | Initial JS for a public page ≤ 170 kB gzip; animation libraries loaded on demand |
| NFR-04 | Images served as AVIF/WebP with `srcset`, explicit dimensions and lazy loading; hero image preloaded |
| NFR-05 | Server response (TTFB) ≤ 300 ms for cached public pages |

### 6.2 Other qualities

| ID | Area | Requirement |
|---|---|---|
| NFR-06 | Responsive | Works from 320 px to 2560 px; touch targets ≥ 44 px; no horizontal scroll |
| NFR-07 | Browsers | Last 2 versions of Chrome, Edge, Firefox, Safari; Samsung Internet; iOS Safari 16+ |
| NFR-08 | Accessibility | WCAG 2.2 AA: contrast, keyboard access, focus states, landmarks, alt text |
| NFR-09 | Security | OWASP Top 10 addressed; CSP, HSTS, secure cookies; admin behind auth + 2FA; uploads validated; rich text sanitised |
| NFR-10 | Privacy | IP addresses stored hashed; consent before marketing tags; lead data deletable |
| NFR-11 | Reliability | Lead submission never lost: stored in DB before any external call; queued jobs retry |
| NFR-12 | Maintainability | Typed frontend, static analysis (Larastan level 6+), formatted code, ≥ 80% backend coverage on domain code |
| NFR-13 | Localisation | All UI strings and content translatable; Bangla numerals and fonts render correctly |
| NFR-14 | Operability | One-command local setup on XAMPP; documented deployment; daily DB backup |

## 7. Information architecture

```
/                               Home
/work                           Work index (filters)
/work/{slug}                    Case study
/services                       Services index
/services/{slug}                Service (and sub-service)
/solutions                      Solutions index
/solutions/ecommerce            Auxtech Ecommerce (flagship)
/solutions/{slug}               Reseller platform, LMS, POS, corporate, custom
/auxify                         SaaS page
/industries/{slug}              Industry page
/pricing                        Pricing
/estimate                       Project estimator
/about                          About
/blog, /blog/{slug}             Blog
/blog/category/{slug}, /blog/tag/{slug}
/careers, /careers/{slug}       Careers
/faq                            FAQ
/contact                        Contact
/{slug}                         CMS pages and SEO landing pages
/bn/...                         Bangla mirror of everything above
/admin/...                      Admin panel (noindex)
/sitemap.xml /robots.txt /llms.txt /llms-full.txt /feed.xml
```

## 8. Presenting the work (content rules)

1. **Lead with outcomes, not screenshots.** Each case study opens with one sentence: who the client is and what Auxtech delivered.
2. **Group by what the visitor sells.** A fashion seller should see ten fashion stores within two clicks.
3. **Show the engine once, the stores many times.** The 23 modules live on the Ecommerce page; each store's case study lists only the modules it uses.
4. **Mark demos honestly.** Projects on `auxdemo.com` / `auxtechsoftware.com` subdomains are labelled "Demo" or "Pre-launch", never passed off as live clients.
5. **Numbers need a source.** Stats such as "30+ stores launched" are stored in settings and must be true on the day they are published. No invented metrics, testimonials or client quotes.
6. **Every project page ends with a WhatsApp CTA** pre-filled with "I want a site like {project}".

## 9. Decisions and open questions

### Confirmed by Auxtech (16 Nov 2026)

| Topic | Decision |
|---|---|
| Domain | **`auxtechbd.com`** is the public domain, pointed by custom DNS at the `auxtechsoftware.com` hosting account. It is currently expired and must be renewed before launch (see `00-RESEARCH.md` §2.1) |
| Hosting | Shared cPanel with SSH/terminal access. Deployment and SSR approach: `02-BACKEND-ARCHITECTURE.md` §11 |
| Email | auxtechbd@gmail.com |
| Social | Facebook `facebook.com/auxtechs`; Instagram `auxtech.bd` |
| Office hours | Every day except Friday, 10:00–18:00 |
| Logo | Transparent logo supplied; SVG, white variant, favicon and app icons generated from it |
| Portfolio rights | All 32 projects may be shown. Auxtech retains portfolio rights; client sites carry Auxtech branding |

### Still open

| # | Question | Default used until answered |
|---|---|---|
| Q1 | Founding year, team size, number of clients served? | Hidden until provided |
| Q2 | Other profiles (LinkedIn, YouTube, Google Business Profile link)? | Only Facebook and Instagram shown |
| Q3 | Real client testimonials (name, role, quote)? | Testimonial section hidden until at least 3 exist |
| Q4 | Published pricing for Auxtech Ecommerce and other solutions? | "Get a quote" CTAs |
| Q5 | Does the cPanel account offer "Setup Node.js App" or a usable `node` binary over SSH? | Assume yes; fallback in document 02 §11 |
| Q6 | A domain mailbox (e.g. `hello@auxtechbd.com`) for sending lead notifications? Gmail as a sender address hurts deliverability | Gmail used as the reply-to and notification recipient |

## 10. Acceptance

The project is accepted when all **M** requirements pass their test cases in `04-TESTING.md`, the performance and accessibility thresholds in section 6 are met on production, `auxtechbd.com` is renewed and pointing at the hosting account, and open question Q5 (Node on the hosting account) is resolved.
