# 03 — Frontend Architecture and UI/UX Design

**Roles:** Senior Frontend Developer and UI/UX Designer
**Implements:** Phase 2 ([`phases/PHASE-2-FRONTEND.md`](phases/PHASE-2-FRONTEND.md))
**Requirement IDs** refer to [`01-SRS.md`](01-SRS.md).

---

## Part A — UI/UX design

### 1. Design concept: "Luminous Blue"

Auxtech builds the machinery behind online stores. The site should feel like **a well-lit control room**: deep ink backgrounds where the product is shown, bright white space where the visitor reads, and one colour — the logo blue — carrying every accent.

Principles, in priority order:

1. **Proof before polish.** Real client screens, real numbers, real names appear in the first viewport. Decoration never pushes proof below the fold.
2. **One action.** Every screen has exactly one primary action, and it is WhatsApp. Everything else is secondary.
3. **Motion explains.** Animation shows cause and effect (a filter rearranges cards, a module opens its screen). Nothing moves only to impress.
4. **Bangla is first-class.** Layouts are designed with Bangla text lengths and line heights, not retrofitted.
5. **Fast on a ৳15,000 phone.** Heavy effects are progressive enhancements, switched off on weak devices.

### 2. Visual language

| Element | Decision |
|---|---|
| Mood | Clean, confident, technical; premium without being cold |
| Surfaces | **Dark immersive sections** (hero, product explorer, featured work, closing CTA) alternate with **light reading sections** (services, process, blog, FAQ) |
| Glassmorphism | Used for elements that float above content: navigation, stat chips over screenshots, filter bar, floating CTA, modals. Never for long text containers |
| Depth | Layered: aurora gradient mesh → fine grid/noise texture → content → glass → glow |
| Shape | Large radii (cards 24 px, inputs 14 px, pills full); 1 px hairline borders |
| Imagery | Client screenshots inside browser and phone frames; no stock photos of people at laptops |
| Icons | Lucide, 1.5 px stroke; custom duotone icons for the 23 modules |

### 3. Design tokens

Implemented in `resources/css/app.css` (`@theme`). Brand scale is built around the logo colour.

**Colour**

| Token | Value | Use |
|---|---|---|
| `brand-600` | `#0145FF` | Logo blue: buttons, links, focus rings, key accents |
| `brand-500` / `brand-400` | `#2F61FF` / `#5988FF` | Hover on dark, gradient stops |
| `brand-50…200` | `#EEF4FF…#BCD2FF` | Tints, light section backgrounds |
| `brand-700…950` | `#0034D6…#07184D` | Pressed states, deep gradients |
| `ink-950 / 900 / 800` | `#050816 / #0A0F24 / #111A36` | Dark surfaces |
| `whatsapp` | `#25D366` | **Only** for WhatsApp actions, so the eye learns it |
| Accent (sparingly) | cyan `#22D3EE`, violet `#7C5CFF` | Gradient partners for the aurora; never for text |
| Semantic | success `#16A34A`, warning `#F59E0B`, danger `#DC2626` | Forms, admin |

Text on white uses `ink-900` (contrast 17:1) and muted `#55607A` (6.6:1). White on `brand-600` is 6.1:1. All pairs meet WCAG AA.

**Typography**

| Role | Font | Notes |
|---|---|---|
| Display / headings | **Sora** (variable) | Geometric, matches the logo wordmark |
| Body / UI | **Inter** (variable) | |
| Bangla | **Anek Bangla** (variable) | Loaded for all text; `line-height` raised to 1.7 under `:lang(bn)` |
| Code / numbers in mockups | JetBrains Mono (optional) | |

All fonts are self-hosted (`@fontsource-variable`), subset, `font-display: swap`, with the two above-the-fold weights preloaded.

Fluid scale (`clamp`): display 40→76 px, h1 34→56, h2 28→44, h3 22→28, body 16→18, small 14. Headings use tight tracking (−0.02em) and `text-wrap: balance`.

**Spacing and layout:** 4 px base; section padding 64 px (mobile) to 128 px (desktop); container max 1280 px with 16/24/32 px gutters; 12-column grid on desktop, 4 on mobile.

**Elevation:** `glass` utility (blur 16 px, saturation 160%, hairline border); glow shadow `0 0 80px brand-600/35` for hero objects; soft shadow for light cards.

**Motion tokens:** durations 150 ms (micro), 300 ms (UI), 600–900 ms (reveals); easing `ease-out-expo` for entrances, springs (`stiffness 260, damping 26`) for interactive elements.

### 4. Core components

| Component | Notes |
|---|---|
| `SiteHeader` | Transparent over hero, becomes glass after 40 px scroll, hides on scroll down and returns on scroll up. Logo, nav with mega menu (Services, Solutions, Work, Pricing, Blog, About), language switch, theme switch, **WhatsApp button** |
| `MobileNav` | Full-screen sheet with staggered links; WhatsApp and Call pinned at the bottom |
| `WhatsAppButton` | Variants: primary, compact, floating (FAB with pulse ring and one-time tooltip), sticky mobile bar (WhatsApp + Call + Estimate) |
| `Button` | Primary (brand), WhatsApp, secondary (glass/outline), ghost, link-with-arrow; magnetic hover on desktop |
| `SectionHeading` | Eyebrow, title, lead; reveal animation built in |
| `ProjectCard` | Browser-framed screenshot, client name, industry tag, one-line result, hover tilt + image scroll |
| `DeviceFrame` | Browser and phone frames in pure CSS |
| `StatCounter` | Counts up once when visible; renders the final number in SSR |
| `LogoMarquee` | CSS-only infinite marquee, pauses on hover, grayscale to colour |
| `ModuleExplorer` | Tabs for Sell / Fulfil / Grow / Control; module list on the left, animated mock screen on the right |
| `BentoGrid` | Asymmetric service grid |
| `TestimonialCarousel` | Embla, swipeable, keyboard accessible |
| `PricingCard`, `FaqAccordion`, `ProcessTimeline`, `BlogCard`, `Breadcrumbs`, `Pagination` | |
| `LeadForm` | Floating labels, inline validation, BD phone mask, success state with WhatsApp handoff |
| `EstimatorWizard` | 4 steps with progress, back/forward, answers kept in session storage |
| `ConsentBanner` | Small glass sheet, not a wall |

### 5. Page blueprints

#### 5.1 Home (the conversion story)

| # | Section | Surface | Content and behaviour |
|---|---|---|---|
| 1 | **Hero** | Dark, aurora | H1 in the pattern *"We build the online stores Bangladesh buys from."* Sub-line names the three services. Primary CTA **Chat on WhatsApp**, secondary **See our work**. Right side: three tilted browser frames cycling real client stores, with floating glass chips ("Order #1042 confirmed", "Courier booked", "CAPI event sent"). Proof row under the CTAs: project count, industries, hotline |
| 2 | **Client marquee** | Dark → light transition | Client names/logos; "Trusted by brands across Bangladesh" |
| 3 | **Stats band** | Light | 3–4 admin-managed numbers with count-up |
| 4 | **Services** | Light | Bento grid: Website Development (large), App Development, Digital Marketing, plus small tiles for E-commerce, SEO, Meta ads. Each tile links to its service page |
| 5 | **Auxtech Ecommerce** | Dark | Module explorer for the 23 modules in four groups; "See a live demo" and WhatsApp CTAs |
| 6 | **Featured work** | Dark | Horizontal scroll-pinned showcase on desktop (swipe carousel on mobile) of 6 featured projects with industry filter chips; "View all 30+ projects" |
| 7 | **Beyond e-commerce** | Light | Cards: Reseller platform, LMS, Custom software (tax tool, waste management), Corporate sites, Auxify SaaS |
| 8 | **Process** | Light | 4–5 steps (Discover → Design → Build → Launch → Grow) on a scroll-drawn line |
| 9 | **Testimonials** | Light tint | Carousel with name, role, company, project link. Hidden until real testimonials exist |
| 10 | **Why Auxtech** | Light | 4–6 differentiators specific to BD commerce (COD, courier, incomplete orders, CAPI, POS, Bangla admin) |
| 11 | **Pricing teaser / estimator** | Brand gradient | "Get a price in 60 seconds" → estimator |
| 12 | **Insights** | Light | 3 latest posts |
| 13 | **FAQ** | Light | 6 questions, accordion, FAQ schema |
| 14 | **Closing CTA** | Dark, aurora | Large WhatsApp button, phone, short form |
| 15 | **Footer** | Dark | Services, solutions, industries, company, full address and phone, socials, language |

#### 5.2 Work index

Sticky glass filter bar (Industry, Solution, Service) with result counts; masonry grid; filtered items rearrange with shared layout animation; featured case studies span two columns. Empty state offers WhatsApp.

#### 5.3 Case study

Hero with client name, one-sentence outcome, live-site button, facts sidebar (industry, solution, modules, launch date) → large browser frame with scroll-through screenshot → Challenge / What we built / Result → modules-used chips linking to the Ecommerce page → mobile screens → testimonial → "Next project" → CTA "I want a site like {client}".

#### 5.4 Service and solution pages

Problem-led hero → who it is for → what is included (feature grid) → proof (projects using it) → process → pricing or quote CTA → FAQ → CTA. The **Auxtech Ecommerce** page adds the full module explorer, a comparison table (Facebook-only vs Auxify SaaS vs Auxtech Ecommerce) and the list of live stores by industry.

#### 5.5 Other pages

About (story, team, office map, values), Pricing (toggle by solution, comparison table), Blog (magazine index, article with sticky table of contents and inline CTA after the third section), Contact (WhatsApp card first, then form, then map), Estimator (full-screen wizard), 404 (search + popular links + WhatsApp).

### 6. Motion design

| Pattern | Where | Tool |
|---|---|---|
| Staggered text and element reveal on enter | All section headings and grids | Motion `whileInView`, once |
| Hero entrance sequence | Home hero | Motion variants with `staggerChildren` |
| Aurora drift | Hero / CTA backgrounds | CSS keyframes on blurred gradients (GPU only) |
| Parallax and tilt on device frames | Hero, case study | Motion `useScroll` + `useTransform`; pointer tilt on desktop only |
| Pinned horizontal showcase | Home featured work | GSAP ScrollTrigger (desktop ≥ 1024 px) |
| Scroll-drawn timeline | Process | GSAP ScrollTrigger scrub on SVG path |
| Shared layout transitions | Work filters, tabs, accordion, nav indicator | Motion `layout` / `layoutId` |
| Page transitions | Between Inertia pages | Motion `AnimatePresence` with a 250 ms fade/slide; View Transitions API where supported |
| Count-up numbers | Stats | Motion `animate` on a motion value |
| Magnetic buttons, cursor glow on cards | Desktop pointer devices | Motion springs |
| Marquee | Client logos | Pure CSS |
| Smooth scrolling | Whole site, desktop | Lenis, synced with ScrollTrigger |

**Guardrails**

- Animate only `transform` and `opacity`. No layout-triggering properties.
- `prefers-reduced-motion`: reveals become instant, pinned sections become normal scroll, marquee and aurora stop, smooth scroll is off.
- Low-end guard: if `navigator.deviceMemory ≤ 4` or `hardwareConcurrency ≤ 4`, disable blur-heavy layers, tilt and pinning.
- Server-rendered HTML is visible without JavaScript: initial states are applied on mount, never in SSR markup, so content cannot be stuck invisible.
- No animation delays the LCP element. Hero text is visible in the first paint.
- GSAP and Lenis are dynamically imported after first paint and only on pages that use them.

### 7. Conversion design (WhatsApp first)

1. **Placements:** header button, hero primary CTA, end of every section group, case-study CTA, pricing cards, floating button (desktop), sticky bottom bar (mobile), 404, form success state.
2. **Context-aware messages**, e.g. on a case study: *"Hi Auxtech, I saw your work for Gearzen and want a similar gadget store."* On a plan: *"I'm interested in the Professional package."* Bangla pages send Bangla text.
3. **Every click is tracked** with `sendBeacon` to `/api/events` and a `dataLayer` push (`whatsapp_click`, with `placement` and `page_type`) before the tab opens.
4. **Low-friction fallback:** a two-field callback form (name + phone) for visitors who do not want to chat.
5. **Micro-commitments:** the estimator, "see a live demo" and "view pricing" lead to WhatsApp with a pre-filled summary.
6. **Trust next to every CTA:** response-time line ("Usually replies within minutes, Sat–Thu 10am–7pm"), address, project count.
7. **No dark patterns:** no fake countdowns, no fake "X people viewing", no auto-opening chat windows.

### 8. Responsive behaviour

| Breakpoint | Width | Layout notes |
|---|---|---|
| base | 320–639 | Single column; sticky bottom action bar; hero shows one device frame; carousels instead of pinned scroll |
| `sm` | 640 | Two-column cards |
| `md` | 768 | Tablet: nav still collapsed; bento becomes 2×N |
| `lg` | 1024 | Desktop nav and mega menu; pinned showcase enabled |
| `xl` | 1280 | Full 12-column grid |
| `2xl` | 1536+ | Container capped; backgrounds bleed |

Touch targets ≥ 44 px; inputs 16 px font to prevent iOS zoom; safe-area insets respected for the bottom bar; no hover-only information.

### 9. Accessibility

Semantic landmarks and one `h1` per page; skip link; visible focus ring (`brand-600`, 2 px offset); all interactive components keyboard-operable (Reka UI primitives); carousels and marquees pausable; colour is never the only signal; alt text required in admin for every image (EN and BN); form errors announced with `aria-live`; language set per page (`lang="bn"`).

### 10. Admin panel UX

A separate, calmer interface: light by default, dense, no decorative animation.

- **Layout:** collapsible sidebar grouped as *Overview · Leads · Content (Projects, Clients, Services, Solutions, Industries, Testimonials, Blog, Pages, FAQs, Pricing, Team, Careers) · SEO (Meta report, Redirects, Robots, Sitemap) · Media · Settings · Users*. Top bar with global search (`Ctrl/⌘ K`), "View site", notifications, profile.
- **Dashboard:** KPI cards (leads, WhatsApp clicks, conversion rate, trend vs previous period), leads-by-source chart, clicks-by-placement chart, recent leads, content needing attention (missing meta, missing alt text, drafts).
- **Lists:** server-side search, filters, sort, pagination, bulk actions, drag-to-reorder where order matters, status badges.
- **Forms:** two columns: content on the left with **EN / বাংলা tabs**, and a right rail with status, schedule, featured, relations and the **SEO panel** (Google result preview, character counters, social image). Autosave drafts, unsaved-changes guard, "Save and view".
- **Leads:** table and kanban views; lead drawer with contact buttons (WhatsApp, call, email), attribution, timeline, notes, status and assignee.
- **Media:** grid with upload drop zone, alt-text editing, usage count.
- **Feedback:** toasts for success, inline errors, confirm dialogs for destructive actions.

---

## Part B — Frontend architecture

### 11. Stack

| Concern | Choice |
|---|---|
| Framework | Vue 3.5, `<script setup lang="ts">`, strict TypeScript |
| Routing/data | Inertia v3 (`@inertiajs/vue3` + `@inertiajs/vite`), SSR on public pages |
| Styling | Tailwind CSS v4 (CSS-first `@theme`), no component CSS files unless unavoidable |
| Motion | `motion-v` (Motion for Vue), GSAP + ScrollTrigger, Lenis |
| Primitives | Reka UI (dialog, popover, tabs, accordion, select, toast) |
| Utilities | VueUse, Embla carousel, Lucide icons |
| Editor (admin) | Tiptap, loaded only in admin |
| Charts (admin) | Lightweight SVG charts (unovis or hand-rolled), loaded only in admin |
| Build | Vite 7, route-level code splitting |
| Tests | Vitest + Vue Test Utils, Playwright, axe-core |

### 12. Directory structure

```
resources/js/
├── app.ts                    Single entry (browser + SSR)
├── pages/
│   ├── Site/                 Home, Work/Index, Work/Show, Services/*, Solutions/*, Blog/*, ...
│   ├── Admin/                Dashboard, Projects/{Index,Form}, Leads/*, ...
│   └── Auth/                 Login, TwoFactor, ForgotPassword, ResetPassword
├── layouts/                  SiteLayout.vue, AdminLayout.vue, AuthLayout.vue
├── components/
│   ├── ui/                   Button, Input, Badge, Dialog, Tabs ... (design-system atoms)
│   ├── site/                 SiteHeader, Footer, WhatsAppButton, ProjectCard, ModuleExplorer ...
│   ├── sections/             Home sections, reusable page sections
│   ├── motion/               Reveal, Stagger, Parallax, Magnetic, CountUp, PageTransition
│   └── admin/                DataTable, FormSection, SeoPanel, MediaPicker, LocaleTabs, RichEditor
├── composables/              useWhatsApp, useTracking, useLocale, useTheme, useSeoSync,
│                             useReducedMotion, useDeviceTier, useLeadForm, useConsent
├── lib/                      phone.ts, format.ts, analytics.ts
├── types/                    models.d.ts (mirrors backend Resources), inertia.d.ts
└── i18n/                     en.json, bn.json (UI strings)
```

### 13. Rules

1. **SSR-safe code.** No `window`/`document` at module top level or in `setup` outside `onMounted`. Browser-only libraries (GSAP, Lenis) are imported dynamically inside `onMounted`.
2. **The server owns `<head>`.** Title, meta, `hreflang` and JSON-LD are printed by Blade from the `seo` prop. Pages do not use `<Head>` for these. `useSeoSync()` in `SiteLayout` updates `document.title` and meta tags on client-side navigation.
3. **Props are typed** from `types/models.d.ts`, which mirrors backend Resources one-to-one.
4. **Text comes from props or i18n files**, never hard-coded in components, so both languages work.
5. **Links use `<Link>`** with prefetch on hover for main navigation; external links get `rel="noopener"`.
6. **Images go through one `AppImage` component**: `srcset`, `sizes`, width/height, lazy by default, `fetchpriority="high"` for the LCP image.
7. **Admin code never ships to public pages**: separate layouts and dynamic imports keep Tiptap, charts and tables out of the site bundle.
8. **Tracking goes through `useTracking()`** only; it respects consent and writes to both `dataLayer` and `/api/events`.
9. **State:** page props and composables. No global store unless a real need appears.
10. **Forms use Inertia `useForm`** (admin) and `useLeadForm` (site) with server-side validation as the source of truth.

### 14. Performance budget (NFR-01…05)

| Item | Budget |
|---|---|
| Initial JS, public page | ≤ 170 kB gzip |
| CSS | ≤ 30 kB gzip |
| Fonts | ≤ 3 files preloaded, ≤ 150 kB total above the fold |
| Hero image | ≤ 120 kB AVIF, preloaded |
| Third-party scripts | Loaded after consent and after `load`; GTM only |
| Long tasks | None > 200 ms on a mid-range phone during load |

Techniques: route-level splitting, `defineAsyncComponent` for below-the-fold interactive sections, `content-visibility: auto` on long sections, Inertia link prefetching, deferred props for secondary data, `IntersectionObserver`-gated animation setup.

### 15. Frontend definition of done

- Matches the blueprint at 360, 768, 1280 and 1920 px in both themes and both languages.
- Renders meaningful HTML with JavaScript disabled (SSR check).
- `npm run typecheck`, `npm run lint`, `npm run test` pass; no console errors or hydration warnings.
- Lighthouse mobile thresholds from SRS §6.1 met for the page.
- Keyboard-only walkthrough passes; axe reports no serious or critical issues.
- Reduced-motion mode verified.
