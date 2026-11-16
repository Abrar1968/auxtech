import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';

/**
 * Single entry for the browser and for server-side rendering.
 * The @inertiajs/vite plugin wraps this call with the SSR bootstrap, so
 * there is no separate ssr.ts. In the browser Inertia hydrates the
 * server-rendered markup, or mounts normally when SSR is not running.
 */
createInertiaApp({
    pages: './pages',
    withApp(app, { ssr, page }) {
        const ziggy = page.props.ziggy as { location: string } & Record<string, unknown>;

        // On the server there is no window.Ziggy, so pass the config explicitly.
        app.use(ZiggyVue, ssr ? { ...ziggy, location: new URL(ziggy.location) } : undefined);
    },
    progress: { color: '#0145FF' },
});
