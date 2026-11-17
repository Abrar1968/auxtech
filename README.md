# Auxtech Website

Company website and admin panel for **Auxtech** — website development, app development and digital marketing, Dhaka, Bangladesh.

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Database | MySQL / MariaDB (XAMPP locally) |
| Frontend | Vue 3 + TypeScript, Inertia v3, Tailwind CSS v4 |
| Real-time / analytics | Pusher Channels, Microsoft Clarity |
| Motion | Motion for Vue (`motion-v`), GSAP, Lenis |
| Tests | Pest, Vitest, Playwright |

Documentation is in [`docs/`](docs/README.md). **Phase 1 (backend) is complete.** The pages are plain placeholders that show the data they receive; Phase 2 builds the designed interface.

## Local setup (Windows + XAMPP)

**Requirements:** XAMPP with PHP 8.2 or newer, [Composer](https://getcomposer.org), [Node.js 20+](https://nodejs.org).

1. Start **Apache** and **MySQL** in the XAMPP control panel.
2. Create the database in phpMyAdmin (`http://localhost/phpmyadmin`) → SQL:

   ```sql
   CREATE DATABASE auxtech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. In `C:\xampp\php\php.ini`, make sure these extensions are enabled (no leading `;`): `gd`, `intl`, `zip`, `pdo_mysql`, `fileinfo`, `exif`.
4. In the project folder:

   ```bash
   composer run setup
   ```

   This installs PHP and Node packages, creates `.env` from `.env.example` (already set to XAMPP's defaults: host `127.0.0.1`, port `3306`, user `root`, empty password, database `auxtech`), generates the app key, runs migrations and builds assets.

5. Open `.env`, set `ADMIN_PASSWORD` (at least 10 characters) and `PUSHER_APP_SECRET`, then load the starter content (services, the 23 e-commerce modules, 32 projects with screenshots):

   ```bash
   php artisan migrate:fresh --seed
   ```

6. Start everything for development:

   ```bash
   composer run dev
   ```

   Open `http://localhost:8000` for the site and `http://localhost:8000/admin` for the admin panel (sign in with `ADMIN_EMAIL` and `ADMIN_PASSWORD` from `.env`).

If your MySQL has a password or a different port, edit the `DB_*` lines in `.env` and run `php artisan migrate`.

## Production

The site is delivered as one file. Build it with:

```bash
bash scripts/build-release.sh
```

This writes `release/auxtech-production.zip`. On the hosting account: create a MySQL database and user in cPanel, upload the zip to the domain's folder, extract it, and open `/install` in the browser. No SSH, Composer, Node or cron is needed on the server. To update later, extract a newer zip over the old files and press "Update database" in Admin → System. Details: [`docs/02-BACKEND-ARCHITECTURE.md`](docs/02-BACKEND-ARCHITECTURE.md) §11.

The build script needs a Unix shell (Git Bash or WSL on Windows) with `zip`.

## Commands

| Task | Command |
|---|---|
| Dev server + queue + logs + Vite | `composer run dev` |
| Production build | `npm run build` |
| Backend tests | `composer test` |
| Reload starter content | `php artisan migrate:fresh --seed` |
| Build the production zip | `bash scripts/build-release.sh` |
| PHP code style check / fix | `composer lint` / `composer format` |
| Type check, lint, unit tests (JS) | `npm run typecheck`, `npm run lint`, `npm test` |
| End-to-end tests | `npm run test:e2e` (first run: `npx playwright install`) |

## Project layout

```
app/                Laravel application code (admin modules in app/Admin)
config/auxtech.php  Company facts (name, address, WhatsApp number, locales)
docs/               SRS, architecture, design, testing, SEO, phase plans
resources/js/       Vue app (pages/, types/, later components/, layouts/, composables/)
resources/views/    Root template, installer, and plain-HTML fallbacks for crawlers
resources/css/      Tailwind v4 entry with design tokens
resources/brand/    Logo
resources/seed/     Portfolio screenshots used by the seeder
scripts/            Release build and site capture scripts
tests/              Pest (Unit, Feature) and Playwright (e2e)
```

## Known gaps

- **Larastan (PHPStan) is not installed yet.** It could not be downloaded in the environment where the project was scaffolded. On your machine run `composer require --dev larastan/larastan` (first task of Phase 1).
- **Playwright** is pinned to 1.63.0. Firefox and WebKit must be installed locally with `npx playwright install`.
- The SVG logo was traced from the 1125 px WebP. It is accurate at normal sizes; if the designer's original vector file exists, drop it into `resources/brand/` and `public/brand/` for perfectly sharp corners.
- **`auxtechbd.com` is expired** (checked 16 Nov 2026) and must be renewed before launch. See `docs/00-RESEARCH.md` §2.1.
