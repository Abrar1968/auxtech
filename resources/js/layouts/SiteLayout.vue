<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { defineAsyncComponent, onBeforeUnmount, onMounted, ref } from 'vue';
import FloatingActions from '@/components/site/FloatingActions.vue';
import SiteFooter from '@/components/site/SiteFooter.vue';
import SiteHeader from '@/components/site/SiteHeader.vue';
import { useSeoSync } from '@/composables/useSeoSync';
import { useI18n } from '@/lib/i18n';

// Loaded after first paint: not needed to show the page.
const MobileMenu = defineAsyncComponent(() => import('@/components/site/MobileMenu.vue'));
const ConsentBanner = defineAsyncComponent(() => import('@/components/site/ConsentBanner.vue'));

/** Persistent layout of the public site. */
const page = usePage();
const { t } = useI18n();
const menuOpen = ref(false);
const menuLoaded = ref(false);
let lenis: { raf: (time: number) => void; destroy: () => void; resize: () => void } | null = null;
let frame = 0;
let stopNavigate: (() => void) | undefined;

useSeoSync();

// The banner (and its code) is only needed when marketing tags are configured.
const consentNeeded = !!(page.props.site as { tracking?: { gtm?: string; pixel?: string } }).tracking?.gtm || !!(page.props.site as { tracking?: { pixel?: string } }).tracking?.pixel;

onMounted(async () => {
    stopNavigate = router.on('navigate', () => {
        menuOpen.value = false;
        lenis?.resize();
    });

    // Smooth scrolling on pointer devices only, never with reduced motion.
    const fine = window.matchMedia('(pointer: fine)').matches;
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (fine && !reduced) {
        const { default: Lenis } = await import('lenis');
        lenis = new Lenis({ duration: 1.05, smoothWheel: true });
        const loop = (time: number) => {
            lenis?.raf(time);
            frame = requestAnimationFrame(loop);
        };
        frame = requestAnimationFrame(loop);
    }
});

onBeforeUnmount(() => {
    stopNavigate?.();
    cancelAnimationFrame(frame);
    lenis?.destroy();
});
</script>

<template>
    <a href="#main" class="sr-only z-[100] rounded-full bg-brand-600 px-5 py-3 text-white focus:not-sr-only focus:fixed focus:top-3 focus:left-3">
        {{ t('Skip to content') }}
    </a>
    <SiteHeader @open-menu="(menuLoaded = true), (menuOpen = true)" />
    <MobileMenu v-if="menuLoaded" v-model:open="menuOpen" />
    <main id="main" :key="page.component" class="animate-page-in">
        <slot />
    </main>
    <SiteFooter />
    <FloatingActions />
    <ConsentBanner v-if="consentNeeded" />
</template>
