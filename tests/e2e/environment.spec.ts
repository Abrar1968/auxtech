import { expect, test } from '@playwright/test';

test('home page renders and links to WhatsApp', async ({ page }) => {
    await page.goto('/');

    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    await expect(page.getByRole('link', { name: /whatsapp/i })).toHaveAttribute(
        'href',
        'https://wa.me/8801805019415',
    );
});
