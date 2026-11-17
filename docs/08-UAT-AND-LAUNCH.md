# 08 — Acceptance and Launch Checklist

For Auxtech staff. Part A is the acceptance test: you try the site the way you will use it, and sign off. Part B is the launch, step by step, with no command line needed. Results from the automated testing are in [`07-TEST-REPORT.md`](07-TEST-REPORT.md).

## Part A — User acceptance test

Do this on a staging copy or on the live site before announcing it. Use at least one Android phone, one iPhone and one laptop (Chrome plus Safari or Firefox).

### A1. Content (Admin panel)

- [ ] Sign in at `/admin`. Turn on two-step sign-in under **Account** (an authenticator app such as Google Authenticator).
- [ ] **Settings**: check company name, address, phone, WhatsApp number, email, office hours, Facebook link and map link. Fill in the Bangla tab for anything visitors read.
- [ ] **Settings**: save the Google app password for auxtechbd@gmail.com, then **System > Send a test email** and confirm it arrives.
- [ ] Add your real **statistics**, **testimonials** (real clients only) and **prices**. Sections with nothing in them stay hidden.
- [ ] Open one real project, check the screenshots, text and live link; add the Bangla text.
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

### B1. Before uploading

- [ ] **Renew the domain auxtechbd.com** and point it to the hosting account (it has expired).
- [ ] In cPanel, issue the free SSL certificate (AutoSSL / Let's Encrypt) for the domain, and for `www`.
- [ ] In cPanel > MySQL Databases: create a database and a user, give the user all privileges on it. Note the names and password.

### B2. Install

1. Upload `auxtech-production.zip` to the domain's folder (usually `public_html`) with File Manager and **Extract** it there.
2. Open `https://auxtechbd.com/install`. Enter the website address (with `https://`), the database details, your admin name, email and password, and the Gmail app password (optional here, can be added later).
3. When it says **Your website is installed**, sign in at `/admin`.

The installer locks itself afterwards. Private files (`.env`, `vendor`, `storage`) are blocked by the included `.htaccess`.

### B3. Settings after install

- [ ] Settings > Search & analytics: Google Tag Manager ID, Meta Pixel ID and Conversions API token (if you use them), Search Console and Bing verification codes. The Clarity project ID `zehuflmwqj` is already set.
- [ ] Decide on **Clarity cookies**. In the Clarity dashboard (Settings > Setup > Cookies): *on* (default) gives full multi-page sessions but Lighthouse marks third-party cookies; *off* records without cookies until a visitor accepts the cookie banner (the site already passes the choice to Clarity). The banner only appears once GTM or the Meta Pixel is set.
- [ ] Settings > Security: turn on "Require two-factor sign-in for Super Admins and Admins" once everyone has set it up.

### B4. Launch-day smoke test

- [ ] Home, Work, one case study, Auxtech Ecommerce and Contact open over `https://` in English and Bangla.
- [ ] `https://auxtechbd.com/.env` shows **Forbidden** (403).
- [ ] Send a real test lead: it appears in the admin, the email arrives, WhatsApp opens with the right number.
- [ ] `/sitemap.xml`, `/robots.txt` and `/llms.txt` open. `robots.txt` ends with a `Sitemap:` line.
- [ ] Admin dashboard shows the green "Live" badge (Pusher).
- [ ] In the Clarity dashboard, a recording of your visit appears within about an hour.

### B5. Search engines

- [ ] Google Search Console: add the domain, verify, submit `https://auxtechbd.com/sitemap.xml`, request indexing for the home page.
- [ ] Bing Webmaster Tools: import from Search Console (this also feeds ChatGPT search and Copilot).
- [ ] Google Business Profile: name, address and phone exactly as on the site (`754/1, 5th Floor, West Shewrapara, Begum Rokeya Avenue, Sharini 1216, Dhaka`; 01805019415).
- [ ] Test the home page and one case study in Google's Rich Results Test.

### B6. After launch

- [ ] Backups: cPanel > Backup, download a full backup weekly (files and database). The `public/uploads` folder holds your images.
- [ ] Free uptime monitor (for example UptimeRobot) on the home page, alerting auxtechbd@gmail.com.
- [ ] Weekly: Admin > System shows no failed background tasks.
- [ ] Updating to a new version later: extract the new zip over the old files, then **Admin > System > Update database**. Your settings, uploads and leads are kept.
