<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Clock, MapPin, Phone } from '@lucide/vue';
import { computed } from 'vue';
import EmailLink from './EmailLink.vue';
import SocialIcon from './SocialIcon.vue';
import WhatsAppButton from './WhatsAppButton.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';

const { site, navigation } = useShared();
const { t, lp, n } = useI18n();

const socials = computed(() =>
    Object.entries(site.value.social)
        .filter(([key, url]) => url && ['facebook', 'instagram', 'linkedin', 'youtube'].includes(key))
        .map(([key, url]) => ({ key, url: url as string })),
);
const company = computed(() => [
    { label: t('Work'), href: lp('/work') },
    { label: t('Pricing'), href: lp('/pricing') },
    { label: t('About'), href: lp('/about') },
    { label: t('Blog'), href: lp('/blog') },
    { label: t('FAQ'), href: lp('/faq') },
    { label: t('Get an estimate'), href: lp('/estimate') },
    { label: t('Contact'), href: lp('/contact') },
]);
</script>

<template>
    <footer class="relative overflow-hidden bg-ink-950 pt-20 pb-28 text-white/70 lg:pb-12">
        <div aria-hidden="true" class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-brand-500/50 to-transparent" />
        <div class="container-page">
            <div class="grid gap-12 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
                <div>
                    <img :src="site.logo.logo_white" :alt="site.name" width="1125" height="174" class="h-7 w-auto" loading="lazy" />
                    <p v-if="site.description" class="mt-5 max-w-sm leading-relaxed">{{ site.description }}</p>
                    <div class="mt-7 flex flex-wrap items-center gap-3">
                        <WhatsAppButton placement="footer" />
                        <EmailLink placement="footer-email" dark />
                    </div>
                    <ul v-if="socials.length" class="mt-7 flex gap-2">
                        <li v-for="social in socials" :key="social.key">
                            <a :href="social.url" target="_blank" rel="noopener me" class="grid size-10 place-items-center rounded-full border border-white/10 transition hover:border-brand-500 hover:bg-brand-600 hover:text-ink-950" :aria-label="social.key">
                                <SocialIcon :name="social.key" />
                            </a>
                        </li>
                    </ul>
                </div>

                <nav v-if="navigation.services.length" :aria-label="t('Services')">
                    <h2 class="font-display text-sm font-semibold tracking-wider text-white uppercase">{{ t('Services') }}</h2>
                    <ul class="mt-5 grid gap-2.5">
                        <li v-for="item in navigation.services" :key="item.url"><Link :href="item.url" class="transition hover:text-white">{{ item.title }}</Link></li>
                    </ul>
                </nav>
                <nav v-if="navigation.solutions.length" :aria-label="t('Platforms')">
                    <h2 class="font-display text-sm font-semibold tracking-wider text-white uppercase">{{ t('Platforms') }}</h2>
                    <ul class="mt-5 grid gap-2.5">
                        <li v-for="item in navigation.solutions" :key="item.url"><Link :href="item.url" class="transition hover:text-white">{{ item.title }}</Link></li>
                    </ul>
                </nav>
                <div>
                    <h2 class="font-display text-sm font-semibold tracking-wider text-white uppercase">{{ t('Company') }}</h2>
                    <ul class="mt-5 grid gap-2.5">
                        <li v-for="item in company" :key="item.href"><Link :href="item.href" class="transition hover:text-white">{{ item.label }}</Link></li>
                    </ul>
                </div>
            </div>

            <address class="mt-14 grid gap-4 border-t border-white/10 pt-8 text-sm not-italic sm:grid-cols-3">
                <span v-if="site.address" class="flex gap-2.5"><MapPin :size="18" class="mt-0.5 shrink-0 text-brand-300" aria-hidden="true" />{{ site.address }}</span>
                <span class="flex gap-2.5"><Phone :size="18" class="mt-0.5 shrink-0 text-brand-300" aria-hidden="true" />{{ site.phoneDisplay }} ({{ t('WhatsApp') }})</span>
                <span v-if="site.hours" class="flex gap-2.5"><Clock :size="18" class="mt-0.5 shrink-0 text-brand-300" aria-hidden="true" />{{ site.hours }}</span>
            </address>
            <p class="mt-8 text-sm text-white/60">© {{ n(new Date().getFullYear()) }} {{ site.name }}. {{ site.tagline }}.</p>
        </div>
    </footer>
</template>
