import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { defineAsyncComponent } from 'vue';
import SiteLayout from './layouts/SiteLayout.vue';

/**
 * Browser entry. Public pages use SiteLayout (bundled here, needed on first
 * paint); the admin layout loads on demand so it never reaches visitors.
 */
const AdminLayout = defineAsyncComponent(() => import('./layouts/AdminLayout.vue'));

createInertiaApp({
    pages: './pages',
    layout: (name: string) => {
        if (name.startsWith('Site/')) return SiteLayout;
        if (name.startsWith('Admin/')) return AdminLayout;
        return undefined;
    },
    progress: { color: '#2f61ff', delay: 150 },
});
