import { expect, test } from '@playwright/test';

test('home page renders and links to WhatsApp', async ({ page }) => {
    await page.goto('/');

    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    await expect(page.getByRole('link', { name: /whatsapp/i }).first()).toHaveAttribute(
        'href',
        /^https:\/\/wa\.me\/8801805019415\?text=/,
    );
});

test('page content is readable without JavaScript', async ({ browser }) => {
    const context = await browser.newContext({ javaScriptEnabled: false });
    const page = await context.newPage();
    await page.goto('/solutions/ecommerce');

    await expect(page.getByRole('heading', { level: 1, name: 'Auxtech Ecommerce' })).toBeVisible();
    await expect(page.getByText('Conversions API (CAPI)')).toBeVisible();
    await context.close();
});

test('an admin can sign in and reach the dashboard', async ({ page }) => {
    await page.goto('/login');
    await page.getByLabel('Email').fill(process.env.ADMIN_EMAIL ?? 'auxtechbd@gmail.com');
    await page.getByLabel('Password').fill(process.env.ADMIN_PASSWORD ?? 'local-dev-password');
    await page.getByRole('button', { name: 'Sign in' }).click();

    await expect(page).toHaveURL(/\/admin$/);
    await expect(page.getByText('Admin/Dashboard')).toBeVisible();
});
