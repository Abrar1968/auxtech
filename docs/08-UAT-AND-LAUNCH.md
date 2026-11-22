# 08 — Acceptance and Launch Checklist

For Auxtech staff. Part A is the acceptance test: you try the site the way you will use it, and sign off. Part B is the launch, step by step, with no command line needed. Results from the automated testing are in [`07-TEST-REPORT.md`](07-TEST-REPORT.md).

## Part A — User acceptance test

Do this on a staging copy or on the live site before announcing it. Use at least one Android phone, one iPhone and one laptop (Chrome plus Safari or Firefox).

### A1. Content (Admin panel)

- [ ] Sign in at `/admin`. Turn on two-step sign-in under **Account** (an authenticator app such as Google Authenticator).
- [ ] **Settings**: check company name, address, phone, WhatsApp number, email, office hours, Facebook link and map link. Fill in the Bangla tab for anything visitors read.
- [ ] **Settings**: save the Google app password for auxtechbd@gmail.com, then **System > Send a test email** and confirm it arrives.
- [ ] Add your real **statistics**, **testimonials** (real clients only) and **prices**. Sections with nothing in them stay hidden.
- [ ] Open a few projects and check the screenshots, text, technologies and live link. Every project text was written from the live site; correct anything that has changed and add the Bangla for the challenge and result fields.
- [ ] Write and publish one blog post with a cover image.
- [ ] Translate the Bangla titles and descriptions of services, solutions and projects (the SEO crawl found 46 pages still showing English text on the Bangla side).
- [ ] Look through every page in English and Bangla for placeholder or wrong text.

### A2. A visitor's journey (on a phone)

- [ ] Home → a case study → "I want a site like this" opens WhatsApp with the project named in the message.
- [ ] The green WhatsApp button and the bottom bar work on every page.
- [ ] Contact form: send a real test message. It appears in **Admin > Leads** within seconds (with a sound), and the email arrives.
- [ ] Estimator: go through all four steps; the lead shows your answers.
- [ ] Switch to Bangla and back; you stay on the same page.
- [ ] Dark theme: switch it on (moon icon) and read a few pages.

### A3. Sales

- [ ] Move the test lead through New → Contacted → Won, add a note, assign it to a salesperson.
- [ ] Export leads to CSV and open it in Excel.
- [ ] Delete or mark the test leads as spam.

### A4. Accessibility spot check

- [ ] Use the site with the keyboard only (Tab, Enter): you can reach every link and the focus is always visible.
- [ ] Try TalkBack (Android) or VoiceOver (iPhone) on the home and contact pages.

### Sign-off

| Name | Role | Date | Result and notes |
|---|---|---|---|
| | | | |

## Part B — Launch

### Updating the live site (auxtechsoftware.com)

The zip's contents go straight into `public_html` (no folder inside the zip). Your uploads (`public/uploads`) are not in the zip, so extracting never touches them.

1. In File Manager, open `public_html` and **download a copy of `.env`** (and of `.htaccess` if cPanel added a PHP-version block to it).
2. Delete leftovers that are not part of the site: the folder `auxtech-production (1)` and `node_modules`.
3. Upload `auxtech-production.zip` into `public_html`, select it, **Extract**, and allow it to overwrite. Delete the zip afterwards.
4. Put your own `.env` back (upload or edit `public_html/.env` and paste your production values). From 1.4.0 the site keeps a backup of it in `storage/.env`: if a later update replaces `.env` with the empty template, the site keeps running on the backup. Edits you make to `public_html/.env` always take effect.
5. If cPanel had added a PHP handler to `.htaccess`, open **MultiPHP Manager**, select auxtechsoftware.com and apply PHP 8.2 (or newer) again; it writes the block back.
6. Sign in at `/admin` and run **System > Update database**, then **Clear caches**.
7. In cPanel **Terminal**, inside `public_html`: `php artisan auxtech:seed` (adds the new projects, the Automotive industry and the pricing packages; keeps your edits), then upload and extract the new image pack outside `public_html` and run `php artisan auxtech:seed --images=/home/YOUR-CPANEL-USER/auxtech-image-pack` (adds only images that are missing). Add `--overwrite` to the first command only if you want the rewritten project texts.


### B1. Before uploading

- [ ] The site lives at **auxtechsoftware.com** (the cPanel account's main domain, `public_html`). If `auxtechsoftware.com` is renewed later, add it as an alias or redirect to auxtechsoftware.com: about 20 client sites link to it.
- [ ] In cPanel, issue the free SSL certificate (AutoSSL / Let's Encrypt) for the domain, and for `www`.
- [ ] In cPanel > MySQL Databases: create a database and a user, give the user all privileges on it. Note the names and password.

### B2. Install

1. Upload `auxtech-production.zip` to the domain's folder (usually `public_html`) with File Manager and **Extract** it there.
2. Open `https://auxtechsoftware.com/install`. Enter the website address (with `https://`), the database details, your admin name, email and password, and the Gmail app password (optional here, can be added later).
3. When it says **Your website is installed**, sign in at `/admin`.

The installer locks itself afterwards. The zip holds the whole project, dotfiles included (`.htaccess`, `.env`, `.env.production`); turn on "Show hidden files" in File Manager to see them. Private files (`.env`, `vendor`, `storage`, source, docs) are blocked by the included `.htaccess`.

**Alternative: import the prepared database.** Before step 2, open the empty database in cPanel > phpMyAdmin and **Import** `auxtech-database.sql`, then extract `auxtech-uploads.zip` inside `public_html/public` (it creates `public/uploads`). The installer keeps the imported content and only creates your admin account, and every image is already in place, so B2a is not needed.

The installer also seeds the whole site's text: 6 services with sub-services, 6 platforms with the 23 Auxtech Ecommerce modules, 12 industries, 7 technologies, all 38 projects with their case studies (30 delivered sites and products, 8 labelled demos), 8 FAQs and 3 engagement packages on the pricing page (shown as "Custom quote" until you enter prices in Admin > Pricing). The same content can be added again at any time from cPanel Terminal with `php artisan auxtech:seed` (it only adds what is missing and keeps your edits; `--overwrite` restores the original text).

### B2a. Images

Images are not part of the site zip. Unzip `auxtech-image-pack.zip` on your computer and open `UPLOAD-GUIDE.html`. It lists all 255 images (37 client logos, 190 project screenshots, 6 platform covers and 22 Auxtech Ecommerce module screenshots) with the exact admin page, field and alt text for each. Either:

- **Option A:** upload them one by one in the admin panel as the guide shows, or
- **Option B:** upload and extract the pack outside `public_html` and run `php artisan auxtech:seed --images=/home/YOUR-CPANEL-USER/auxtech-image-pack` in cPanel Terminal. It attaches everything with alt text and skips fields that already have an image.

Until the images are uploaded, pages show client names instead of logos and empty screenshot frames.

**Alternative: import the prepared database and images.** Import `auxtech-database.sql` in phpMyAdmin before running the installer, and extract `auxtech-uploads.zip` inside `public_html/public`. Every image is then already linked (see B2).

**Upgrading an existing install to 1.4.0:** extract `auxtech-update.zip` (not the full zip, which contains a template `.env`) over the old files, run Admin > System > Update database, then in cPanel Terminal run `php artisan auxtech:seed` (adds the 5 new projects, the Automotive industry and the pricing packages) and `php artisan auxtech:seed --images=...` with the new image pack (adds only the new images). Add `--overwrite` to the first command only if you want the rewritten project texts for the demos; it replaces admin edits to seeded records.

### B3. Settings after install

- [ ] Settings > Search & analytics: Google Tag Manager ID, Meta Pixel ID and Conversions API token (if you use them), Search Console and Bing verification codes. The Clarity project ID `zehuflmwqj` is already set.
- [ ] Decide on **Clarity cookies**. In the Clarity dashboard (Settings > Setup > Cookies): *on* (default) gives full multi-page sessions but Lighthouse marks third-party cookies; *off* records without cookies until a visitor accepts the cookie banner (the site already passes the choice to Clarity). The banner only appears once GTM or the Meta Pixel is set.
- [ ] Settings > Security: turn on "Require two-factor sign-in for Super Admins and Admins" once everyone has set it up.

### B4. Launch-day smoke test

- [ ] Home, Work, one case study, Auxtech Ecommerce and Contact open over `https://` in English and Bangla.
- [ ] `https://auxtechsoftware.com/.env` shows **Forbidden** (403).
- [ ] Send a real test lead: it appears in the admin, the email arrives, WhatsApp opens with the right number.
- [ ] `/sitemap.xml`, `/robots.txt` and `/llms.txt` open. `robots.txt` ends with a `Sitemap:` line.
- [ ] Admin dashboard shows the green "Live" badge (Pusher).
- [ ] In the Clarity dashboard, a recording of your visit appears within about an hour.

### B5. Search engines

- [ ] Google Search Console: add the domain, verify, submit `https://auxtechsoftware.com/sitemap.xml`, request indexing for the home page.
- [ ] Bing Webmaster Tools: import from Search Console (this also feeds ChatGPT search and Copilot).
- [ ] Google Business Profile: name, address and phone exactly as on the site (`754/1, 5th Floor, West Shewrapara, Begum Rokeya Avenue, Sharini 1216, Dhaka`; 01805019415).
- [ ] Test the home page and one case study in Google's Rich Results Test.

### B6. After launch

- [ ] Backups: cPanel > Backup, download a full backup weekly (files and database). The `public/uploads` folder holds your images.
- [ ] Free uptime monitor (for example UptimeRobot) on the home page, alerting auxtechbd@gmail.com.
- [ ] Weekly: Admin > System shows no failed background tasks.
- [ ] Updating to a new version later: extract the new zip over the old files, then **Admin > System > Update database**. Your settings, uploads and leads are kept. From 1.4.0 the site's settings live in `storage/.env`, so the template `.env` inside the zip cannot overwrite them. **A site installed with 1.3.0 or earlier** must be updated once with `auxtech-update.zip` (the same files without `.env`); after that, either zip is safe.
