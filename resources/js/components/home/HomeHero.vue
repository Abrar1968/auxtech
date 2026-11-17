<script setup lang="ts">
import { ArrowRight, Check } from '@lucide/vue';
import { motion, useScroll, useTransform } from 'motion-v';
import { computed, ref } from 'vue';
import DeviceFrame from '@/components/site/DeviceFrame.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';
import type { Project, Solution } from '@/types/models';

const props = defineProps<{
    title: string | null;
    subtitle: string | null;
    projects: Project[];
    flagship: Solution | null;
    liveCount: number;
    industryCount: number;
}>();

const { site } = useShared();
const { t, lp, n } = useI18n();

// Three screenshots for the stacked frames.
const frames = computed(() => props.projects.filter((p) => p.cover).slice(0, 3));

// Module names float over the frames as glass chips.
const chips = computed(() => {
    const features = props.flagship?.feature_groups?.flatMap((g) => g.features) ?? [];
    const wanted = ['order-management', 'courier-integration', 'conversions-api'];
    const picked = wanted.map((slug) => features.find((f) => f.slug === slug)).filter(Boolean);

    return (picked.length === 3 ? picked : features.slice(0, 3)) as NonNullable<(typeof features)[number]>[];
});

const section = ref<HTMLElement>();
const { scrollYProgress } = useScroll({ target: section, offset: ['start start', 'end start'] });
const framesY = useTransform(scrollYProgress, [0, 1], [0, 120]);
const textY = useTransform(scrollYProgress, [0, 1], [0, 60]);

const words = computed(() => (props.title ?? '').split(' '));
const ease = [0.16, 1, 0.3, 1] as const;
</script>

<template>
    <section ref="section" class="relative isolate overflow-hidden bg-ink-950 pt-32 pb-20 text-white sm:pt-40 lg:min-h-[100svh] lg:pb-28">
        <!-- Background: grid, aurora, glow line -->
        <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid" />
        <div aria-hidden="true" class="absolute -top-40 -left-40 -z-10 size-[40rem] animate-aurora rounded-full bg-[radial-gradient(closest-side,rgb(1_69_255/0.6),transparent)] blur-2xl" />
        <div aria-hidden="true" class="absolute top-20 right-[-10rem] -z-10 size-[34rem] animate-aurora rounded-full bg-[radial-gradient(closest-side,rgb(124_92_255/0.35),transparent)] blur-2xl [animation-delay:-6s]" />
        <div aria-hidden="true" class="absolute bottom-[-12rem] left-1/3 -z-10 size-[30rem] animate-aurora rounded-full bg-[radial-gradient(closest-side,rgb(34_211_238/0.22),transparent)] blur-2xl [animation-delay:-11s]" />

        <div class="container-page grid items-center gap-16 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)]">
            <motion.div :style="{ y: textY }">
                <motion.p
                    :initial="{ opacity: 0, y: 12 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.6, ease }"
                    class="inline-flex items-center gap-2 rounded-full glass-dark py-1.5 pr-4 pl-1.5 text-sm text-white/80"
                >
                    <span class="rounded-full bg-brand-600 px-2.5 py-0.5 text-xs font-semibold text-white">{{ site.name }}</span>
                    {{ site.tagline }}
                </motion.p>

                <h1 class="mt-7 font-display text-[2.6rem] leading-[1.05] font-semibold tracking-tight sm:text-6xl xl:text-7xl">
                    <motion.span
                        v-for="(word, index) in words"
                        :key="index"
                        class="inline-block"
                        :class="index >= words.length - 2 ? 'text-gradient' : ''"
                        :initial="{ y: '0.35em', filter: 'blur(6px)' }"
                        :animate="{ y: '0em', filter: 'blur(0px)' }"
                        :transition="{ duration: 0.7, delay: index * 0.04, ease }"
                    >
                        {{ word }}&nbsp;
                    </motion.span>
                </h1>

                <motion.p
                    v-if="subtitle"
                    :initial="{ opacity: 0, y: 16 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.8, delay: 0.45, ease }"
                    class="mt-6 max-w-xl text-lg leading-relaxed text-white/70 sm:text-xl"
                >
                    {{ subtitle }}
                </motion.p>

                <motion.div
                    :initial="{ opacity: 0, y: 16 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.8, delay: 0.6, ease }"
                    class="mt-10 flex flex-col gap-3 sm:flex-row sm:items-center"
                >
                    <WhatsAppButton placement="hero" size="lg" />
                    <Button :href="lp('/work')" variant="glass" size="lg">
                        {{ t('See our work') }}
                        <ArrowRight :size="18" class="transition group-hover/button:translate-x-0.5" aria-hidden="true" />
                    </Button>
                </motion.div>

                <motion.ul
                    :initial="{ opacity: 0 }"
                    :animate="{ opacity: 1 }"
                    :transition="{ duration: 1, delay: 0.85 }"
                    class="mt-10 flex flex-wrap gap-x-6 gap-y-3 text-sm text-white/65"
                >
                    <li v-if="liveCount" class="flex items-center gap-2"><Check :size="16" class="text-whatsapp" aria-hidden="true" />{{ t(':count live client sites', { count: n(liveCount) }) }}</li>
                    <li v-if="industryCount" class="flex items-center gap-2"><Check :size="16" class="text-whatsapp" aria-hidden="true" />{{ t(':count industries', { count: n(industryCount) }) }}</li>
                    <li v-if="site.hours" class="flex items-center gap-2"><Check :size="16" class="text-whatsapp" aria-hidden="true" />{{ site.hours }}</li>
                </motion.ul>
            </motion.div>

            <!-- Stacked client screenshots with floating module chips -->
            <motion.div v-if="frames.length" :style="{ y: framesY }" class="relative mx-auto w-full max-w-xl lg:max-w-none" aria-hidden="true">
                <motion.div
                    v-for="(project, index) in frames"
                    :key="project.id"
                    :initial="{ opacity: 0, y: 60, rotate: 0 }"
                    :animate="{ opacity: 1, y: 0, rotate: [-6, 3, -1][index] }"
                    :transition="{ duration: 1.1, delay: 0.3 + index * 0.15, ease }"
                    :class="[
                        'w-[78%]',
                        index === 0 && 'relative z-10 ml-auto',
                        index === 1 && 'absolute top-[18%] left-0 z-20',
                        index === 2 && 'absolute top-[46%] right-[4%] z-30 w-[64%]',
                    ]"
                >
                    <div class="animate-float" :style="{ animationDelay: `${index * -2.3}s` }">
                        <DeviceFrame :image="project.cover" :url="project.live_url" :priority="index === 0" sizes="(min-width: 1024px) 34vw, 70vw" />
                    </div>
                </motion.div>

                <motion.div
                    v-for="(chip, index) in chips"
                    :key="chip.slug"
                    :initial="{ opacity: 0, scale: 0.85 }"
                    :animate="{ opacity: 1, scale: 1 }"
                    :transition="{ duration: 0.6, delay: 1 + index * 0.18, ease }"
                    :class="[
                        'absolute z-40 hidden items-center gap-2.5 rounded-2xl border border-white/10 bg-ink-900/85 px-3.5 py-2.5 text-sm font-medium text-white shadow-[0_20px_50px_-15px_rgb(0_0_0/0.9)] backdrop-blur-xl sm:flex',
                        ['-top-10 left-[6%]', 'top-[58%] -left-8', '-bottom-12 right-0'][index],
                    ]"
                >
                    <span class="grid size-8 place-items-center rounded-xl bg-brand-600"><Icon :name="chip.icon" :size="16" /></span>
                    {{ chip.title }}
                    <span class="size-2 rounded-full bg-whatsapp" />
                </motion.div>
                <!-- Keeps height for the absolutely positioned frames -->
                <div class="h-[30%]" />
            </motion.div>
        </div>
    </section>
</template>
