<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { X } from '@lucide/vue';
import { motion } from 'motion-v';
import { DialogContent, DialogDescription, DialogOverlay, DialogPortal, DialogRoot, DialogTitle } from 'reka-ui';
import { computed } from 'vue';
import EmailLink from './EmailLink.vue';
import WhatsAppButton from './WhatsAppButton.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';

const open = defineModel<boolean>('open', { default: false });
const { site, seo } = useShared();
const { t, lp, locale } = useI18n();

const links = computed(() => [
    { label: t('Home'), href: lp('/') },
    { label: t('Work'), href: lp('/work') },
    { label: t('Services'), href: lp('/services') },
    { label: t('Solutions'), href: lp('/solutions') },
    { label: t('Pricing'), href: lp('/pricing') },
    { label: t('About'), href: lp('/about') },
    { label: t('Blog'), href: lp('/blog') },
    { label: t('Contact'), href: lp('/contact') },
]);
const otherLocale = computed(() => (locale.value === 'en' ? 'bn' : 'en'));
</script>

<template>
    <DialogRoot v-model:open="open">
        <DialogPortal>
            <DialogOverlay class="fixed inset-0 z-[60] bg-ink-950/60 backdrop-blur-sm" />
            <DialogContent class="fixed inset-0 z-[70] flex flex-col overflow-y-auto bg-ink-950 px-5 pt-5 pb-8 text-white outline-none">
                <div class="flex items-center justify-between">
                    <img :src="site.logo.logo_white" :alt="site.name" width="1125" height="174" class="h-6 w-auto" />
                    <button class="grid size-11 place-items-center rounded-full hover:bg-white/10" :aria-label="t('Close menu')" @click="open = false">
                        <X :size="24" aria-hidden="true" />
                    </button>
                </div>
                <DialogTitle class="sr-only">{{ t('Menu') }}</DialogTitle>
                <DialogDescription class="sr-only">{{ site.tagline }}</DialogDescription>

                <nav class="mt-10" :aria-label="t('Main')">
                    <ul class="grid gap-1">
                        <motion.li
                            v-for="(link, index) in links"
                            :key="link.href"
                            :initial="{ opacity: 0, x: -24 }"
                            :animate="{ opacity: 1, x: 0 }"
                            :transition="{ delay: 0.04 * index, duration: 0.5, ease: [0.16, 1, 0.3, 1] }"
                        >
                            <Link :href="link.href" class="block py-2 font-display text-3xl font-semibold tracking-tight text-white/85 hover:text-white" @click="open = false">
                                {{ link.label }}
                            </Link>
                        </motion.li>
                    </ul>
                </nav>

                <a
                    v-if="seo.alternates?.[otherLocale]"
                    :href="seo.alternates[otherLocale]"
                    :lang="otherLocale"
                    class="mt-8 inline-flex min-h-11 items-center self-start rounded-full border border-white/15 px-5 text-sm font-medium"
                >
                    {{ otherLocale === 'bn' ? 'বাংলায় দেখুন' : 'View in English' }}
                </a>

                <div class="mt-auto grid gap-3 pt-10">
                    <WhatsAppButton placement="mobile-menu" size="lg" block />
                    <EmailLink placement="mobile-menu-email" dark class="justify-center" />
                </div>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
