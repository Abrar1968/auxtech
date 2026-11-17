<script setup lang="ts">
import { motion, useScroll } from 'motion-v';
import { ref } from 'vue';
import Reveal from '@/components/ui/Reveal.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { SectionCopy, TextRow } from '@/types/models';

/** Numbered steps on a line that draws itself as you scroll. */
defineProps<{ steps: TextRow[]; copy?: SectionCopy }>();

const { n } = useI18n();
const list = ref<HTMLElement>();
const { scrollYProgress } = useScroll({ target: list, offset: ['start 75%', 'end 55%'] });
</script>

<template>
    <section class="section-y bg-surface-muted">
        <div class="container-page grid gap-14 lg:grid-cols-[minmax(0,4fr)_minmax(0,7fr)]">
            <div class="lg:sticky lg:top-32 lg:self-start">
                <SectionHeading :title="copy?.title" :lead="copy?.lead" />
            </div>
            <ol ref="list" class="relative grid gap-10 pl-16 sm:pl-20">
                <div aria-hidden="true" class="absolute top-2 bottom-2 left-[1.375rem] w-px bg-line sm:left-[1.625rem]">
                    <motion.div class="w-px origin-top bg-gradient-to-b from-brand-500 to-cyan-accent" :style="{ scaleY: scrollYProgress, height: '100%' }" />
                </div>
                <Reveal v-for="(step, index) in steps" :key="index" as="li" :delay="0.05" class="relative">
                    <span
                        class="absolute top-0 -left-16 grid size-11 place-items-center rounded-full border border-line bg-surface-raised font-display text-sm font-semibold text-brand-600 shadow-sm sm:-left-20 sm:size-13 sm:text-base"
                    >
                        {{ n(String(index + 1).padStart(2, '0')) }}
                    </span>
                    <h3 class="font-display text-2xl font-semibold text-fg">{{ step.title }}</h3>
                    <p v-if="step.description" class="mt-2 max-w-xl text-lg text-muted">{{ step.description }}</p>
                </Reveal>
            </ol>
        </div>
    </section>
</template>
