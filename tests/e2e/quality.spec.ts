import AxeBuilder from '@axe-core/playwright';
import { expect, test, type Page } from '@playwright/test';

/**
 * Responsive and accessibility sweep over one page of every public type
 * (docs/04-TESTING.md §6 and §7.2). Runs once, on desktop Chromium; the
 * viewport is set per check.
 */
const pages = [
    '/',
    '/work',
    '/work/gearzen',
    '/services',
    '/services/website-development',
    '/solutions',
    '/solutions/ecommerce',
    '/industries/fashion',
    '/pricing',
    '/about',
    '/faq',
    '/contact',
    '/estimate',
    '/blog',
    '/no-such-page',
    '/bn',
    '/bn/work/gearzen',
    '/bn/contact',
];

const widths = [320, 360, 768, 1280, 1920];

test.beforeEach(({}, testInfo) => {
    test.skip(testInfo.project.name !== 'desktop-chrome', 'viewport is set by the test itself');
});

/** Scroll through the page so lazy sections and reveal animations run. */
async function settle(page: Page) {
    await page.evaluate(async () => {
        for (let y = 0; y < document.body.scrollHeight; y += window.innerHeight / 2) {
            window.scrollTo(0, y);
            await new Promise((resolve) => setTimeout(resolve, 40));
        }
        window.scrollTo(0, 0);
    });
    await page.waitForTimeout(300);
}

for (const path of pages) {
    test(`no sideways scrolling on ${path}`, async ({ page }) => {
        test.setTimeout(90_000);
        for (const width of widths) {
            await page.setViewportSize({ width, height: 900 });
            await page.goto(path);
            await settle(page);

            const overflow = await page.evaluate(() => {
                const limit = document.documentElement.clientWidth;
                const wide = [...document.querySelectorAll<HTMLElement>('body *')]
                    .filter((el) => {
                        const box = el.getBoundingClientRect();
                        // Elements inside a clipping or scrolling container (marquees, carousels) are fine.
                        return box.width > 0 && box.right > limit + 1 && !el.closest('[class*="overflow-"], .mask-fade-x');
                    })
                    .slice(0, 3)
                    .map((el) => `${el.tagName.toLowerCase()}.${String(el.className).slice(0, 60)}`);

                // Controls in the fixed header are clipped rather than scrolled, so check them directly.
                const clipped = [...document.querySelectorAll<HTMLElement>('header a, header button')]
                    .filter((el) => {
                        const box = el.getBoundingClientRect();
                        return box.width > 0 && (box.left < 0 || box.right > limit + 1);
                    })
                    .map((el) => el.getAttribute('aria-label') ?? el.textContent?.trim() ?? el.tagName);

                return { scroll: document.documentElement.scrollWidth, limit, wide, clipped };
            });

            expect(overflow.scroll, `${path} at ${width}px: ${overflow.wide.join(', ')}`).toBeLessThanOrEqual(overflow.limit);
            expect(overflow.clipped, `${path} at ${width}px: header controls off-screen`).toEqual([]);
        }
    });
}

for (const theme of ['light', 'dark'] as const) {
    test.describe(`accessibility, ${theme} theme`, () => {
        test.use({ reducedMotion: 'reduce' });

        for (const path of pages) {
            test(`axe finds no WCAG 2.2 AA problems on ${path}`, async ({ page }) => {
                await page.addInitScript((value) => localStorage.setItem('aux-theme', value), theme);
                await page.goto(path);
                await settle(page);

                const results = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa']).analyze();
                const problems = results.violations.map((v) => `${v.id}: ${v.nodes.map((n) => n.target.join(' ')).slice(0, 3).join(' | ')}`);

                expect(problems).toEqual([]);
            });
        }
    });
}
