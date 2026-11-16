import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';

/**
 * Browser entry. Production runs without Node, so there is no SSR:
 * crawlers read the Blade content fallback and Vue mounts over it.
 */
createInertiaApp({
    pages: './pages',
    withApp(app) {
        app.use(ZiggyVue);
    },
    progress: { color: '#0145FF' },
});
