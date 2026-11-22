import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { defineAsyncComponent } from 'vue';
import SiteLayout from './layouts/SiteLayout.vue';

/**
 * Browser entry. Public pages use SiteLayout (bundled here, needed on first
 * paint); the admin layout loads on demand so it never reaches visitors.
 */
const AdminLayout = defineAsyncComponent(() => import('./layouts/AdminLayout.vue'));

const start = () =>
    createInertiaApp({
        pages: './pages',
        layout: (name: string) => {
            if (name.startsWith('Site/')) return SiteLayout;
            if (name.startsWith('Admin/')) return AdminLayout;
            return undefined;
        },
        progress: { color: '#ffc93c', delay: 150 },
    });

// When the server printed an app shell, let the browser paint it before Vue
// replaces it (two frames), so the first and largest paint never wait for the bundle.
if (document.querySelector('[data-shell]')) {
    requestAnimationFrame(() => requestAnimationFrame(() => setTimeout(start, 0)));
} else {
    start();
}
