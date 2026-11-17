<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';

/**
 * Consent for marketing tags (Google Tag Manager, Meta Pixel), using Google
 * Consent Mode v2, also passed to Microsoft Clarity (consentv2), which
 * records without cookies until consent when its project's cookie setting is
 * off. Shown only when GTM or the Pixel is configured in Settings.
 */
const { site } = useShared();
const { t } = useI18n();
const show = ref(false);
const KEY = 'aux-consent';

function loadTags(granted: boolean): void {
    const w = window as Window & { gtag?: (...args: unknown[]) => void; _fbq?: unknown; clarity?: (...args: unknown[]) => void };
    w.dataLayer = w.dataLayer || [];
    w.gtag = function gtag() {
        w.dataLayer!.push(arguments as unknown as Record<string, unknown>);
    };
    const state = granted ? 'granted' : 'denied';
    w.gtag('consent', 'default', { ad_storage: state, analytics_storage: state, ad_user_data: state, ad_personalization: state });
    w.clarity?.('consentv2', { ad_Storage: state, analytics_Storage: state });

    if (!granted) {
        return;
    }

    const { gtm, pixel } = site.value.tracking;

    if (gtm && !document.getElementById('gtm-script')) {
        w.dataLayer.push({ 'gtm.start': Date.now(), event: 'gtm.js' });
        const script = document.createElement('script');
        script.id = 'gtm-script';
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtm.js?id=${encodeURIComponent(gtm)}`;
        document.head.appendChild(script);
    }

    if (pixel && !w.fbq) {
        /* Meta Pixel base code */
        const fbq = function (...args: unknown[]) {
            (fbq as unknown as { queue: unknown[] }).queue.push(args);
        } as unknown as ((...args: unknown[]) => void) & { queue: unknown[]; loaded: boolean; version: string; push: unknown };
        fbq.queue = [];
        fbq.loaded = true;
        fbq.version = '2.0';
        fbq.push = fbq;
        w.fbq = fbq;
        w._fbq = fbq;
        const script = document.createElement('script');
        script.async = true;
        script.src = 'https://connect.facebook.net/en_US/fbevents.js';
        document.head.appendChild(script);
        fbq('init', pixel);
        fbq('track', 'PageView');
    }
}

function choose(granted: boolean): void {
    try {
        localStorage.setItem(KEY, granted ? 'granted' : 'denied');
    } catch {
        // Storage may be blocked.
    }

    show.value = false;
    loadTags(granted);
}

onMounted(() => {
    const { gtm, pixel } = site.value.tracking;

    if (!gtm && !pixel) {
        return;
    }

    let saved: string | null = null;

    try {
        saved = localStorage.getItem(KEY);
    } catch {
        // Storage may be blocked.
    }

    if (saved) {
        loadTags(saved === 'granted');
    } else {
        setTimeout(() => (show.value = true), 1200);
    }
});
</script>

<template>
    <Transition enter-active-class="transition duration-500 ease-out-expo" enter-from-class="opacity-0 translate-y-6" leave-active-class="transition duration-200" leave-to-class="opacity-0">
        <div v-if="show" role="dialog" :aria-label="t('Cookie preferences')" class="fixed inset-x-3 bottom-20 z-50 sm:right-auto sm:bottom-5 sm:left-5 sm:max-w-sm">
            <div class="glass rounded-2xl p-5 shadow-[0_20px_60px_-20px_rgb(5_8_22/0.45)]">
                <p class="text-sm text-fg">{{ t('We use cookies to measure visits and improve our ads. You can say no; the site works the same.') }}</p>
                <div class="mt-4 flex gap-2">
                    <Button size="sm" @click="choose(true)">{{ t('Accept') }}</Button>
                    <Button size="sm" variant="secondary" @click="choose(false)">{{ t('No thanks') }}</Button>
                </div>
            </div>
        </div>
    </Transition>
</template>
