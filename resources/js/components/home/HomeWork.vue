<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import { useMediaQuery, useResizeObserver } from '@vueuse/core';
import { motion, useScroll, useTransform } from 'motion-v';
import { computed, ref } from 'vue';
import ProjectCard from '@/components/site/ProjectCard.vue';
import Button from '@/components/ui/Button.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { Project, SectionCopy } from '@/types/models';

/**
 * Featured projects. On large screens the section pins and the cards slide
 * sideways as you scroll; on phones (and with reduced motion) it is a
 * swipeable row.
 */
defineProps<{ projects: Project[]; total: number; copy?: SectionCopy }>();

const { t, lp, n } = useI18n();
const desktop = useMediaQuery('(min-width: 1024px)');
const reduced = useMediaQuery('(prefers-reduced-motion: reduce)');
const pinned = computed(() => desktop.value && !reduced.value);

const section = ref<HTMLElement>();
const track = ref<HTMLElement>();
const distance = ref(0);

useResizeObserver(track, () => {
    distance.value = Math.max(0, (track.value?.scrollWidth ?? 0) - window.innerWidth + 64);
});

const { scrollYProgress } = useScroll({ target: section, offset: ['start start', 'end end'] });
const x = useTransform(scrollYProgress, [0, 1], [0, 1]);
const translate = useTransform(() => -x.get() * distance.value);
const progress = useTransform(scrollYProgress, [0, 1], ['0%', '100%']);
</script>

<template>
    <section
        ref="section"
        class="relative bg-ink-950 text-white"
        :style="pinned ? { height: `calc(100vh + ${distance}px)` } : undefined"
    >
        <div :class="pinned ? 'sticky top-0 flex h-screen flex-col justify-center overflow-hidden' : 'section-y'">
            <div aria-hidden="true" class="absolute inset-0 bg-grid opacity-60" />
            <div class="container-page relative flex flex-wrap items-end justify-between gap-6">
                <SectionHeading :title="copy?.title" :lead="copy?.lead" dark />
                <Button :href="lp('/work')" variant="glass">
                    {{ t('View all :count projects', { count: n(total) }) }}
                    <ArrowRight :size="18" aria-hidden="true" />
                </Button>
            </div>

            <div :class="['relative mt-12', !pinned && 'overflow-x-auto overscroll-x-contain [scrollbar-width:none] snap-x snap-mandatory']">
                <motion.div
                    ref="track"
                    :style="pinned ? { x: translate } : undefined"
                    class="flex w-max gap-6 px-4 sm:px-6 lg:gap-8 lg:px-8"
                    :class="!pinned && 'pb-2'"
                >
                    <div
                        v-for="(project, index) in projects"
                        :key="project.id"
                        class="w-[82vw] shrink-0 snap-start sm:w-[26rem] lg:w-[34rem]"
                    >
                        <ProjectCard :project="project" dark :priority="index === 0" />
                    </div>
                </motion.div>
            </div>

            <div v-if="pinned" class="container-page relative mt-12">
                <div class="h-px w-full bg-white/10">
                    <motion.div class="h-px bg-brand-400" :style="{ width: progress }" />
                </div>
            </div>
        </div>
    </section>
</template>
