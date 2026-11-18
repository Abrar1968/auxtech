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

const themes = [
    'bg-ink-950 text-white border-white/10',
    'bg-gradient-to-br from-brand-700 via-brand-600 to-violet-accent text-white border-white/10',
    'bg-surface-raised text-fg border-line',
    'bg-ink-900 text-white border-white/10',
    'bg-gradient-to-br from-[#0b2c80] to-ink-950 text-white border-white/10',
];
</script>

<template>
    <section class="section-y bg-surface">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <SectionHeading :eyebrow="eyebrow" :title="copy?.title" :lead="copy?.lead" />
            </div>

            <div ref="stack" class="mt-14 grid gap-8">
                <div v-for="(solution, index) in solutions" :key="solution.id" class="sticky" :style="{ top: `calc(6.5rem + ${index * 1.25}rem)` }">
                    <motion.article
                        :style="{ scale: scales[index] }"
                        :class="['origin-top overflow-hidden rounded-[2rem] border shadow-[0_40px_80px_-50px_rgb(5_8_22/0.65)]', themes[index % themes.length]]"
                    >
                        <div class="grid items-center gap-10 p-8 sm:p-10 lg:grid-cols-[minmax(0,5fr)_minmax(0,6fr)] lg:gap-14 lg:p-14">
                            <div>
                                <div class="flex items-center gap-4">
                                    <span
                                        :class="[
                                            'grid size-13 place-items-center rounded-2xl',
                                            index % themes.length === 2 ? 'bg-brand-600 text-white' : 'bg-white/10 text-white ring-1 ring-white/15',
                                        ]"
                                    >
                                        <Icon :name="solution.icon" :size="24" />
                                    </span>
                                    <span :class="['font-mono text-sm', index % themes.length === 2 ? 'text-muted' : 'text-white/55']">{{ String(index + 1).padStart(2, '0') }} / {{ String(count).padStart(2, '0') }}</span>
                                </div>
                                <h3 class="mt-7 font-display text-3xl font-semibold tracking-tight sm:text-4xl">{{ solution.name }}</h3>
                                <p v-if="solution.tagline" :class="['mt-3 text-lg font-medium', index % themes.length === 2 ? 'text-link' : 'text-brand-200']">{{ solution.tagline }}</p>
                                <p v-if="solution.summary" :class="['mt-4 text-lg leading-relaxed', index % themes.length === 2 ? 'text-muted' : 'text-white/70']">{{ solution.summary }}</p>
                                <p v-if="solution.projects_count" :class="['mt-5 text-sm font-medium', index % themes.length === 2 ? 'text-muted' : 'text-white/60']">
                                    {{ tc(':count project', ':count projects', solution.projects_count) }}
                                </p>
                                <div class="mt-8 flex flex-wrap gap-3">
                                    <Button :href="solution.url" :variant="index % themes.length === 2 ? 'primary' : 'glass'">
                                        {{ t('Explore the platform') }} <ArrowRight :size="18" aria-hidden="true" />
                                    </Button>
                                    <Button v-if="solution.demo_url || solution.external_url" :href="(solution.demo_url || solution.external_url)!" :variant="index % themes.length === 2 ? 'secondary' : 'glass'" external>
                                        {{ solution.demo_url ? t('See a live demo') : t('Visit website') }} <ArrowUpRight :size="18" aria-hidden="true" />
                                    </Button>
                                </div>
                            </div>
                            <Link :href="solution.url" tabindex="-1" aria-hidden="true" class="block transition duration-700 ease-out-expo hover:-translate-y-1.5">
                                <DeviceFrame v-if="solution.cover" :image="solution.cover" :url="solution.external_url ?? solution.demo_url" :dark="index % themes.length !== 2" sizes="(min-width: 1024px) 40vw, 90vw" />
                                <div v-else class="grid aspect-[16/10] place-items-center rounded-2xl bg-white/5 ring-1 ring-white/10"><Icon :name="solution.icon" :size="64" class="opacity-40" /></div>
                            </Link>
                        </div>
                    </motion.article>
                </div>
            </div>
        </div>
    </section>
</template>
