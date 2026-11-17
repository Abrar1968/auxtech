import { expect, test } from '@playwright/test';

/**
 * Admin journeys, signed in as the seeded Super Admin. Sign-in is limited to
 * five attempts a minute, so each worker signs in once and reuses the session.
 */
test.describe.configure({ mode: 'serial' });

let state: Awaited<ReturnType<import('@playwright/test').BrowserContext['storageState']>> | undefined;

test.beforeEach(async ({ page, context }) => {
    if (state) {
        await context.addCookies(state.cookies);
        await page.goto('/admin');
        return;
    }

    await page.goto('/login');
    await page.getByLabel('Email').fill(process.env.ADMIN_EMAIL ?? 'auxtechbd@gmail.com');
    await page.locator('input[type=password]').fill(process.env.ADMIN_PASSWORD ?? 'local-dev-password');
    await page.getByRole('button', { name: 'Sign in' }).click();
    await expect(page).toHaveURL(/\/admin$/);
    state = await context.storageState();
});

test('dashboard loads with figures and navigation', async ({ page }) => {
    await expect(page.getByRole('heading', { name: /Hello/ })).toBeVisible();
    await expect(page.getByRole('navigation', { name: 'Admin' }).getByRole('link', { name: 'Projects' })).toBeVisible();
});

test('a new stat created in the admin appears on the home page', async ({ page }) => {
    const label = `Playwright stores ${Date.now()}`;
    await page.goto('/admin/stats/create');
    await page.getByLabel('Value').fill('27');
    await page.getByLabel('Suffix').fill('+');
    await page.getByLabel('Label').fill(label);
    await page.getByRole('button', { name: /Create home stat/ }).click();
    await expect(page.getByText('Home Stat created.')).toBeVisible();

    await page.goto('/');
    await expect(page.getByText(label)).toBeVisible();
});

test('editing a project shows validation errors and saves', async ({ page }) => {
    await page.goto('/admin/projects');
    await page.getByRole('link', { name: 'Gearzen', exact: true }).click();
    await page.locator('#f-title').fill('');
    await page.getByRole('button', { name: 'Save changes' }).click();
    await expect(page.getByText('Please fix the highlighted fields')).toBeVisible();
    await page.locator('#f-title').fill('Gearzen');
    await page.getByRole('button', { name: 'Save changes' }).click();
    await expect(page.getByText('Project saved.')).toBeVisible();
});

test('settings show readable values in every tab', async ({ page }) => {
    await page.goto('/admin/settings');
    for (const tab of ['Company', 'Contact & hours', 'Home page', 'Email']) {
        await page.getByRole('button', { name: tab, exact: true }).click();
        await expect(page.locator('input, textarea').first()).toBeVisible();
    }
    await expect(page.locator('input[value="[object Object]"]')).toHaveCount(0);
});
