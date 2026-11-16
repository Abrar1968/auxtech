import { defineConfig, devices } from '@playwright/test';

const baseURL = process.env.APP_URL ?? 'http://127.0.0.1:8000';

export default defineConfig({
    testDir: './tests/e2e',
    fullyParallel: true,
    forbidOnly: !!process.env.CI,
    retries: process.env.CI ? 2 : 0,
    reporter: process.env.CI ? [['github'], ['html', { open: 'never' }]] : 'list',
    use: {
        baseURL,
        trace: 'off',
        screenshot: 'only-on-failure',
    },
    // Device matrix: docs/04-TESTING.md §6.
    projects: [
        { name: 'desktop-chrome', use: { ...devices['Desktop Chrome'] } },
        { name: 'desktop-firefox', use: { ...devices['Desktop Firefox'] } },
        { name: 'desktop-safari', use: { ...devices['Desktop Safari'] } },
        { name: 'android', use: { ...devices['Pixel 7'] } },
        { name: 'iphone', use: { ...devices['iPhone 14'] } },
        { name: 'tablet', use: { ...devices['iPad (gen 7)'] } },
    ],
    webServer: {
        command: 'php artisan serve --host=127.0.0.1 --port=8000',
        url: baseURL,
        reuseExistingServer: !process.env.CI,
        timeout: 60_000,
    },
});
