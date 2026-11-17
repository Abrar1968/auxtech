<script setup lang="ts">
/**
 * Phase 1 placeholder: shows the data a page receives so the backend
 * contract can be checked. Phase 2 replaces every page that uses it.
 */
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const shared = ['errors', 'ziggy', 'locale', 'locales', 'flash', 'seo', 'site', 'navigation', 'auth'];
const site = computed(() => page.props.site as { name: string; whatsapp: { url: string } });
const flash = computed(() => page.props.flash as { success?: string; error?: string });
const isAdmin = computed(() => page.component.startsWith('Admin/'));
const own = computed(() => Object.fromEntries(Object.entries(page.props).filter(([key]) => !shared.includes(key))));
</script>

<template>
    <main class="mx-auto max-w-5xl px-4 py-8">
        <p class="text-sm text-(--text-muted)">Phase 1 placeholder · {{ page.component }}</p>
        <h1 class="mt-1 font-display text-2xl font-semibold">{{ (page.props.seo as { title: string }).title }}</h1>

        <p v-if="flash.success" class="mt-4 rounded-lg bg-green-100 px-3 py-2 text-green-900">{{ flash.success }}</p>
        <p v-if="flash.error" class="mt-4 rounded-lg bg-red-100 px-3 py-2 text-red-900">{{ flash.error }}</p>

        <nav class="mt-4 flex flex-wrap gap-3 text-sm text-brand-600">
            <template v-if="isAdmin">
                <Link href="/admin">Dashboard</Link>
                <Link href="/admin/leads">Leads</Link>
                <Link href="/admin/projects">Projects</Link>
                <Link href="/admin/settings">Settings</Link>
                <Link href="/admin/system">System</Link>
                <Link href="/logout" method="post" as="button">Sign out</Link>
            </template>
            <template v-else>
                <Link href="/">Home</Link>
                <Link href="/work">Work</Link>
                <Link href="/services">Services</Link>
                <Link href="/solutions">Solutions</Link>
                <a :href="site.whatsapp.url" target="_blank" rel="noopener">WhatsApp</a>
            </template>
        </nav>

        <slot />

        <pre class="mt-6 overflow-x-auto rounded-xl bg-ink-950 p-4 text-xs leading-relaxed text-white/90">{{ JSON.stringify(own, null, 2) }}</pre>
    </main>
</template>
