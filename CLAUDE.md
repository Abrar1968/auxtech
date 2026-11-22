# Auxtech website — working notes

Company site + admin panel for **Auxtech** (never "Auxtech International"). Read `docs/README.md` first; the docs are the specification.

## Stack

Laravel 12 (lock pinned to PHP 8.2 via `config.platform`), MySQL/MariaDB 10.4+ (XAMPP), Inertia **v3** + Vue 3 + TypeScript (no SSR), Tailwind CSS v4, Pusher Channels, `motion-v`, Lenis, Pest, Vitest, Playwright.

## Conventions

- Phases: 1 backend (done) → 2 frontend (done) → 3 testing (done; UAT and launch with Auxtech). Plans in `docs/phases/`; results in `docs/07-TEST-REPORT.md`, traceability in `docs/06-TRACEABILITY.md`.
- Production: `auxtechsoftware.com` (cPanel `public_html`; demos live on its subdomains) on shared cPanel. The zip's contents extract straight into `public_html`. Delivered as a zip + browser installer. **Nothing may require SSH, Composer, Node, cron, a queue worker, Redis or a storage symlink on the server** (`docs/02-BACKEND-ARCHITECTURE.md` §11).
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
- Admin content types are modules (`app/Admin/Modules`, listed in `config/admin.php`) served by one `CrudController`. Add a module rather than a new controller; re-run `PermissionSeeder` afterwards (`docs/02` §2.1).
- Slow work after a request goes through `App\Support\AfterResponse` (no queue worker exists in production).
- Search on text columns uses `App\Support\Search` (MariaDB JSON columns are case-sensitive with plain LIKE).
- Interface strings go through `useI18n().t('English text')`; add the Bangla to `lang/bn.json` (a Vitest test enforces it). Content text comes from props/settings, never hard-coded.
- New icons: add to `resources/data/icons.json` and `resources/js/lib/icons.ts` (a test keeps them in step).
- Keep public bundles small: admin-only code stays under `pages/Admin` or `components/admin`; anything not needed for first paint is `defineAsyncComponent`.
- Every public page type has a Blade fallback in `resources/views/fallback/site`; keep it in step with the page.
- No invented stats, testimonials or client quotes. Demo/staging projects are labelled as demos.
- CSP uses per-request nonces: every inline `<script>` in Blade needs `nonce="{{ Vite::cspNonce() }}"`. Scripts added at runtime are trusted through `'strict-dynamic'`.
- The app shell (`resources/views/shell/_hero.blade.php`) mirrors the hero markup of `HomeHero.vue` and `PageHero.vue`; change them together.
- Palette: Gray `#2B2D31` (`ink-950`), Silver `#D9DADC` (`silver`, lines), Yellow `#FFC93C` (`brand-600`). Yellow fills always carry gray text (`text-ink-950`), never white. Brand colour as text on page surfaces is `text-link` (deep amber on light, yellow on dark), never `text-brand-*`. White text on gray stays at 65% opacity or more (axe checks it).
- Seeded content lives in `database/seeders/data/portfolio.php` (38 projects) and `catalogue.php` (services, platforms, industries, technologies, FAQs); site copy defaults in `config/settings.php`. `php artisan auxtech:seed` is safe to re-run (keeps admin edits; `--overwrite`, `--images=`). Images are never committed: they ship as a separate image pack (release/auxtech-image-pack.zip, git-ignored) with UPLOAD-GUIDE.html: client logos, project screenshots, platform covers and Auxtech Ecommerce module screenshots (Admin > Features > Screenshot). Pricing packages are seeded without prices ("Custom quote"). "Projects delivered" counts live sites and products, not demos.
- Auxtech is presented as a software development company; the public menu says "Platforms" for solutions. Project texts describe only what is visible on the live sites, never client results.
- Fonts: Fraunces (headings, soft axis; `*stars*` in hero, section and CTA titles become Fraunces italic, see `resources/js/lib/accent.ts`; strip them with `plain()` anywhere else, Bangla titles have no stars) and Plus Jakarta Sans (text), self-hosted via @fontsource; Anek Bangla for Bangla. `app.blade.php` preloads both latin files.
- The home hero is scroll-pinned (`HomeHero.vue`): a Canvas 2D scene (`heroScene.ts`, loaded after first paint) of light trails and a wireframe laptop and phone showing featured projects' screenshots; stages are the first three services; labels are sub-services. Reduced motion and data saver get a still frame and no pinning. A background video is optional (`home.hero_video`, empty by default).
- The old home background video is generated from `scripts/hero-video/scene.html` (`HERO_SCREENS=<image pack>/2-project-screenshots node scripts/hero-video/render.cjs` → public/media; the phone shows real project screenshots). H.264 is listed first and the video fades in only after `canplaythrough`; `public/.htaccess` sets video MIME types and keeps media uncompressed so byte ranges work. Paths are admin settings (`home.hero_video*`).
- Never add files under `public/` whose path matches a route (Apache serves the file instead). Files a past release shipped that must go are listed in `App\Support\ObsoleteFiles`.

## Checks before committing

```
vendor/bin/pint --test && vendor/bin/pest          # SQLite; also run on MariaDB (see Sandbox notes)
npm run typecheck && npm run lint && npm test && npm run build
npx playwright test --project=desktop-chrome --project=android   # after migrate:fresh --seed, with ADMIN_PASSWORD
node scripts/seo-crawl.mjs http://127.0.0.1:8000
```

## Release

`bash scripts/build-release.sh` runs `npm run build` and writes `release/auxtech-production.zip` (git-ignored; contains the Pusher secret): the whole project with its dotfiles (`.htaccess`, `.env` with an empty key and address for the installer, `.env.production`, `.env.example`), source, docs, tests and production `vendor`; only `.git`, `node_modules`, uploads and local runtime folders (logs, sessions, caches, `storage/inertia-devtools`) stay out. Check the zip size (about 73 MB). Installed sites keep a backup of their settings in `storage/.env` (bootstrap/app.php: a top-level `.env` with an APP_KEY wins and refreshes the backup; the template `.env` from a zip falls back to the backup; never shipped); the script also writes `release/auxtech-update.zip` (no `.env`) for sites installed before 1.4.0. Also hand over `release/auxtech-database.sql` (mysqldump without users, sessions or cache, first `/*M!` line removed so MariaDB 10.4 and MySQL accept it) with `release/auxtech-uploads.zip` (`public/uploads`) when the owner wants the local content and images as they are. After changing the installer, `.htaccess` or migrations, rehearse it: extract into an Apache document root, install through `/install`, then extract a second build over it and run the database update from Admin > System.

## Sandbox notes

- In the cloud sandbox Composer must use `--prefer-source` (GitHub zip downloads are blocked) and PHPStan cannot be installed.
- MariaDB can be installed there (`apt-get update && apt-get install -y mariadb-server`, start with `mariadbd-safe --user=mysql &`). Run the suite on both engines: plain `vendor/bin/pest`, then with `DB_CONNECTION=mysql DB_DATABASE=auxtech_test DB_USERNAME=root DB_PASSWORD=`.
- Sandbox `vendor/` holds full git histories (about 8 GB); the release script skips them. Watch disk space.
- Playwright is pinned to 1.63.0 to match the pre-installed Chromium; only Chromium projects run in the sandbox.
