# 00 — Research Findings

> Input for every other document. Read this first.
> Research date: 16 Nov 2026. Raw crawl data: [`research/client-sites.json`](research/client-sites.json). Screenshots: `resources/seed/portfolio/*.webp`.

## 1. Company facts (source of truth)

| Field | Value |
|---|---|
| Legal / brand name | **Auxtech** (never "Auxtech International"; client footers also use "AuxTech" — we standardise on **Auxtech**) |
| Services | Website development, app development, digital marketing |
| Flagship product | **Auxtech Ecommerce** (23 modules, section 3) |
| SaaS | **Auxify** — https://auxify.store |
| Address | 754/1, 5th Floor, West Shewrapara, Begum Rokeya Avenue, Sharini 1216, Dhaka, Bangladesh |
| WhatsApp / hotline | 01805019415 → international `+8801805019415` → `https://wa.me/8801805019415` |
| Primary conversion | WhatsApp chat |
| Website | **https://auxtechbd.com** (primary domain; see §2.1). Hosting account: `auxtechsoftware.com`, which also serves demos on subdomains |
| Email | auxtechbd@gmail.com |
| Office hours | Every day except Friday, 10:00–18:00 (Asia/Dhaka) |
| Social | Facebook https://www.facebook.com/auxtechs/ (about 5.2K followers, category "Software Company"); Instagram `auxtech.bd` (as shown on the Facebook page) |
| Slogan (Facebook) | "Don't go for a company that makes simple software, go for a company that can make the software simple." |
| Brand colour | `#0145FF` (sampled from the logo), on white |
| Logo | `resources/brand/`: `auxtech-logo.svg`, `auxtech-logo-white.svg`, `auxtech-icon.svg`, `auxtech-icon-white.svg`, traced from the supplied 1125×174 transparent WebP. Favicon and app icons are in `public/` |
| Portfolio rights | Auxtech keeps the right to show every client project; all client sites carry Auxtech branding by agreement |

## 2. Portfolio audit — 32 live projects

Every URL supplied was loaded in a headless browser on the research date. **All 32 returned HTTP 200.** `technmorebd.com` was listed twice and is counted once.

| # | Project | URL | Category | What it is (observed) |
|---|---|---|---|---|
| 1 | Fablix | fablixbd.com | Fashion e-commerce | Men's wear: hoodies, shirts, baggy jeans. Footer: "Developed by Auxtech" |
| 2 | Ventire | ventirebd.com | Fashion e-commerce | Men's casual wear, combo sets, offer zone |
| 3 | Signature Mart BD | signaturemartbd.com | Fashion e-commerce | Men's winter collection, polo, panjabi |
| 4 | Queens Club BD | queensclubbd.com | Fashion e-commerce | Women, teen and kids wear; wholesale orders |
| 5 | Noksha Craft | nokshacraft.com | Fashion & craft | Women's dresses, bedsheets, Nokshi Katha |
| 6 | WeeShop BD | weeshopbd.com | Fashion e-commerce | Leather shoes and watches |
| 7 | Kalki | kalki.auxdemo.com | Fashion (demo) | Full family fashion catalogue: women, men, kids, newborn, footwear |
| 8 | Jamilooms (The Patchee) | thepatchee.auxtechsoftware.com | Fashion (staging) | T-shirts, bags, caps, shoes |
| 9 | Dhaka Culture | dhakaculture.com | Fashion e-commerce | Streetwear brand: semidrop tees, pre-order |
| 10 | La Vie World | lavieworld.auxtechsoftware.com | Fashion (staging) | Handbags, sling bags, perfumes, "watch & shop" |
| 11 | Tech N More | technmorebd.com | Gadgets e-commerce | Phone accessories by brand, pre-order |
| 12 | Gearzen | gearzen.com.bd | Gadgets e-commerce | Phones, MacBook, cameras, drones; compare and wishlist |
| 13 | UniGear | unigear.com.bd | Gadgets e-commerce | Speakers, tripods, microphones, smart watches |
| 14 | Gadget Panel BD | gadgetpanelbd.com | Gadgets e-commerce | Mobile accessories, wearables, pre-order, free pickup |
| 15 | Bottola Bazar | bottolabazar.com | General store | Multi-category marketplace, complaint module |
| 16 | Greenland Marketing Company | greenlandmarketingcompany.com | General / corporate purchase | B2C + corporate purchasing, blog, weekly discounts |
| 17 | Bikolpoo | bikolpoo.com | General store | Home appliance, decor, skin care |
| 18 | Cartex | cartexbd.com | General store | Electronics, wellness, clothing, pre-order |
| 19 | Gulshan Baking Shop | gulshanbakingshop.com | Food & grocery | Imported groceries, baking tools, 24h Dhaka delivery |
| 20 | BD Fresh Bazar | bdfreshbazar.com | Food & grocery | Dairy: yogurt, ghee, milk |
| 21 | Seasonal | seasonalbd.com | Food & grocery | Honey, cold-pressed oil, rice, spices from farmers |
| 22 | Seasonal (demo) | seasonal.auxtechsoftware.com | Food (demo) | Demo of the organic food storefront |
| 23 | Auxify | auxify.store | **SaaS (own product)** | Multi-tenant store builder, 10 themes, plans ৳999–৳2,399/month, BN + EN |
| 24 | AuxTech Reseller v2 | resellerv2.auxtechsoftware.com | Reseller platform (demo) | B2B dropshipping/reselling platform |
| 25 | Rong Torongo B2B | rongtorongob2b.com | Reseller platform | Dropshipping and reselling, packages |
| 26 | E Hub Dropshop | ehubdropshop.com | Reseller platform | Zero-capital dropshipping, support tickets, tutorials |
| 27 | Astha Reseller | asthareseller.com | Reseller platform | B2B catalogue network, COD to 64 districts |
| 28 | Water Technology BD Ltd | wtbl.com.bd | Corporate website | Industrial water treatment firm, 600+ projects |
| 29 | Greenland Tax | tax.greenlandmarketingcompany.com | Custom web app | Bangladesh income tax calculator with certificate generation |
| 30 | E7 Training | e7training.com | LMS | Paid courses, student and trainer portals |
| 31 | SS Typing & Travels | sstyping.com | Travel | UAE-based Hajj/Umrah and typing services |
| 32 | CleanBDX | cleanbdx.com | Full custom system | Digital waste management: citizens, sellers, riders |

### What this tells us about positioning

1. **Auxtech is an e-commerce specialist first.** 22 of 32 projects run on the same commerce engine. The site must lead with that, not with a generic "we do everything" message.
2. **Four proven verticals** give us ready-made industry pages: fashion (10), gadgets (4), general retail (4), food and grocery (4).
3. **Range beyond e-commerce is real but thinner**: reseller platforms (4), LMS, tax tool, travel, corporate, waste-management system. These prove custom-software capability and each deserves a case study.
4. **Two own products** (Auxtech Ecommerce and Auxify) let us sell productised offers with clear pricing, which convert better than open-ended quotes.
5. **The audience is Bangladeshi merchants.** Most client sites are in Bangla or mixed. The Auxtech site must be bilingual (English + বাংলা).

### 2.1 Footer credits and the domain decision

A second pass checked every site's full page text and links for an Auxtech credit.

| Finding | Count |
|---|---|
| Sites that name Auxtech in the page ("Developed by / Crafted by / Powered by Auxtech"; includes Auxtech's own reseller demo) | **28 of 32** |
| Sites whose credit **links to `https://auxtechbd.com/`** | **20** |
| Credit present but no link to an Auxtech domain found | Kalki, Queens Club BD, CleanBDX, and the staging sites La Vie World, Jamilooms, Seasonal demo |
| Credit links to a different domain (`auxtechint.com`, which does not resolve) | WTBL |
| No credit found | E7 Training, Gulshan Baking Shop, SS Typing & Travels (Auxify is Auxtech's own product) |

On the research date `auxtechbd.com` showed a registrar page titled **"Your domain is expired"**, and `auxtechsoftware.com` showed an empty directory listing.

Consequences:

1. **`auxtechbd.com` must be the site's primary domain.** Twenty client sites already link to it. Those links are the most valuable SEO asset Auxtech has, and they are currently pointing at an expired-domain page.
2. **Renewing `auxtechbd.com` is urgent.** After expiry a domain passes through grace and redemption periods and can then be registered by someone else, who would inherit all twenty backlinks.
3. `auxtechsoftware.com` should 301-redirect to `auxtechbd.com` (its subdomains keep serving demos).
4. Small fixes on the client side: make the text-only credits into links to `auxtechbd.com`, correct the WTBL link, add a credit to the three sites without one.

### Issues found on client sites that affect our portfolio

These weaken the proof when a prospect clicks through. Worth fixing on the client side:

- 13 of the 22 storefronts still have the default meta description "E-commerce store"; several have the title "Home".
- Several footers carry template placeholder text ("Your premium destination for beauty and wellness") on non-beauty stores (Ventire, Bottola Bazar, Seasonal demo, The Patchee).
- Auxify shows the name "My SaaS Platform" in its header and title.
- Typos: "Bilolpoo" (title of bikolpoo.com), "Gulshan **Banking** Shop", "Casula Shirt's".

Because live client sites change, **the portfolio stores its own screenshots** and never iframes or hot-links client sites.

## 3. Auxtech Ecommerce — the 23 modules, grouped for presentation

A flat list of 23 items is hard to read, so the site presents them in four groups.

| Group | Modules |
|---|---|
| **Sell** | Product Management, Order Management, Point of Sale (POS), Pre-Order Management, Product Request Management, Landing Page Management, Website Page Management |
| **Fulfil** | Inventory Management, Courier Integration, Order Tracking, Incomplete Order Management |
| **Grow** | Campaign Management, Coupon Management, Reviews & Ratings, Customer Management, Meta Pixel & Google Tag Manager, Conversions API (CAPI) |
| **Control** | Dashboard, Accounting Management, Employee Management, User & Access Management, API Settings, System Settings |

Selling points specific to Bangladesh, visible across client stores: cash on delivery, courier integration, incomplete-order recovery, pre-orders, server-side Meta tracking (CAPI) for Facebook-driven sales.

## 4. International inspiration

| Site | What we take from it |
|---|---|
| netguru.com | Order of proof: client logos directly under the hero → case-study cards with a **headline result** each → numbers band → named testimonials → closing "Estimate project" CTA |
| thoughtbot.com | One repeated CTA ("Let's talk") in header, hero and footer; case studies tagged by service; resources as a lead source |
| linear.app | Product UI as the hero visual instead of stock art; restrained dark surfaces; short headings; numbered "figure" captions |
| stripe.com, vercel.com (general reference) | Mesh-gradient hero, bento grids, glass navigation, crisp type scale |

Design direction derived from these is in [`03-FRONTEND-DESIGN.md`](03-FRONTEND-DESIGN.md).

## 5. Search landscape in Bangladesh

For queries such as "top IT firms in Bangladesh" or "best ecommerce website development company in Dhaka", page one is dominated by **directories and listicles**, not by agency homepages: Clutch, GoodFirms, DesignRush, TechBehemoths, and self-published "top 20" posts (e.g. Riseup Labs ranks itself first).

Consequences:

1. Ranking the homepage for "top IT firms in Bangladesh" directly is a long game. The faster wins are **specific, high-intent queries**: "ecommerce website price in Bangladesh", "reseller website development", "LMS development company Bangladesh", "POS software with ecommerce", plus Bangla equivalents.
2. Auxtech must be **listed on those directories** with reviews. That is off-site work the website cannot do for you.
3. Publishing our own well-researched comparison content ("Top e-commerce development companies in Bangladesh") is a legitimate, common tactic.

## 6. Being recommended by ChatGPT and other AI assistants

Published studies are mostly from vendors and disagree in places, so treat this as directional.

- AI assistants answering "best agency in X" lean heavily on **directories and review platforms** (Clutch was the most-cited source for digital services in one 2025 analysis) and on **Google review volume** for local businesses.
- A company's **own site** is cited for facts about it when the content is clear, structured and current.
- `llms.txt` is cheap to add but there is no confirmation that major assistants read it. We ship it without relying on it.
- Structured data (Organization, LocalBusiness, Service, FAQ, Article) and self-contained, factual sentences help machines extract who Auxtech is and what it does.

**No one can guarantee a top ranking or an AI recommendation.** What we can do is build every on-site signal correctly and give you a checklist for the off-site ones. The full plan is in [`05-SEO-GEO-STRATEGY.md`](05-SEO-GEO-STRATEGY.md).

## 7. Sources

- [Netguru](https://www.netguru.com/), [thoughtbot](https://www.thoughtbot.com/), [Linear](https://linear.app/)
- [GoodFirms — e-commerce developers in Bangladesh](https://www.goodfirms.co/ecommerce-development-companies/bangladesh)
- [DesignRush — e-commerce agencies in Bangladesh](https://www.designrush.com/agency/ecommerce/bd)
- [TechBehemoths — e-commerce development in Dhaka](https://techbehemoths.com/companies/e-commerce-development/dhaka)
- [Clutch — web developers in Bangladesh](https://clutch.co/bd/web-developers?page=5)
- [Riseup Labs — top IT companies in Bangladesh](https://riseuplabs.com/top-it-company-in-bangladesh/)
- [Insites — what AI uses to choose local businesses](https://insites.com/blog/what-ai-uses-to-choose-local-businesses-and-what-it-ignores/)
- [Hall — review platform AI citation analysis](https://usehall.com/guides/review-platform-ai-citation-analysis)
- [Peec.ai — review sites in AI search](https://docs.peec.ai/research/review-sites-in-ai-search)
- [Passionfruit — Clutch, G2, DesignRush in AI agency recommendations](https://www.getpassionfruit.com/blog/clutch-g2-designrush-ai-agency-recommendations)
- [Firebrand — what is llms.txt](https://www.firebrand.marketing/2025/08/what-is-llmstxt-and-how-it-can-boost-your-geo-efforts/)
- [Erlin — GEO best practices](https://www.erlin.ai/blog/generative-engine-optimization-best-practices)
