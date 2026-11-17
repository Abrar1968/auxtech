<script setup lang="ts">
import { Calculator, Mail } from '@lucide/vue';
import { Link } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';
import { track } from '@/lib/tracking';

/**
 * Always-available contact: a floating WhatsApp button on larger screens
 * and a bottom action bar on phones (WhatsApp, email, estimate).
 */
const { site } = useShared();
const { t, lp, locale } = useI18n();
const visible = ref(false);
const hint = ref(false);

function onScroll(): void {
    visible.value = window.scrollY > 480;
}

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    try {
        if (!sessionStorage.getItem('aux-hint')) {
            setTimeout(() => (hint.value = true), 9000);
            setTimeout(() => (hint.value = false), 17000);
            sessionStorage.setItem('aux-hint', '1');
        }
    } catch {
        // Storage may be blocked.
    }
});
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));

const mailto = computed(() => `mailto:${site.value.email}?subject=${encodeURIComponent(t('Project enquiry from the website'))}`);
</script>

<template>
    <!-- Desktop and tablet -->
    <div class="fixed right-5 bottom-5 z-40 hidden items-center gap-3 sm:flex">
        <Transition enter-active-class="transition duration-500 ease-out-expo" enter-from-class="opacity-0 translate-x-3" leave-active-class="transition duration-200" leave-to-class="opacity-0">
            <p v-if="hint" class="glass rounded-2xl px-4 py-2.5 text-sm font-medium text-fg shadow-lg">{{ t('Questions? We reply on WhatsApp.') }}</p>
        </Transition>
        <a
            :href="site.whatsapp.url"
            target="_blank"
            rel="noopener"
            :aria-label="t('Chat on WhatsApp')"
            class="relative grid size-15 place-items-center rounded-full bg-whatsapp text-ink-950 shadow-[0_12px_40px_-8px_rgb(37_211_102/0.7)] transition duration-300 hover:scale-105"
            @click="track('whatsapp_click', 'floating-button', locale)"
        >
            <span aria-hidden="true" class="absolute inset-0 animate-ping rounded-full bg-whatsapp/40 [animation-duration:2.6s] motion-reduce:hidden" />
            <svg viewBox="0 0 24 24" class="relative size-7" fill="currentColor" aria-hidden="true">
                <path
                    d="M17.47 14.38c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.47-.88-.79-1.47-1.76-1.65-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.21 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.22 1.36.19 1.87.12.57-.09 1.75-.72 2-1.41.25-.69.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35zM12.05 21.8a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 0 1-1.51-5.26C2.16 6.45 6.6 2.02 12.06 2.02c2.64 0 5.12 1.03 6.99 2.9a9.82 9.82 0 0 1 2.89 6.99c0 5.45-4.44 9.89-9.89 9.89zm8.41-18.3A11.82 11.82 0 0 0 12.05 0C5.5 0 .16 5.34.16 11.9c0 2.1.55 4.14 1.59 5.95L.06 24l6.3-1.65a11.88 11.88 0 0 0 5.69 1.45c6.55 0 11.89-5.34 11.89-11.9 0-3.18-1.24-6.17-3.49-8.41z"
                />
            </svg>
        </a>
    </div>

    <!-- Phones -->
    <nav
        :aria-label="t('Contact')"
        :class="[
            'fixed inset-x-0 bottom-0 z-40 border-t border-line bg-surface/90 px-3 pt-2 pb-[max(0.5rem,env(safe-area-inset-bottom))] backdrop-blur-xl transition duration-500 ease-out-expo sm:hidden',
            visible ? 'translate-y-0' : 'translate-y-full',
        ]"
    >
        <div class="grid grid-cols-[1fr_auto_auto] gap-2">
            <a
                :href="site.whatsapp.url"
                target="_blank"
                rel="noopener"
                class="flex min-h-12 items-center justify-center gap-2 rounded-full bg-whatsapp font-semibold text-ink-950"
                @click="track('whatsapp_click', 'mobile-bar', locale)"
            >
                {{ t('Chat on WhatsApp') }}
            </a>
            <a :href="mailto" class="grid size-12 place-items-center rounded-full border border-line text-fg" :aria-label="t('Email us')" @click="track('email_click', 'mobile-bar', locale)">
                <Mail :size="20" aria-hidden="true" />
            </a>
            <Link :href="lp('/estimate')" class="grid size-12 place-items-center rounded-full border border-line text-fg" :aria-label="t('Get an estimate')">
                <Calculator :size="20" aria-hidden="true" />
            </Link>
        </div>
    </nav>
</template>
