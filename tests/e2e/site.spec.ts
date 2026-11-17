import { expect, test } from '@playwright/test';

/** Public site journeys. Runs against a freshly seeded database. */

test('home shows the headline, the e-commerce modules and WhatsApp calls to action', async ({ page }) => {
    const errors: string[] = [];
    page.on('pageerror', (error) => errors.push(String(error)));

    await page.goto('/');
    await expect(page.getByRole('heading', { level: 1 })).toContainText('online stores');
    await expect(page.locator('a[href^="https://wa.me/8801805019415"]:visible').first()).toBeVisible();
    await expect(page.getByRole('tab', { name: /Fulfil/ })).toBeVisible();
    await page.getByRole('tab', { name: /Fulfil/ }).click();
    await expect(page.getByRole('button', { name: 'Courier Integration' })).toBeVisible();
    expect(errors).toEqual([]);
});

test('every WhatsApp click is recorded before the chat opens', async ({ page }) => {
    await page.goto('/work/gearzen');
    const beacon = page.waitForRequest((request) => request.url().endsWith('/api/events') && request.method() === 'POST');
    const link = page.getByRole('link', { name: /I want a site like this/ }).first();
    await expect(link).toHaveAttribute('href', /wa\.me\/8801805019415\?text=.*Gearzen/);
    await link.click({ modifiers: ['ControlOrMeta'] });
    // sendBeacon bodies are not exposed to Playwright; the backend test suite checks what is stored.
    expect((await beacon).url()).toMatch(/\/api\/events$/);
});

test('work filters update the list and the address', async ({ page }) => {
    await page.goto('/work');
    await page.getByRole('button', { name: /Gadgets & Electronics/ }).click();
    await expect(page).toHaveURL(/industry=gadgets/);
    await expect(page.getByText('4 projects')).toBeVisible();
    await page.getByRole('button', { name: 'Clear filters' }).click();
    await expect(page).toHaveURL(/\/work$/);
});

test('the contact form stores a lead and offers WhatsApp', async ({ page }) => {
    await page.goto('/contact');
    await page.waitForTimeout(2100); // the spam time trap rejects instant submissions
    await page.getByLabel('Your name').fill('Playwright Visitor');
    await page.getByLabel('Mobile number').fill('017 1111 2222');
    await page.getByRole('button', { name: 'Send message' }).click();
    await expect(page.getByText('Thank you, we got your message.')).toBeVisible();
    await expect(page.getByRole('link', { name: 'Continue on WhatsApp' })).toBeVisible();
});

test('the contact form explains an invalid phone number', async ({ page }) => {
    await page.goto('/contact');
    await page.getByLabel('Your name').fill('Someone');
    await page.getByLabel('Mobile number').fill('12345');
    await page.getByRole('button', { name: 'Send message' }).click();
    await expect(page.getByRole('alert')).toContainText('valid Bangladeshi mobile number');
});

test('the estimator walks through four steps', async ({ page }) => {
    await page.goto('/estimate');
    await page.getByRole('button', { name: 'Auxtech Ecommerce' }).click();
    await page.getByRole('button', { name: 'Continue' }).click();
    await page.getByRole('button', { name: 'Point of Sale (POS)' }).click();
    await page.getByRole('button', { name: 'Continue' }).click();
    await page.getByRole('button', { name: 'Not sure yet' }).click();
    await page.getByRole('button', { name: 'Within a month' }).click();
    await page.getByRole('button', { name: 'Continue' }).click();
    await expect(page.getByText('Where should we send the estimate?')).toBeVisible();
    await expect(page.getByText(/Point of Sale \(POS\)/)).toBeVisible();
});

test('Bangla pages use Bangla text and keep the same page when switching language', async ({ page }) => {
    await page.goto('/work/gearzen');
    await page.locator('header a[hreflang="bn"]').click();
    await expect(page).toHaveURL(/\/bn\/work\/gearzen$/);
    await expect(page.locator('html')).toHaveAttribute('lang', 'bn');
    await expect(page.getByRole('link', { name: 'এমন একটি সাইট চাই' }).first()).toBeVisible();
});

test('page content is readable without JavaScript', async ({ browser }) => {
    const context = await browser.newContext({ javaScriptEnabled: false });
    const page = await context.newPage();
    await page.goto('/solutions/ecommerce');
    await expect(page.getByRole('heading', { level: 1, name: 'Auxtech Ecommerce' })).toBeVisible();
    await expect(page.getByText('Conversions API (CAPI)')).toBeVisible();
    await context.close();
});

test('unknown pages show the branded 404 page', async ({ page }) => {
    const response = await page.goto('/work/does-not-exist');
    expect(response?.status()).toBe(404);
    await expect(page.getByRole('heading', { name: 'Page not found' })).toBeVisible();
});

test('reduced motion leaves all content visible', async ({ browser }) => {
    const context = await browser.newContext({ reducedMotion: 'reduce' });
    const page = await context.newPage();
    await page.goto('/');
    await page.mouse.wheel(0, 4000);
    await expect(page.getByRole('heading', { name: 'How we work' })).toBeVisible();
    await context.close();
});

test('phones get the bottom action bar and a working menu', async ({ page, isMobile }) => {
    test.skip(!isMobile, 'phone layout only');
    await page.goto('/');
    await page.getByRole('button', { name: 'Open menu' }).click();
    await expect(page.getByRole('dialog')).toBeVisible();
    await page.getByRole('dialog').getByRole('link', { name: 'Pricing' }).click();
    await expect(page).toHaveURL(/\/pricing$/);
    await page.mouse.wheel(0, 1200);
    await expect(page.getByRole('navigation', { name: 'Contact' })).toBeVisible();
});
