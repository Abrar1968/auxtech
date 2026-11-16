# Phase 2 — Full Frontend

**Owner:** Senior Frontend Developer with the UI/UX Designer
**Specification:** [`../03-FRONTEND-DESIGN.md`](../03-FRONTEND-DESIGN.md)
**Starts when:** Phase 1 exit criteria are met.
**Outcome:** the complete public site and admin panel, responsive, animated, bilingual and within performance budgets.

## 2.1 Design system

- [ ] Finalise tokens in `app.css` (colour, type scale, spacing, radii, shadows, motion)
- [ ] Fonts: subsets, preloads, Bangla line-height rules
- [ ] `components/ui`: Button, Input, Textarea, Select, Checkbox, Badge, Card, Tabs, Accordion, Dialog, Sheet, Tooltip, Toast, Skeleton
- [ ] `AppImage`, `DeviceFrame`, `SectionHeading`, `Breadcrumbs`, `Pagination`
- [ ] Motion primitives: `Reveal`, `Stagger`, `Parallax`, `Magnetic`, `CountUp`, `PageTransition`
- [ ] Composables: `useTheme`, `useLocale`, `useReducedMotion`, `useDeviceTier`, `useSeoSync`
- [ ] Internal `/styleguide` page (dev only) showing every component in both themes

**Done when:** components have Vitest tests and pass axe in the style guide.

## 2.2 Site shell

- [ ] `SiteLayout`: header (glass on scroll, mega menu), mobile navigation sheet, footer
- [ ] Language switch, theme switch
- [ ] WhatsApp: header button, floating button, sticky mobile bar; `useWhatsApp` + `useTracking`
- [ ] Consent banner with Google Consent Mode v2; GTM loader
- [ ] Lenis smooth scroll and page transitions
- [ ] Error pages

## 2.3 Home page

- [ ] Hero with device frames, aurora background and entrance sequence
- [ ] Client marquee, stats band
- [ ] Services bento grid
- [ ] Auxtech Ecommerce module explorer
- [ ] Featured work (pinned horizontal on desktop, carousel on mobile)
- [ ] Beyond e-commerce, process timeline, testimonials, why Auxtech
- [ ] Estimator teaser, insights, FAQ, closing CTA

**Done when:** Home meets the Lighthouse thresholds on mobile and works with reduced motion and without JavaScript.

## 2.4 Inner pages

- [ ] Work index with animated filters; Case study
- [ ] Services index and detail; Solutions index and detail; Ecommerce flagship; Auxify
- [ ] Industries, Pricing, About, FAQ
- [ ] Blog index, category/tag, article with table of contents
- [ ] Contact with lead form and map; Estimator wizard
- [ ] Careers; CMS block renderer; SEO landing page template
- [ ] Search overlay

## 2.5 Admin panel

- [ ] `AdminLayout`: sidebar, top bar, command palette, toasts
- [ ] Auth screens (login, 2FA, reset)
- [ ] Shared admin components: DataTable, FormSection, LocaleTabs, SeoPanel, MediaPicker, RichEditor, SortableList, StatusBadge, ConfirmDialog
- [ ] Dashboard with KPI cards and charts
- [ ] CRUD screens for every content module
- [ ] Leads: table, kanban, detail drawer, export
- [ ] Media library; SEO tools (meta report, redirects, robots, sitemap status)
- [ ] Settings screens; users and roles; activity log

## 2.6 Bangla, polish and performance

- [ ] Complete `bn.json`; review every page in Bangla for overflow and tone
- [ ] Dark theme pass on every page
- [ ] Bundle analysis; lazy-load GSAP, Lenis, Embla, Tiptap; hit the 170 kB budget
- [ ] Image audit (LCP preload, `sizes`), font audit
- [ ] Low-end device pass (throttled) and reduced-motion pass
- [ ] Cross-browser pass on the matrix in `04-TESTING.md` §6

## Phase 2 exit criteria

1. Every page in the SRS information architecture is implemented in EN and BN, light and dark.
2. `typecheck`, `lint`, Vitest and the Playwright smoke suite pass; no console errors or hydration warnings.
3. Lighthouse mobile thresholds met on Home, Work, Case study, Ecommerce, Blog post and Contact.
4. axe: no serious or critical issues.
5. Auxtech has reviewed the design on a real phone and desktop.
