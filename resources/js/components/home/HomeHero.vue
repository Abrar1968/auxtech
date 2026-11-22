<script setup lang="ts">
import { ArrowRight, Check } from '@lucide/vue';
import { motion, useScroll, useTransform } from 'motion-v';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';

/**
 * Full-screen hero over a looping background video (admin-replaceable in
 * Settings > Home page). Phones, data-saver and reduced-motion visitors get
 * the still image; the video loads only after the page is interactive.
 */
const props = defineProps<{
    eyebrow: string | null;
    title: string | null;
    subtitle: string | null;
    video: string | null;
    videoWebm: string | null;
    poster: string | null;
    projectCount: number;
    industryCount: number;
    capabilities: string[];
}>();

const { site } = useShared();
const { t, lp, n } = useI18n();

const section = ref<HTMLElement>();
const videoEl = ref<HTMLVideoElement>();
const showVideo = ref(false);
const playing = ref(false);

const { scrollYProgress } = useScroll({ target: section, offset: ['start start', 'end start'] });
const mediaY = useTransform(scrollYProgress, [0, 1], ['0%', '18%']);
const mediaScale = useTransform(scrollYProgress, [0, 1], [1.04, 1.14]);
const textY = useTransform(scrollYProgress, [0, 1], [0, 90]);
const textOpacity = useTransform(scrollYProgress, [0, 0.7], [1, 0]);

const words = computed(() => (props.title ?? '').split(' '));
const ease = [0.16, 1, 0.3, 1] as const;
const ticker = computed(() => [...props.capabilities, ...props.capabilities]);

let observer: IntersectionObserver | undefined;
const ready = ref(false);

// Fade the video in only once enough is buffered to play through, so it never
// appears and then freezes while the rest downloads; until then the poster shows.
function start() {
    const video = videoEl.value;
    if (!video || ready.value) return;
    ready.value = true;
    video.play().then(() => (playing.value = true)).catch(() => {});
    // Pause while the hero is off screen; it costs nothing to keep it loaded.
    observer = new IntersectionObserver(([entry]) => (entry.isIntersecting ? video.play().catch(() => {}) : video.pause()));
    observer.observe(section.value!);
}

onMounted(() => {
    const connection = (navigator as Navigator & { connection?: { saveData?: boolean; effectiveType?: string } }).connection;
    const allowed =
        !!(props.video || props.videoWebm) &&
        matchMedia('(min-width: 768px)').matches &&
        !matchMedia('(prefers-reduced-motion: reduce)').matches &&
        !connection?.saveData &&
        !['slow-2g', '2g'].includes(connection?.effectiveType ?? '');

    if (!allowed) {
        return;
    }

    // Start downloading after the page has loaded, so the video never competes
    // with the page's own scripts, fonts and images.
    const load = () => setTimeout(() => (showVideo.value = true), 300);
    if (document.readyState === 'complete') load();
    else addEventListener('load', load, { once: true });
});

onBeforeUnmount(() => observer?.disconnect());
</script>

<template>
    <section ref="section" class="relative isolate flex min-h-[100svh] flex-col overflow-hidden bg-ink-950 text-white">
        <!-- Background: still image, then the video fades in over it -->
        <motion.div aria-hidden="true" class="absolute inset-0 -z-20" :style="{ y: mediaY, scale: mediaScale }">
            <img v-if="poster" :src="poster" alt="" class="size-full object-cover object-right" fetchpriority="high" decoding="async" />
            <video
                v-if="showVideo"
                ref="videoEl"
                class="absolute inset-0 size-full object-cover object-right transition-opacity duration-1000"
                :class="playing ? 'opacity-100' : 'opacity-0'"
                :poster="poster ?? undefined"
                muted
                loop
                playsinline
                preload="auto"
                disablepictureinpicture
                @canplaythrough="start"
                @playing="ready && (playing = true)"
            >
                <!-- H.264 first: hardware-decoded almost everywhere, so it plays without stutter. -->
                <source v-if="video" :src="video" type="video/mp4" />
                <source v-if="videoWebm" :src="videoWebm" type="video/webm" />
            </video>
        </motion.div>
        <div aria-hidden="true" class="absolute inset-0 -z-10 bg-gradient-to-r from-ink-950 via-ink-950/75 to-ink-950/5" />
        <div aria-hidden="true" class="absolute inset-x-0 bottom-0 -z-10 h-48 bg-gradient-to-t from-ink-950 to-transparent" />
        <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid opacity-60" />

        <div class="container-page flex flex-1 items-center pt-32 pb-16 sm:pt-40">
            <motion.div class="max-w-3xl" :style="{ y: textY, opacity: textOpacity }">
                <motion.p
                    v-if="eyebrow"
                    :initial="{ opacity: 0, y: 12 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.6, ease }"
                    class="inline-flex items-center gap-2.5 font-mono text-xs tracking-[0.18em] text-brand-200 uppercase sm:text-[0.8rem]"
                >
                    <span class="size-1.5 animate-pulse rounded-full bg-brand-600" />[ {{ eyebrow }} ]
                </motion.p>

                <h1 class="mt-6 font-display text-[2.75rem] leading-[1.05] font-semibold tracking-tight sm:text-6xl xl:text-7xl lang-bn:leading-[1.35]">
                    <motion.span
                        v-for="(word, index) in words"
                        :key="index"
                        class="inline-block align-top"
                        :class="index >= words.length - 2 ? 'text-gradient' : ''"
                        :initial="{ y: '0.35em', filter: 'blur(8px)' }"
                        :animate="{ y: '0em', filter: 'blur(0px)' }"
                        :transition="{ duration: 0.8, delay: 0.1 + index * 0.05, ease }"
                    >
                        {{ word }}&nbsp;
                    </motion.span>
                </h1>

                <motion.p
                    v-if="subtitle"
                    :initial="{ y: 14, scale: 0.96 }"
                    :animate="{ y: 0, scale: 1 }"
                    :transition="{ duration: 0.8, ease }"
                    class="mt-6 max-w-2xl origin-top-left text-base leading-relaxed text-white/75 sm:text-xl"
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
                    class="mt-10 flex flex-wrap gap-x-6 gap-y-3 text-sm text-white/70"
                >
                    <li v-if="projectCount" class="flex items-center gap-2"><Check :size="16" class="text-whatsapp" aria-hidden="true" />{{ t(':count projects delivered', { count: n(projectCount) }) }}</li>
                    <li v-if="industryCount" class="flex items-center gap-2"><Check :size="16" class="text-whatsapp" aria-hidden="true" />{{ t(':count industries', { count: n(industryCount) }) }}</li>
                    <li v-if="site.hours" class="flex items-center gap-2"><Check :size="16" class="text-whatsapp" aria-hidden="true" />{{ site.hours }}</li>
                </motion.ul>
            </motion.div>
        </div>

        <!-- What we do, as a slow ticker along the bottom edge -->
        <div v-if="capabilities.length" class="relative border-t border-white/10 bg-ink-950/40 py-5 backdrop-blur-md">
            <h2 class="sr-only">{{ t('What we do') }}</h2>
            <div class="mask-fade-x overflow-hidden" :style="{ '--marquee-duration': `${Math.max(30, capabilities.length * 6)}s` }">
                <ul class="flex w-max animate-marquee items-center gap-10 pr-10 font-display text-lg text-white/60 motion-reduce:animate-none sm:text-xl">
                    <li v-for="(item, index) in ticker" :key="index" class="flex items-center gap-10 whitespace-nowrap" :aria-hidden="index >= capabilities.length">
                        {{ item }}<span class="text-brand-400" aria-hidden="true">✦</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>
