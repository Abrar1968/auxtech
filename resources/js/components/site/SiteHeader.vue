<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown, Menu, Moon, Sun } from '@lucide/vue';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import WhatsAppButton from './WhatsAppButton.vue';
import { useShared } from '@/composables/useSite';
import { useTheme } from '@/composables/useTheme';
import { useI18n } from '@/lib/i18n';

const emit = defineEmits<{ openMenu: [] }>();
const page = usePage();
const { site, navigation, seo } = useShared();
const { t, lp, locale } = useI18n();
const { theme, toggle } = useTheme();

const scrolled = ref(false);
const hidden = ref(false);
const openMenu = ref<string | null>(null);
let lastY = 0;

function onScroll(): void {
    const y = window.scrollY;
    scrolled.value = y > 40;
    hidden.value = y > 320 && y > lastY && openMenu.value === null;
    lastY = y;
}

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));

const links = computed(() => [
    { label: t('Work'), href: lp('/work') },
    { label: t('Services'), href: lp('/services'), menu: 'services' },
    { label: t('Platforms'), href: lp('/solutions'), menu: 'solutions' },
    { label: t('Pricing'), href: lp('/pricing') },
    { label: t('About'), href: lp('/about') },
    { label: t('Blog'), href: lp('/blog') },
]);

const otherLocale = computed(() => (locale.value === 'en' ? 'bn' : 'en'));
const switchHref = computed(() => seo.value.alternates?.[otherLocale.value] ?? lp('/'));

function isActive(href: string): boolean {
    const path = new URL(href, 'http://x').pathname;

    return path !== '/' && path !== '/bn' && page.url.startsWith(path);
}
</script>

<template>
    <header
        :class="[
            'fixed inset-x-0 top-0 z-50 transition duration-500 ease-out-expo',
            hidden ? '-translate-y-full' : 'translate-y-0',
        ]"
    >
        <div class="container-page pt-3">
            <div
                :class="[
                    'flex h-16 items-center gap-2 rounded-2xl pr-1.5 pl-3 transition-all duration-500 sm:gap-4 sm:px-3 sm:pl-4',
                    scrolled ? 'glass shadow-[0_10px_40px_-20px_rgb(5_8_22/0.35)]' : 'border border-transparent',
                ]"
            >
                <Link :href="lp('/')" class="shrink-0" :aria-label="site.name">
                    <img
                        :src="scrolled && theme === 'light' ? site.logo.logo : site.logo.logo_white"
                        :alt="site.name"
                        width="1125"
                        height="174"
                        class="h-5 w-auto min-[360px]:h-6 sm:h-7"
                    />
                </Link>

                <nav :aria-label="t('Main')" class="ml-6 hidden lg:block">
                    <ul class="flex items-center gap-1">
                        <li
                            v-for="link in links"
                            :key="link.href"
                            class="relative"
                            @mouseenter="openMenu = link.menu ?? null"
                            @mouseleave="openMenu = null"
                        >
                            <Link
                                :href="link.href"
                                prefetch="hover"
                                :class="[
                                    'inline-flex min-h-10 items-center gap-1 rounded-full px-3.5 text-sm font-medium transition',
                                    scrolled ? 'text-fg/75 hover:bg-surface-muted hover:text-fg' : 'text-white/75 hover:bg-white/10 hover:text-white',
                                    isActive(link.href) && (scrolled ? '!text-link' : '!text-white'),
                                ]"
                                :aria-current="isActive(link.href) ? 'page' : undefined"
                                :aria-expanded="link.menu ? openMenu === link.menu : undefined"
                                @focus="openMenu = link.menu ?? null"
                            >
                                {{ link.label }}
                                <ChevronDown v-if="link.menu" :size="14" class="opacity-60" aria-hidden="true" />
                            </Link>

                            <Transition
                                enter-active-class="transition duration-300 ease-out-expo"
                                enter-from-class="opacity-0 translate-y-2"
                                leave-active-class="transition duration-150"
                                leave-to-class="opacity-0"
                            >
                                <div v-if="link.menu && openMenu === link.menu && navigation[link.menu as 'services'].length" class="absolute top-full left-0 pt-3">
                                    <ul class="glass grid w-72 gap-0.5 rounded-2xl p-2 shadow-[0_30px_60px_-30px_rgb(5_8_22/0.5)]">
                                        <li v-for="item in navigation[link.menu as 'services']" :key="item.url">
                                            <Link
                                                :href="item.url"
                                                class="block rounded-xl px-3.5 py-2.5 text-sm font-medium text-fg/80 transition hover:bg-brand-600 hover:text-white"
                                                @click="openMenu = null"
                                            >
                                                {{ item.title }}
                                            </Link>
                                        </li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                    </ul>
                </nav>

                <div class="ml-auto flex items-center gap-1 sm:gap-1.5">
                    <a
                        :href="switchHref"
                        :hreflang="otherLocale"
                        :lang="otherLocale"
                        :class="[
                            'grid min-h-10 min-w-10 place-items-center rounded-full px-2.5 text-sm font-semibold transition',
                            scrolled ? 'text-fg/75 hover:bg-surface-muted' : 'text-white/80 hover:bg-white/10',
                        ]"
                        :title="otherLocale === 'bn' ? 'বাংলা' : 'English'"
                    >
                        <!-- System font: keeps the Bangla web font off English pages. -->
                        <span :style="otherLocale === 'bn' ? { fontFamily: 'system-ui, sans-serif' } : undefined">{{ otherLocale === 'bn' ? 'বাং' : 'EN' }}</span>
                    </a>
                    <button
                        :class="['grid size-10 place-items-center rounded-full transition', scrolled ? 'text-fg/75 hover:bg-surface-muted' : 'text-white/80 hover:bg-white/10']"
                        :aria-label="theme === 'dark' ? t('Switch to light theme') : t('Switch to dark theme')"
                        @click="toggle"
                    >
                        <Sun v-if="theme === 'dark'" :size="18" aria-hidden="true" />
                        <Moon v-else :size="18" aria-hidden="true" />
                    </button>
                    <span class="hidden sm:block"><WhatsAppButton placement="header" size="sm" :label="t('WhatsApp')" /></span>
                    <button
                        :class="['grid size-11 place-items-center rounded-full lg:hidden', scrolled ? 'text-fg hover:bg-surface-muted' : 'text-white hover:bg-white/10']"
                        :aria-label="t('Open menu')"
                        @click="emit('openMenu')"
                    >
                        <Menu :size="22" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </div>
    </header>
</template>
