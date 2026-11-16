// Research crawl: capture title/meta/headings + screenshot for each client site.
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const out = process.argv[2];
const sites = [
  ['fablixbd', 'https://fablixbd.com/'], ['ventirebd', 'https://ventirebd.com/'],
  ['signaturemartbd', 'https://signaturemartbd.com/'], ['queensclubbd', 'https://queensclubbd.com/'],
  ['nokshacraft', 'https://nokshacraft.com/'], ['weeshopbd', 'https://weeshopbd.com/'],
  ['kalki', 'https://kalki.auxdemo.com/'], ['thepatchee', 'https://thepatchee.auxtechsoftware.com/'],
  ['dhakaculture', 'https://dhakaculture.com/'], ['lavieworld', 'https://lavieworld.auxtechsoftware.com/'],
  ['technmorebd', 'https://technmorebd.com/'], ['gearzen', 'https://gearzen.com.bd/'],
  ['unigear', 'https://unigear.com.bd/'], ['gadgetpanelbd', 'https://gadgetpanelbd.com/'],
  ['bottolabazar', 'https://bottolabazar.com/'], ['greenlandmarketingcompany', 'https://greenlandmarketingcompany.com/'],
  ['bikolpoo', 'https://bikolpoo.com/'], ['cartexbd', 'https://cartexbd.com/'],
  ['gulshanbakingshop', 'https://gulshanbakingshop.com/'], ['bdfreshbazar', 'https://bdfreshbazar.com/'],
  ['seasonalbd', 'https://seasonalbd.com/'], ['seasonal-demo', 'https://seasonal.auxtechsoftware.com/'],
  ['auxify', 'https://auxify.store/'], ['resellerv2', 'https://resellerv2.auxtechsoftware.com/'],
  ['rongtorongob2b', 'https://rongtorongob2b.com/'], ['ehubdropshop', 'https://ehubdropshop.com/'],
  ['asthareseller', 'https://asthareseller.com/'], ['wtbl', 'https://wtbl.com.bd/'],
  ['greenland-tax', 'https://tax.greenlandmarketingcompany.com/'], ['e7training', 'https://e7training.com/'],
  ['sstyping', 'https://sstyping.com/'], ['cleanbdx', 'https://cleanbdx.com/'],
];

(async () => {
  fs.mkdirSync(path.join(out, 'shots'), { recursive: true });
  const browser = await chromium.launch();
  const results = [];
  const queue = [...sites];
  async function worker() {
    while (queue.length) {
      const [slug, url] = queue.shift();
      const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1, ignoreHTTPSErrors: true });
      const page = await ctx.newPage();
      const r = { slug, url };
      try {
        const resp = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 45000 });
        r.status = resp && resp.status();
        await page.waitForLoadState('networkidle', { timeout: 15000 }).catch(() => {});
        await page.waitForTimeout(2500);
        Object.assign(r, await page.evaluate(() => {
          const m = (s) => document.querySelector(s)?.getAttribute('content') || null;
          const txt = (s, n) => [...document.querySelectorAll(s)].map(e => e.innerText.trim().replace(/\s+/g, ' ')).filter(Boolean).slice(0, n);
          return {
            finalUrl: location.href, title: document.title, description: m('meta[name="description"]'),
            ogImage: m('meta[property="og:image"]'), lang: document.documentElement.lang,
            h1: txt('h1', 3), h2: txt('h2', 8), nav: txt('nav a, header a', 25),
            footer: (document.querySelector('footer')?.innerText || '').replace(/\s+/g, ' ').slice(0, 500),
            body: document.body.innerText.replace(/\s+/g, ' ').slice(0, 900),
          };
        }));
        await page.screenshot({ path: path.join(out, 'shots', slug + '.png') });
      } catch (e) { r.error = String(e).slice(0, 200); }
      results.push(r);
      await ctx.close();
      console.log(slug, r.status || r.error);
    }
  }
  await Promise.all([worker(), worker(), worker(), worker()]);
  await browser.close();
  fs.writeFileSync(path.join(out, 'sites.json'), JSON.stringify(results, null, 1));
})();
