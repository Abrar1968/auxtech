#!/usr/bin/env node
/**
 * SEO crawl (docs/04-TESTING.md §8). Reads the sitemap and checks the HTML the
 * server sends, which is what search engines and AI crawlers see.
 *
 *   node scripts/seo-crawl.mjs http://127.0.0.1:8000
 *
 * Exits 1 when an error is found; warnings are listed but do not fail.
 */
const base = (process.argv[2] ?? process.env.APP_URL ?? 'http://127.0.0.1:8000').replace(/\/$/, '');

const errors = [];
const warnings = [];
const titles = new Map();
const descriptions = new Map();
const nap = new Map();

const decode = (s) =>
    s.replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&#039;|&#39;/g, "'").replace(/&lt;/g, '<').replace(/&gt;/g, '>');
const attr = (tag, name) => decode(tag.match(new RegExp(`${name}="([^"]*)"`, 'i'))?.[1] ?? '');
const text = (html) => decode(html.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim());

async function get(url) {
    const response = await fetch(url, { redirect: 'manual', headers: { 'User-Agent': 'AuxtechSeoCrawl/1.0' } });
    return { status: response.status, headers: response.headers, body: await response.text() };
}

function check(url, html) {
    const fail = (message) => errors.push(`${url}: ${message}`);
    const warn = (message) => warnings.push(`${url}: ${message}`);
    const head = html.split('</head>')[0];

    const title = text(head.match(/<title>([\s\S]*?)<\/title>/i)?.[1] ?? '');
    if (!title) fail('no <title>');
    else if (title.length > 65) warn(`title is ${title.length} characters`);
    titles.set(title, [...(titles.get(title) ?? []), url]);

    const description = attr(head.match(/<meta name="description"[^>]*>/i)?.[0] ?? '', 'content');
    if (!description) fail('no meta description');
    else if (description.length < 50 || description.length > 170) warn(`description is ${description.length} characters`);
    descriptions.set(description, [...(descriptions.get(description) ?? []), url]);

    const canonical = attr(head.match(/<link rel="canonical"[^>]*>/i)?.[0] ?? '', 'href');
    if (canonical !== url) fail(`canonical is "${canonical}"`);

    const hreflangs = [...head.matchAll(/<link rel="alternate" hreflang="([^"]+)"/gi)].map((m) => m[1]).sort();
    if (hreflangs.join() !== 'bn,en,x-default') fail(`hreflang set is [${hreflangs}]`);

    if (/<meta name="robots" content="[^"]*noindex/i.test(head)) fail('marked noindex but listed in the sitemap');
    if (!/<meta property="og:image"/i.test(head)) warn('no og:image');

    const body = html.split('</head>')[1] ?? '';
    const h1s = body.match(/<h1[\s>]/gi) ?? [];
    if (h1s.length !== 1) fail(`${h1s.length} <h1> elements`);

    for (const img of body.match(/<img\b[^>]*>/gi) ?? []) {
        if (!/\balt="/i.test(img)) fail(`image without alt: ${attr(img, 'src')}`);
    }

    const blocks = [...html.matchAll(/<script type="application\/ld\+json"[^>]*>([\s\S]*?)<\/script>/gi)];
    if (!blocks.length) fail('no JSON-LD');
    for (const [, json] of blocks) {
        try {
            const data = JSON.parse(json);
            const nodes = data['@graph'] ?? [data];
            for (const node of nodes) {
                if (node.telephone) nap.set(`${node.telephone} | ${node.address?.streetAddress ?? ''}`, url);
            }
            if (!nodes.some((node) => [node['@type']].flat().includes('BreadcrumbList')) && new URL(url).pathname.split('/').filter(Boolean).length > 1) {
                warn('no BreadcrumbList');
            }
        } catch (error) {
            fail(`JSON-LD does not parse: ${error.message}`);
        }
    }

    if (!/data-fallback/.test(body)) fail('no server-rendered content for crawlers');
}

const sitemap = await get(`${base}/sitemap.xml`);
if (sitemap.status !== 200) {
    console.error(`sitemap.xml returned ${sitemap.status}`);
    process.exit(1);
}
const urls = [...sitemap.body.matchAll(/<loc>([^<]+)<\/loc>/g)].map((m) => decode(m[1]));

for (const url of urls) {
    const page = await get(url);
    if (page.status !== 200) {
        errors.push(`${url}: status ${page.status}${page.headers.get('location') ? ` → ${page.headers.get('location')}` : ''}`);
        continue;
    }
    check(url, page.body);
}

/*
 * A Bangla page whose content has not been translated yet repeats the English
 * title and description. hreflang ties the pair together, so this is reported
 * once as a to-do rather than as an error.
 */
const englishOf = (url) => url.replace(`${base}/bn/`, `${base}/`).replace(new RegExp(`^${base}/bn$`), base);
const isLanguagePair = (pages) => pages.length === 2 && englishOf(pages[0]) === englishOf(pages[1]);
const untranslated = new Set();

for (const [title, pages] of titles) {
    if (pages.length < 2) continue;
    if (isLanguagePair(pages)) untranslated.add(englishOf(pages[0]));
    else errors.push(`duplicate title "${title}" on ${pages.join(', ')}`);
}
for (const [description, pages] of descriptions) {
    if (!description || pages.length < 2) continue;
    if (isLanguagePair(pages)) untranslated.add(englishOf(pages[0]));
    else warnings.push(`duplicate description on ${pages.join(', ')}`);
}
if (untranslated.size) {
    warnings.push(`${untranslated.size} Bangla pages repeat the English title or description (content not translated yet), e.g. ${[...untranslated].slice(0, 3).join(', ')}`);
}
if (nap.size > 1) errors.push(`company phone/address differs between pages: ${[...nap.keys()].join(' / ')}`);

for (const path of ['/robots.txt', '/llms.txt', '/llms-full.txt', '/feed.xml']) {
    const { status } = await get(base + path);
    if (status !== 200) errors.push(`${path}: status ${status}`);
}
const robots = (await get(`${base}/robots.txt`)).body;
if (!robots.includes(`Sitemap: ${base}/sitemap.xml`)) errors.push('robots.txt does not point to the sitemap');
for (const bot of ['GPTBot', 'ClaudeBot', 'PerplexityBot', 'Google-Extended']) {
    if (!robots.includes(bot)) warnings.push(`robots.txt does not mention ${bot}`);
}

console.log(`Crawled ${urls.length} URLs from the sitemap.`);
for (const warning of warnings) console.log(`warning  ${warning}`);
for (const error of errors) console.log(`ERROR    ${error}`);
console.log(`${errors.length} errors, ${warnings.length} warnings.`);
process.exit(errors.length ? 1 : 0);
