<script setup lang="ts">
import { AnimatePresence, motion, useMotionValueEvent, useScroll } from 'motion-v';
import { computed, ref } from 'vue';
import Reveal from '@/components/ui/Reveal.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { SectionCopy, TextRow } from '@/types/models';

/**
 * Delivery process. On large screens the section pins while you scroll and the
 * active step advances along a progress rail; elsewhere it is a numbered list.
 */
const props = defineProps<{ steps: TextRow[]; copy?: SectionCopy; eyebrow?: string }>();

const { n } = useI18n();
const track = ref<HTMLElement>();
const active = ref(0);
const { scrollYProgress } = useScroll({ target: track, offset: ['start start', 'end end'] });
useMotionValueEvent(scrollYProgress, 'change', (value: number) => {
    active.value = Math.min(props.steps.length - 1, Math.max(0, Math.floor(value * props.steps.length)));
});

const step = computed(() => props.steps[active.value]);
const number = (index: number) => n(String(index + 1).padStart(2, '0'));
const ease = [0.16, 1, 0.3, 1] as const;
</script>

<template>
    <section class="bg-surface-muted">
        <!-- Large screens: pinned, scroll-driven -->
        <div ref="track" class="relative hidden lg:block" :style="{ height: `${steps.length * 70 + 30}vh` }">
            <div class="sticky top-0 flex h-screen items-center overflow-hidden">
                <div class="container-page grid w-full items-center gap-16 lg:grid-cols-[minmax(0,5fr)_minmax(0,6fr)]">
                    <div>
                        <SectionHeading :eyebrow="eyebrow" :title="copy?.title" :lead="copy?.lead" />
                        <ol class="mt-12 grid gap-1" :aria-label="copy?.title ?? undefined">
                            <li v-for="(item, index) in steps" :key="index" class="relative flex items-center gap-4 py-2.5 pl-6">
                                <span aria-hidden="true" class="absolute top-0 left-0 h-full w-0.5 rounded-full bg-line">
                                    <span
                                        class="block w-full origin-top rounded-full bg-gradient-to-b from-brand-500 to-cyan-accent transition-transform duration-500 ease-out-expo"
                                        :style="{ height: '100%', transform: `scaleY(${index < active ? 1 : index === active ? 0.999 : 0})` }"
                                    />
                                </span>
                                <span :class="['font-mono text-sm transition-colors duration-300', index <= active ? 'text-link' : 'text-muted/60']">{{ number(index) }}</span>
                                <span :class="['font-display text-lg transition-all duration-300', index === active ? 'font-semibold text-fg' : 'text-muted']">{{ item.title }}</span>
                            </li>
                        </ol>
                    </div>

                    <div class="relative">
                        <div aria-hidden="true" class="absolute -inset-10 -z-10 rounded-full bg-[radial-gradient(closest-side,rgb(1_69_255/0.18),transparent)] blur-2xl" />
                        <div class="relative min-h-[26rem] overflow-hidden rounded-[2rem] border border-line bg-surface-raised p-10 shadow-[0_40px_90px_-50px_rgb(1_69_255/0.55)] xl:p-14">
                            <AnimatePresence mode="wait">
                                <motion.div
                                    v-if="step"
                                    :key="active"
                                    :initial="{ opacity: 0, y: 40, filter: 'blur(6px)' }"
                                    :animate="{ opacity: 1, y: 0, filter: 'blur(0px)' }"
                                    :exit="{ opacity: 0, y: -30, filter: 'blur(6px)' }"
                                    :transition="{ duration: 0.55, ease }"
                                >
                                    <p class="font-display text-[7rem] leading-none font-semibold tracking-tight text-transparent [-webkit-text-stroke:1.5px_var(--color-brand-400)]">{{ number(active) }}</p>
                                    <h3 class="mt-8 font-display text-4xl font-semibold tracking-tight text-fg">{{ step.title }}</h3>
                                    <p v-if="step.description" class="mt-5 max-w-xl text-xl leading-relaxed text-muted">{{ step.description }}</p>
                                </motion.div>
                            </AnimatePresence>
                            <div class="absolute inset-x-10 bottom-8 h-1 overflow-hidden rounded-full bg-line xl:inset-x-14">
                                <motion.div class="h-full origin-left rounded-full bg-gradient-to-r from-brand-500 to-cyan-accent" :style="{ scaleX: scrollYProgress }" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phones and tablets: a numbered list -->
        <div class="section-y lg:hidden">
            <div class="container-page">
                <SectionHeading :eyebrow="eyebrow" :title="copy?.title" :lead="copy?.lead" />
                <ol class="mt-12 grid gap-8">
                    <Reveal v-for="(item, index) in steps" :key="index" as="li" class="relative border-l-2 border-line pl-6">
                        <span class="font-mono text-sm text-link">{{ number(index) }}</span>
                        <h3 class="mt-1 font-display text-2xl font-semibold text-fg">{{ item.title }}</h3>
                        <p v-if="item.description" class="mt-2 text-lg text-muted">{{ item.description }}</p>
                    </Reveal>
                </ol>
            </div>
        </div>
    </section>
</template>
