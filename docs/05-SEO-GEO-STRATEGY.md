# 05 — SEO and AI-Visibility (GEO) Strategy

**Audience:** everyone; the on-site parts are built in Phases 1 and 2, the off-site parts are Auxtech's own ongoing work.
**Evidence and sources:** [`00-RESEARCH.md`](00-RESEARCH.md) §5–7.

> **Honest expectation.** No website build can guarantee a number-one ranking or a recommendation from ChatGPT. Rankings depend on competitors, links, reviews and time. This plan makes every signal Auxtech controls as strong as possible and lists the outside work that decides the rest.

---

## 1. Positioning for search

Auxtech should not try to rank first as a generic "IT firm". The portfolio proves a sharper claim:

> **Auxtech is a Dhaka-based web, app and digital marketing company that specialises in e-commerce platforms for Bangladeshi brands, with 30+ live stores and its own commerce engine and SaaS (Auxify).**

Every page, profile and listing should repeat this description in near-identical words. Consistency is what lets search engines and AI models form a stable picture of the company.

## 2. Keyword map

One primary keyword per page. Bangla pages target the Bangla phrasing of the same intent.

| Page | Primary keyword (EN) | Supporting keywords |
|---|---|---|
| Home | web development company in Bangladesh | IT company in Dhaka, software company Bangladesh, app development company |
| /solutions/ecommerce | ecommerce website development in Bangladesh | ecommerce website price in Bangladesh, online store development, ecommerce with POS and inventory |
| /solutions/reseller-platform | reseller website development Bangladesh | dropshipping platform development, B2B reseller software |
| /solutions/lms | LMS development company Bangladesh | online course platform development |
| /solutions/pos | POS software with ecommerce Bangladesh | inventory and POS software |
| /solutions/corporate-website | corporate website design Bangladesh | company website development Dhaka |
| /solutions/custom-software | custom software development Bangladesh | web application development |
| /auxify | ecommerce website builder Bangladesh | create online store Bangladesh, Shopify alternative Bangladesh |
| /services/website-development | website development service Dhaka | Laravel development company Bangladesh |
| /services/app-development | mobile app development company Bangladesh | Flutter app development Dhaka |
| /services/digital-marketing | digital marketing agency in Bangladesh | Facebook marketing agency, SEO service Bangladesh |
| /services/meta-pixel-capi | Conversions API setup Bangladesh | Meta Pixel setup, server-side tracking |
| /industries/fashion | fashion ecommerce website development | clothing store website Bangladesh |
| /industries/gadgets | gadget shop website development | electronics ecommerce Bangladesh |
| /industries/food-grocery | grocery ecommerce website Bangladesh | organic food online store development |
| /pricing | website development cost in Bangladesh | ecommerce website price |
| /work | ecommerce website examples Bangladesh | web development portfolio |
| Landing pages | {service} in {area} (Mirpur, Uttara, Chattogram…) | only where real, unique content exists |

Bangla examples: "ই-কমার্স ওয়েবসাইট তৈরি", "ই-কমার্স ওয়েবসাইট বানাতে কত টাকা লাগে", "রিসেলার ওয়েবসাইট তৈরি", "ওয়েবসাইট ডেভেলপমেন্ট কোম্পানি".

## 3. On-site SEO (built into the product)

| Area | What the site does | Where specified |
|---|---|---|
| Rendering | Server-side rendered HTML for every public page | 02 §3, §11 |
| Metadata | Unique title, description, canonical, Open Graph per page; admin editing with previews; missing-meta report | 02 §7, 03 §10 |
| Languages | `/` English, `/bn` Bangla, reciprocal `hreflang` + `x-default` | 02 §5 |
| Structured data | Organization, LocalBusiness, WebSite, BreadcrumbList, Service, SoftwareApplication, CreativeWork, Article, FAQPage, JobPosting | 02 §7 |
| Crawl control | Sitemap index with language and image entries, robots editor, redirect manager, automatic slug redirects, IndexNow | 02 §7 |
| Speed | Core Web Vitals budgets enforced in CI | 03 §14, 04 §7.6 |
| Internal links | Breadcrumbs; case study ↔ solution ↔ industry ↔ service cross-links; related posts; footer hubs | 03 §5 |
| Images | AVIF/WebP, dimensions, lazy loading, mandatory alt text in both languages | 02 §9 |
| Local signals | Identical name, address and phone on every page and in schema; map embed; office hours | 01 FR-S11 |

**Title pattern:** `{Primary keyword} | Auxtech` (≤ 60 characters).
**Description pattern:** what it is + proof + action, 140–155 characters. Example: *"E-commerce website development in Bangladesh with POS, inventory, courier and CAPI built in. 30+ live stores. Chat with Auxtech on WhatsApp."*

## 4. Content plan

Content is what earns rankings after launch. Minimum cadence: **two posts per month**, each tied to a commercial page.

| Type | Examples | Purpose |
|---|---|---|
| Cost and buying guides | "E-commerce website cost in Bangladesh (2026 price guide)", "Custom site vs Shopify vs Auxify" | Highest purchase intent |
| How-to for merchants | "How to set up Meta Conversions API for a Bangladeshi online store", "Recovering incomplete orders" | Shows expertise; attracts links |
| Comparisons and lists | "Best courier services for e-commerce in Bangladesh", "Top e-commerce development companies in Bangladesh" (fair, with Auxtech included) | Captures "best/top" searches |
| Case studies | One per project, starting with the 8 strongest | Proof; long-tail industry terms |
| Bangla originals | Written in Bangla, not machine-translated | Less competition, closer to buyers |

Each article: one clear question answered in the first paragraph, original screenshots or data, author name and bio, last-updated date, FAQ block, and an inline WhatsApp CTA.

## 5. AI visibility (GEO)

AI assistants answer "which company should I use?" from three places: what they learned in training, what they retrieve live from search, and third-party lists and reviews. The plan covers all three.

### 5.1 On-site (built in)

1. **Crawlable by AI bots:** `GPTBot`, `OAI-SearchBot`, `ChatGPT-User`, `ClaudeBot`, `PerplexityBot`, `Google-Extended` and `Bingbot` allowed in `robots.txt`; content in the HTML, not behind JavaScript.
2. **`/llms.txt` and `/llms-full.txt`:** a plain-language summary of the company, services, products, proof and links. Low cost; benefit unproven, so we do not depend on it.
3. **A facts block on Home and About:** short, self-contained sentences a model can quote — who Auxtech is, where it is, what it builds, for whom, how many projects, how to contact.
4. **Question-and-answer content:** FAQ sections that mirror how people ask assistants ("How much does an e-commerce website cost in Bangladesh?").
5. **Entity markup:** Organization schema with `sameAs` links to every official profile, so all mentions resolve to one entity named "Auxtech".
6. **Freshness:** visible "last updated" dates; pricing and guides reviewed quarterly.

### 5.2 Off-site (Auxtech's ongoing work — decisive)

| Action | Why it matters | Priority |
|---|---|---|
| **Google Business Profile**: exact name, address, phone, category "Website designer / Software company", photos, services; ask every client for a Google review | Review count is the strongest observed predictor of AI and local-pack visibility | 1 |
| **Clutch, GoodFirms, DesignRush, TechBehemoths** profiles with verified client reviews | These directories own page one for "top IT firms in Bangladesh" and are heavily cited by AI assistants | 1 |
| **Renew `auxtechbd.com` immediately.** 20 client sites already link to it and it is showing an "expired" page | These backlinks are Auxtech's strongest SEO asset; if the domain lapses someone else can take it and them | **0 (now)** |
| Complete the footer credits: 28 of 32 sites already name Auxtech; make the text-only credits into links to `auxtechbd.com`, fix WTBL's link (points to `auxtechint.com`), add a credit to the 3 sites without one | Relevant backlinks at no cost | 1 |
| Fix weak SEO on client stores (default "E-commerce store" descriptions, "Home" titles, placeholder footer text) | Client sites are the portfolio's proof and the link sources | 2 |
| **Facebook page (`facebook.com/auxtechs`, ~5.2K followers) and Instagram (`auxtech.bd`)**: set the website to `https://auxtechbd.com`, use the same description, address and phone; add a **LinkedIn company page** and YouTube | Entity consistency; these URLs go into the site's `sameAs` schema | 2 |
| **BASIS** membership listing; local business directories | Authority and citations | 2 |
| Guest articles and mentions in Bangladeshi tech and business media; inclusion in other people's "top companies" lists | Third-party mentions drive both rankings and AI recommendations | 3 |
| Bing Webmaster Tools + IndexNow | ChatGPT's live search leans on Bing's index | 1 |

### 5.3 What not to do

- No bought reviews, fake testimonials or invented statistics.
- No doorway pages: location pages exist only with unique, useful content.
- No hidden text or keyword stuffing aimed at AI crawlers.

## 6. Measurement

| Metric | Tool | Review |
|---|---|---|
| Impressions, clicks, positions per keyword group | Google Search Console, Bing Webmaster Tools | Weekly |
| Organic sessions, WhatsApp click rate, leads by source | GA4 + admin dashboard | Weekly |
| Core Web Vitals (field data) | Search Console, CrUX | Monthly |
| Indexed pages, crawl errors | Search Console | Weekly |
| AI visibility: ask ChatGPT, Gemini, Perplexity and Copilot a fixed list of 15 questions and record whether Auxtech appears | Manual log (spreadsheet) | Monthly |
| Referrals from `chatgpt.com`, `perplexity.ai`, `copilot.microsoft.com` | GA4 | Monthly |
| Review count and rating on Google, Clutch, GoodFirms | Manual | Monthly |

## 7. First 90 days after launch

| Weeks | Work |
|---|---|
| 0 | Renew `auxtechbd.com`; point it at the hosting account; 301 `auxtechsoftware.com` to it |
| 1–2 | Search Console and Bing verification, sitemap submitted, Google Business Profile complete, directory profiles created, footer credit added to client sites |
| 3–6 | Publish 8 case studies and the cost guide; request reviews from 10 clients; fix client-site meta data |
| 7–12 | Two posts per month; first Bangla originals; outreach for listings and mentions; first AI-visibility log; adjust titles based on Search Console data |
