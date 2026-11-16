# Auxtech website — working notes

Company site + admin panel for **Auxtech** (never "Auxtech International"). Read `docs/README.md` first; the docs are the specification.

## Stack

Laravel 12 (lock pinned to PHP 8.2 via `config.platform`), MySQL/MariaDB 10.4+ (XAMPP), Inertia **v3** + Vue 3 + TypeScript (no SSR), Tailwind CSS v4, Pusher Channels, `motion-v`, GSAP, Lenis, Pest, Vitest, Playwright.

## Conventions

- Phases: 1 backend → 2 frontend → 3 testing. Plans in `docs/phases/`. Do not start a later phase early.
- Production: `auxtechbd.com` on shared cPanel. Delivered as a zip + browser installer. **Nothing may require SSH, Composer, Node, cron, a queue worker, Redis or a storage symlink on the server** (`docs/02-BACKEND-ARCHITECTURE.md` §11).
- No SSR. Crawlable content comes from the Blade content fallback inside the app root (`docs/02` §3.1); every public page type needs its fallback partial.
- Fully dynamic: no hard-coded public copy, stats, testimonials or prices; everything is admin-editable and optional sections hide when empty.
- CTAs: WhatsApp (primary) and email to auxtechbd@gmail.com (secondary) only. No call/callback CTAs. All notification mail goes to that Gmail address.
- Pusher: key/cluster in `.env.example`; the secret is never committed (local `.env` and the release zip's `.env.production` only). Clarity project `zehuflmwqj`, production public pages only.
- Company facts live in `config/auxtech.php` (later the `settings` table). WhatsApp: `https://wa.me/8801805019415`. WhatsApp is the primary CTA everywhere.
- Inertia pages are in `resources/js/pages` (lowercase, v3 convention). Single browser entry `resources/js/app.ts`.
- The Blade root template owns `<title>`, meta and JSON-LD (from the `seo` prop). Vue pages must not set them with `<Head>`.
- Translatable content uses spatie/laravel-translatable JSON columns (`en`, `bn`). English at `/`, Bangla at `/bn`.
- MariaDB 10.4 compatibility: no JSON path queries, generated columns or functional indexes (`docs/02-BACKEND-ARCHITECTURE.md` §4.4).
- Controllers stay thin: Form Request → Action → Resource. Never pass raw models to Inertia.
- No invented stats, testimonials or client quotes. Demo/staging projects are labelled as demos.

## Checks before committing

```
vendor/bin/pint --test && vendor/bin/pest
npm run typecheck && npm run lint && npm test && npm run build
```

## Sandbox notes

- In the cloud sandbox Composer must use `--prefer-source` (GitHub zip downloads are blocked) and PHPStan cannot be installed; tests run on SQLite there.
- Playwright is pinned to 1.63.0 to match the pre-installed Chromium; only Chromium projects run in the sandbox.
