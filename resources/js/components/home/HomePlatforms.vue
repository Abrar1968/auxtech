<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, ArrowUpRight } from '@lucide/vue';
import { motion, useScroll, useTransform } from 'motion-v';
import { ref } from 'vue';
import DeviceFrame from '@/components/site/DeviceFrame.vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { SectionCopy, Solution } from '@/types/models';

/**
 * Platforms as cards that stack on top of each other while scrolling: each
 * card sticks under the header and the ones below it shrink back slightly.
 */
const props = defineProps<{ solutions: Solution[]; copy?: SectionCopy; eyebrow?: string }>();

const { t, tc } = useI18n();
const stack = ref<HTMLElement>();
const { scrollYProgress } = useScroll({ target: stack, offset: ['start start', 'end end'] });

const count = props.solutions.length;
// Cards further down the stack end up smaller, so the pile reads as depth.
const scales = props.solutions.map((_, index) => useTransform(scrollYProgress, [index / count, 1], [1, 1 - (count - index - 1) * 0.04]));

// Card themes in the site palette: gray, yellow, page surface, silver, deep gray.
type Theme = { card: string; dark: boolean; tile: string; meta: string; tagline: string; body: string; primary: 'primary' | 'dark' | 'glass'; secondary: 'secondary' | 'glass' };
const themes: Theme[] = [
    { card: 'bg-ink-950 text-white border-white/10', dark: true, tile: 'bg-brand-600 text-ink-950', meta: 'text-white/65', tagline: 'text-brand-200', body: 'text-white/75', primary: 'primary', secondary: 'glass' },
    { card: 'bg-brand-600 text-ink-950 border-brand-700/50', dark: false, tile: 'bg-ink-950 text-brand-600', meta: 'text-ink-950/75', tagline: 'text-ink-950', body: 'text-ink-950/80', primary: 'dark', secondary: 'secondary' },
    { card: 'bg-surface-raised text-fg border-line', dark: false, tile: 'bg-brand-600 text-ink-950', meta: 'text-muted', tagline: 'text-link', body: 'text-muted', primary: 'primary', secondary: 'secondary' },
    { card: 'bg-silver text-ink-950 border-ink-950/10', dark: false, tile: 'bg-ink-950 text-brand-600', meta: 'text-ink-950/75', tagline: 'text-ink-950', body: 'text-ink-950/80', primary: 'dark', secondary: 'secondary' },
    { card: 'bg-gradient-to-br from-ink-800 to-ink-950 text-white border-white/10', dark: true, tile: 'bg-white/10 text-brand-600 ring-1 ring-white/15', meta: 'text-white/65', tagline: 'text-brand-200', body: 'text-white/75', primary: 'primary', secondary: 'glass' },
];
const theme = (index: number) => themes[index % themes.length];
</script>

<template>
    <section class="section-y bg-surface">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <SectionHeading :eyebrow="eyebrow" :title="copy?.title" :lead="copy?.lead" />
            </div>

            <div ref="stack" class="mt-14 grid grid-cols-1 gap-8">
                <div v-for="(solution, index) in solutions" :key="solution.id" class="min-w-0 lg:sticky" :style="{ top: `calc(6.5rem + ${index * 1.25}rem)` }">
                    <motion.article
                        :style="{ scale: scales[index] }"
                        :class="['origin-top overflow-hidden rounded-[2rem] border shadow-[0_40px_80px_-50px_rgb(43_45_49/0.55)]', theme(index).card]"
                    >
                        <div class="grid items-center gap-10 p-8 sm:p-10 lg:grid-cols-[minmax(0,5fr)_minmax(0,6fr)] lg:gap-14 lg:p-14">
                            <div>
                                <div class="flex items-center gap-4">
                                    <span
                                        :class="[
                                            'grid size-13 place-items-center rounded-2xl',
                                            theme(index).tile,
                                        ]"
                                    >
                                        <Icon :name="solution.icon" :size="24" />
                                    </span>
                                    <span :class="['font-mono text-sm', theme(index).meta]">{{ String(index + 1).padStart(2, '0') }} / {{ String(count).padStart(2, '0') }}</span>
                                </div>
                                <h3 class="mt-7 font-display text-3xl font-semibold tracking-tight sm:text-4xl">{{ solution.name }}</h3>
                                <p v-if="solution.tagline" :class="['mt-3 text-lg font-medium', theme(index).tagline]">{{ solution.tagline }}</p>
                                <p v-if="solution.summary" :class="['mt-4 text-lg leading-relaxed', theme(index).body]">{{ solution.summary }}</p>
                                <p v-if="solution.projects_count" :class="['mt-5 text-sm font-medium', theme(index).meta]">
                                    {{ tc(':count project', ':count projects', solution.projects_count) }}
                                </p>
                                <div class="mt-8 flex flex-wrap gap-3">
                                    <Button :href="solution.url" :variant="theme(index).primary">
                                        {{ t('Explore the platform') }} <ArrowRight :size="18" aria-hidden="true" />
                                    </Button>
                                    <Button v-if="solution.demo_url || solution.external_url" :href="(solution.demo_url || solution.external_url)!" :variant="theme(index).secondary" external>
                                        {{ solution.demo_url ? t('See a live demo') : t('Visit website') }} <ArrowUpRight :size="18" aria-hidden="true" />
                                    </Button>
                                </div>
                            </div>
                            <Link :href="solution.url" tabindex="-1" aria-hidden="true" class="block transition duration-700 ease-out-expo hover:-translate-y-1.5">
                                <DeviceFrame v-if="solution.cover" :image="solution.cover" :url="solution.external_url ?? solution.demo_url" :dark="theme(index).dark" sizes="(min-width: 1024px) 40vw, 90vw" />
                                <div v-else class="grid aspect-[16/10] place-items-center rounded-2xl bg-current/5 ring-1 ring-current/10"><Icon :name="solution.icon" :size="64" class="opacity-40" /></div>
                            </Link>
                        </div>
                    </motion.article>
                </div>
            </div>
        </div>
    </section>
</template>
