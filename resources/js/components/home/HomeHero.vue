<script setup lang="ts">
import { ArrowRight, Check, ChevronDown } from '@lucide/vue';
import { Link } from '@inertiajs/vue3';
import { motion, useMotionValueEvent, useScroll, useTransform } from 'motion-v';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import type { Anchor, HeroScene, Screen } from './heroScene';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';

/**
 * Scroll-pinned hero. Light trails race toward a wireframe laptop and phone that
 * show real project screenshots; while the visitor scrolls, the hero stays on
 * screen, the camera moves through the scene and the headline steps through the
 * featured services (stages). Everything shown comes from the admin.
 *
 * Phones get the same scene at a lower cost; visitors who prefer reduced motion
 * or save data get a still frame and a normal, unpinned hero. An optional
 * background video (Settings > Home page) replaces the scene.
 */
export type HeroStage = { title: string; text: string | null; url: string };

const props = defineProps<{
    eyebrow: string | null;
    title: string | null;
    titleParts: { text: string; accent: boolean }[];
    subtitle: string | null;
    video: string | null;
    videoWebm: string | null;
    poster: string | null;
    screens: Screen[];
    stages: HeroStage[];
    hotspots: string[];
    projectCount: number;
    industryCount: number;
}>();

const { site } = useShared();
const { t, n, lp } = useI18n();

const section = ref<HTMLElement>();
const canvas = ref<HTMLCanvasElement>();
const labels = ref<HTMLElement[]>([]);
const videoEl = ref<HTMLVideoElement>();

const motionOk = ref(false);
const sceneOn = ref(false);
const playing = ref(false);
const active = ref(0);

const steps = computed(() => 1 + props.stages.length);
// The section is taller than the screen only when it pins (motion allowed, stages present).
const pinned = computed(() => motionOk.value && props.stages.length > 0);
const parts = computed(() =>
    props.titleParts.length ? props.titleParts : props.title ? [{ text: props.title, accent: false }] : [],
);

const { scrollYProgress } = useScroll({ target: section, offset: ['start start', 'end end'] });
let progress = 0;
useMotionValueEvent(scrollYProgress, 'change', (value: number) => {
    progress = value;
    active.value = pinned.value ? Math.min(steps.value - 1, Math.floor(value * steps.value * 0.999)) : 0;
});
// When unpinned (no stages), fade the content as the hero leaves the screen.
const { scrollYProgress: leave } = useScroll({ target: section, offset: ['start start', 'end start'] });
const contentY = useTransform(leave, [0, 1], [0, 60]);

const number = (i: number) => n(String(i + 1).padStart(2, '0'));

function scrollOn(): void {
    const el = section.value;
    if (!el) return;
    const top = el.getBoundingClientRect().top + window.scrollY;
    const end = top + el.offsetHeight;
    const step = pinned.value ? (el.offsetHeight - window.innerHeight) / steps.value : 0;
    const target =
        pinned.value && active.value < steps.value - 1
            ? top + step * (active.value + 1) + 2
            : end - (pinned.value ? window.innerHeight - 1 : 0);
    window.scrollTo({ top: target, behavior: 'smooth' });
}

let scene: HeroScene | undefined;
let frame = 0;
let observer: IntersectionObserver | undefined;
let resizeObserver: ResizeObserver | undefined;
let onScreen = true;

function placeLabels(anchors: Anchor[]): void {
    labels.value.forEach((el, i) => {
        const a = anchors[i];
        if (!el) return;
        el.style.opacity = a?.visible ? '1' : '0';
        if (a?.visible) el.style.transform = `translate3d(${a.x}px, ${a.y}px, 0)`;
    });
}

onMounted(async () => {
    const connection = (navigator as Navigator & { connection?: { saveData?: boolean } }).connection;
    const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches || !!connection?.saveData;
    motionOk.value = !reduced;

    if (props.video) {
        if (!reduced && matchMedia('(min-width: 768px)').matches) videoEl.value?.play().catch(() => {});
        return;
    }

    const el = canvas.value;
    if (!el) return;
    const { createHeroScene } = await import('./heroScene');
    const lite = !matchMedia('(min-width: 768px)').matches;
    let intro = false;
    try {
        intro = !reduced && !sessionStorage.getItem('aux-hero-intro');
        sessionStorage.setItem('aux-hero-intro', '1');
    } catch {
        intro = false;
    }
    const wide = () => matchMedia('(min-width: 1024px)').matches;
    scene = createHeroScene(el, {
        screens: props.screens,
        lite,
        intro,
        focus: () => (wide() ? { x: 0.27, y: 0.6 } : { x: 0.5, y: 0.47 }),
        onAnchors: wide() ? placeLabels : undefined,
    });
    sceneOn.value = true;

    if (reduced) {
        // A still frame, redrawn once the screenshots have loaded.
        scene.render(3, 0);
        setTimeout(() => scene?.render(3, 0), 1500);
        resizeObserver = new ResizeObserver(() => {
            scene?.resize();
            scene?.render(3, 0);
        });
        resizeObserver.observe(el);
        return;
    }

    const start = performance.now();
    let last = 0;
    // Phones start at about 30 frames a second; any device whose frames take
    // long drops to that rate too, so the scene never makes the page feel slow.
    let interval = lite ? 30 : 0;
    let cost = 0;
    const tick = (now: number) => {
        frame = requestAnimationFrame(tick);
        if (now - last < interval || !onScreen) return;
        last = now;
        const before = performance.now();
        scene?.render((now - start) / 1000, pinned.value ? progress : 0);
        cost = cost * 0.9 + (performance.now() - before) * 0.1;
        if (cost > 9) interval = 30;
        if (cost > 20) interval = 60;
    };
    frame = requestAnimationFrame(tick);

    resizeObserver = new ResizeObserver(() => scene?.resize());
    resizeObserver.observe(el);
    observer = new IntersectionObserver(([entry]) => (onScreen = entry.isIntersecting));
    observer.observe(el);
});

onBeforeUnmount(() => {
    cancelAnimationFrame(frame);
    observer?.disconnect();
    resizeObserver?.disconnect();
    scene?.destroy();
});
</script>

<template>
    <section
        ref="section"
        class="relative bg-ink-950 text-white"
        :style="pinned ? { height: `${100 + stages.length * 90}svh` } : undefined"
    >
        <div
            :class="[
                'isolate flex flex-col overflow-hidden',
                pinned ? 'sticky top-0 h-[100svh]' : 'relative min-h-[100svh]',
            ]"
        >
            <!-- Ground and light: a deep gray floor with a faint warm horizon. -->
            <div
                aria-hidden="true"
                class="absolute inset-0 -z-30 bg-[radial-gradient(120%_80%_at_50%_42%,#3a3c42_0%,#2b2d31_38%,#1c1d20_100%)]"
            />

            <template v-if="video">
                <img
                    v-if="poster"
                    :src="poster"
                    alt=""
                    aria-hidden="true"
                    class="absolute inset-0 -z-20 size-full object-cover object-right"
                    fetchpriority="high"
                    decoding="async"
                />
                <video
                    ref="videoEl"
                    aria-hidden="true"
                    :class="[
                        'absolute inset-0 -z-20 size-full object-cover object-right transition-opacity duration-1000',
                        playing ? 'opacity-100' : 'opacity-0',
                    ]"
                    :poster="poster ?? undefined"
                    muted
                    loop
                    playsinline
                    preload="auto"
                    disablepictureinpicture
                    @playing="playing = true"
                >
                    <source :src="video" type="video/mp4" />
                    <source v-if="videoWebm" :src="videoWebm" type="video/webm" />
                </video>
            </template>
            <canvas
                v-else
                ref="canvas"
                aria-hidden="true"
                :class="[
                    'absolute inset-0 -z-20 size-full transition-opacity duration-700',
                    sceneOn ? 'opacity-100' : 'opacity-0',
                ]"
            />

            <!-- Keep text readable over the scene. -->
            <div
                aria-hidden="true"
                class="absolute inset-x-0 top-0 -z-10 h-40 bg-gradient-to-b from-ink-950/80 to-transparent"
            />
            <div
                aria-hidden="true"
                class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950/85 via-transparent to-transparent lg:bg-gradient-to-tr lg:from-ink-950/80 lg:via-transparent"
            />
            <div aria-hidden="true" class="absolute inset-0 -z-10 bg-ink-950/35 lg:hidden" />
            <div aria-hidden="true" class="absolute inset-0 -z-10 hidden bg-[radial-gradient(55%_55%_at_82%_28%,rgb(28_29_32/0.7),transparent)] lg:block" />

            <!-- Labels pinned to the devices (large screens). -->
            <div
                v-if="!video && hotspots.length"
                aria-hidden="true"
                class="pointer-events-none absolute inset-0 -z-10 hidden lg:block"
            >
                <span
                    v-for="(label, i) in hotspots.slice(0, 4)"
                    :key="label"
                    ref="labels"
                    class="absolute top-0 left-0 flex items-center gap-2.5 opacity-0 transition-opacity duration-500 will-change-transform"
                >
                    <span
                        class="-mt-3 -ml-3 grid size-6 place-items-center rounded-full border border-white/60 bg-ink-950/40 backdrop-blur-sm"
                    >
                        <span :class="['size-1.5 rounded-full', i === 0 ? 'bg-brand-600' : 'bg-white']" />
                    </span>
                    <span class="-mt-3 max-w-36 text-[0.72rem] leading-tight text-white/80">{{ label }}</span>
                </span>
            </div>

            <motion.div
                class="container-page flex flex-1 flex-col pt-28 pb-24 sm:pt-32 sm:pb-8"
                :style="pinned ? undefined : { y: contentY }"
            >
                <!-- Headlines: the H1, then one per stage, in the same place. -->
                <div class="grid lg:ml-auto lg:w-[60%] lg:text-right">
                    <div
                        :class="[
                            'col-start-1 row-start-1 transition duration-700 ease-out-expo',
                            active === 0
                                ? 'translate-y-0 opacity-100'
                                : 'pointer-events-none invisible -translate-y-6 opacity-0',
                        ]"
                    >
                        <p
                            v-if="eyebrow"
                            class="inline-flex items-center gap-2.5 font-mono text-xs tracking-[0.18em] text-brand-200 uppercase sm:text-[0.8rem] lg:justify-end"
                        >
                            <span class="size-1.5 animate-pulse rounded-full bg-brand-600" />[ {{ eyebrow }} ]
                        </p>
                        <h1
                            class="mt-5 font-display text-[2.6rem] leading-[1.02] font-light tracking-[-0.02em] text-balance sm:text-6xl lg:text-[5.25rem] xl:text-[6.25rem] lang-bn:leading-[1.3]"
                        >
                            <template v-for="(part, i) in parts" :key="i">
<span :class="part.accent ? 'text-brand-600 italic' : ''">{{ part.text }}</span>{{ i < parts.length - 1 ? ' ' : '' }}
</template>
                        </h1>
                    </div>
                    <div
                        v-for="(stage, i) in stages"
                        :key="stage.url"
                        :class="[
                            'col-start-1 row-start-1 transition duration-700 ease-out-expo',
                            active === i + 1
                                ? 'translate-y-0 opacity-100'
                                : active > i + 1
                                  ? 'pointer-events-none invisible -translate-y-6 opacity-0'
                                  : 'pointer-events-none invisible translate-y-6 opacity-0',
                        ]"
                    >
                        <p class="font-mono text-xs tracking-[0.18em] text-brand-200 uppercase sm:text-[0.8rem]">
                            {{ number(i + 1) }} / {{ number(steps - 1) }} · {{ t('What we do') }}
                        </p>
                        <h2
                            class="mt-5 font-display text-[2.6rem] leading-[1.02] font-light tracking-[-0.02em] text-balance sm:text-6xl lg:text-[5.25rem] xl:text-[6.25rem] lang-bn:leading-[1.3]"
                        >
                            <span class="text-brand-600 italic">{{ stage.title.split(' ')[0] }}</span>
                            {{ stage.title.split(' ').slice(1).join(' ') }}
                        </h2>
                    </div>
                </div>

                <div class="flex-1" />

                <!-- The card: lead text and calls to action. -->
                <div
                    class="relative max-w-md rounded-2xl border border-white/10 bg-ink-950/60 p-5 backdrop-blur-md sm:p-6"
                >
                    <div class="grid">
                        <div
                            :class="[
                                'col-start-1 row-start-1 transition duration-500',
                                active === 0 ? 'opacity-100' : 'invisible opacity-0',
                            ]"
                        >
                            <p v-if="subtitle" class="line-clamp-3 text-[0.95rem] leading-relaxed text-white/80 sm:line-clamp-none sm:text-base">
                                {{ subtitle }}
                            </p>
                            <ul class="mt-4 flex flex-wrap gap-x-5 gap-y-1.5 text-xs text-white/70 sm:text-sm">
                                <li v-if="projectCount" class="flex items-center gap-1.5">
                                    <Check :size="14" class="text-whatsapp" aria-hidden="true" />{{
                                        t(':count projects delivered', { count: n(projectCount) })
                                    }}
                                </li>
                                <li v-if="industryCount" class="flex items-center gap-1.5">
                                    <Check :size="14" class="text-whatsapp" aria-hidden="true" />{{
                                        t(':count industries', { count: n(industryCount) })
                                    }}
                                </li>
                                <li v-if="site.hours" class="hidden items-center gap-1.5 sm:flex">
                                    <Check :size="14" class="text-whatsapp" aria-hidden="true" />{{ site.hours }}
                                </li>
                            </ul>
                        </div>
                        <div
                            v-for="(stage, i) in stages"
                            :key="stage.url"
                            :class="[
                                'col-start-1 row-start-1 transition duration-500',
                                active === i + 1 ? 'opacity-100' : 'pointer-events-none invisible opacity-0',
                            ]"
                        >
                            <p v-if="stage.text" class="text-[0.95rem] leading-relaxed text-white/80 sm:text-base">
                                {{ stage.text }}
                            </p>
                            <Link
                                :href="stage.url"
                                class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-brand-200 hover:text-white"
                            >
                                {{ t('Explore the service') }} <ArrowRight :size="15" aria-hidden="true" />
                            </Link>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap items-center gap-2.5">
                        <WhatsAppButton placement="hero" />
                        <Button :href="lp('/work')" variant="glass">{{ t('See our work') }}</Button>
                    </div>

                    <div v-if="pinned" class="mt-5 flex items-center gap-3">
                        <div class="flex flex-1 gap-1.5" aria-hidden="true">
                            <span
                                v-for="i in steps"
                                :key="i"
                                class="h-0.5 flex-1 overflow-hidden rounded-full bg-white/15"
                            >
                                <span
                                    :class="[
                                        'block h-full rounded-full bg-brand-600 transition-all duration-500',
                                        i - 1 <= active ? 'w-full' : 'w-0',
                                    ]"
                                />
                            </span>
                        </div>
                        <button
                            type="button"
                            class="grid size-10 shrink-0 place-items-center rounded-full border border-white/15 text-white/80 transition hover:border-brand-600 hover:text-brand-600"
                            :aria-label="t('Scroll down')"
                            @click="scrollOn"
                        >
                            <ChevronDown :size="18" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </motion.div>
        </div>
    </section>
</template>
