<script setup lang="ts">
import { ArrowRight, ArrowUpRight, Hand } from '@lucide/vue';
import { Link } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import AppImage from '@/components/ui/AppImage.vue';
import Button from '@/components/ui/Button.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { Image, SectionCopy } from '@/types/models';

/**
 * Selected work as a curved gallery: tall cards (each project's phone
 * screenshot, the client's logo and facts about the build) glide along the
 * inside of a cylinder. It moves by itself, pauses under the pointer and can
 * be dragged. The chips state facts about each project, never results.
 */
export type ShowcaseProject = {
    id: number;
    title: string;
    url: string;
    industry: string | null;
    platform: string | null;
    tech: string | null;
    modules: number | null;
    demo: boolean;
    image: Image | null;
    logo: Image | null;
};

defineProps<{ projects: ShowcaseProject[]; total: number; copy?: SectionCopy; eyebrow?: string }>();
const { t, tc, n, lp } = useI18n();

const stage = ref<HTMLElement>();
const cards = ref<HTMLElement[]>([]);

let offset = 0; // px travelled along the arc
let velocity = 0; // extra speed from dragging, decays
let paused = false;
let dragging = false;
let dragX = 0;
let moved = 0;
let frame = 0;
let last = 0;
let visible = true;
let reduced = false;
let observer: IntersectionObserver | undefined;

function layout(): void {
    const el = stage.value;
    if (!el) return;
    const width = el.clientWidth;
    const card = cards.value[0]?.offsetWidth ?? 260;
    const gap = width < 640 ? 14 : 22;
    const pitch = card + gap;
    const count = cards.value.length;
    const loop = pitch * count;
    // A curved screen seen from inside: cards keep even gaps along the row,
    // turn toward the centre and come closer the further out they are, so
    // the row's top and bottom edges bow outward (as on DOE Media).
    const half = width / 2;
    cards.value.forEach((node, i) => {
        // Position along the row, wrapped so it never ends.
        let s = (((i * pitch - offset) % loop) + loop) % loop;
        if (s > loop / 2) s -= loop;
        const u = s / half; // -1 at the left edge, 1 at the right edge
        const hidden = Math.abs(u) > 1.45;
        const x = s * (1 + 0.24 * Math.abs(u));
        const z = Math.min(1.8, u * u) * card * 0.95;
        const turn = Math.max(-0.8, Math.min(0.8, u * 0.58));
        node.style.transform = `translate3d(${x}px, 0, ${z}px) rotateY(${-turn}rad)`;
        node.style.opacity = hidden ? '0' : '1';
        node.style.visibility = hidden ? 'hidden' : 'visible';
        node.style.zIndex = String(Math.round(Math.abs(s)));
    });
}

function tick(now: number): void {
    frame = requestAnimationFrame(tick);
    const dt = Math.min(64, now - (last || now));
    last = now;
    if (!visible) return;
    const glide = reduced || paused || dragging ? 0 : 0.035; // px per ms
    offset += glide * dt + velocity * dt;
    velocity *= Math.pow(0.92, dt / 16);
    if (Math.abs(velocity) < 0.002) velocity = 0;
    layout();
}

function down(e: PointerEvent): void {
    dragging = true;
    dragX = e.clientX;
    moved = 0;
    velocity = 0;
}
function move(e: PointerEvent): void {
    if (!dragging) return;
    const dx = e.clientX - dragX;
    dragX = e.clientX;
    moved += Math.abs(dx);
    offset -= dx * 1.2;
    velocity = (-dx * 1.2) / 16;
    if (reduced) layout();
}
function up(): void {
    dragging = false;
}
function enter(): void {
    paused = true;
}
function leave(): void {
    dragging = false;
    paused = false;
}
// A drag is not a click: keep the card link from opening after dragging.
function click(e: MouseEvent): void {
    if (moved > 6) {
        e.preventDefault();
        e.stopPropagation();
    }
}
// Keyboard: bring the focused card to the centre.
function focusCard(index: number): void {
    const el = stage.value;
    if (!el) return;
    const card = cards.value[0]?.offsetWidth ?? 260;
    const pitch = card + (el.clientWidth < 640 ? 14 : 22);
    offset = index * pitch;
    layout();
}
function nudge(direction: number): void {
    velocity = direction * 1.1;
    if (reduced) {
        focusCard(Math.round(offset / ((cards.value[0]?.offsetWidth ?? 260) + 22)) + direction);
    }
}

onMounted(() => {
    reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
    // Start with the first project in the centre.
    offset = 0;
    layout();
    observer = new IntersectionObserver(([entry]) => (visible = entry.isIntersecting));
    if (stage.value) observer.observe(stage.value);
    addEventListener('resize', layout);
    frame = requestAnimationFrame(tick);
});

onBeforeUnmount(() => {
    cancelAnimationFrame(frame);
    observer?.disconnect();
    removeEventListener('resize', layout);
});
</script>

<template>
    <section class="relative overflow-hidden bg-ink-950 pt-20 pb-16 text-white sm:pt-28 sm:pb-20">
        <div aria-hidden="true" class="absolute inset-x-0 top-1/3 -z-0 mx-auto h-[28rem] max-w-5xl rounded-full bg-[radial-gradient(closest-side,rgb(255_201_60/0.12),transparent)] blur-2xl" />
        <div class="relative container-page">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <SectionHeading :eyebrow="eyebrow" :title="copy?.title" :lead="copy?.lead" dark />
                <Button :href="lp('/work')" variant="glass">
                    {{ t('View all :count projects', { count: n(total) }) }} <ArrowRight :size="18" aria-hidden="true" />
                </Button>
            </div>
        </div>

        <!-- The curved track -->
        <div
            ref="stage"
            class="relative mt-14 h-[25rem] cursor-grab touch-pan-y select-none active:cursor-grabbing sm:h-[33rem]"
            style="perspective: 1100px; perspective-origin: 50% 40%"
            @pointerdown="down"
            @pointermove="move"
            @pointerup="up"
            @pointerleave="leave"
            @pointerenter="enter"
        >
            <ul class="absolute inset-0" style="transform-style: preserve-3d" :aria-label="t('Selected projects')">
                <li
                    v-for="(project, index) in projects"
                    :key="project.id"
                    ref="cards"
                    class="absolute top-4 left-1/2 -ml-[6.5rem] h-[22rem] w-[13rem] will-change-transform sm:-ml-[8.25rem] sm:h-[29.5rem] sm:w-[16.5rem]"
                >
                    <Link
                        :href="project.url"
                        draggable="false"
                        class="group relative block size-full overflow-hidden rounded-[1.4rem] bg-ink-900 shadow-[0_30px_60px_-30px_rgb(0_0_0/0.8)] ring-1 ring-white/10 transition duration-500 hover:ring-brand-600/70 focus-visible:ring-2 focus-visible:ring-brand-600"
                        @click="click"
                        @focus="focusCard(index)"
                    >
                        <AppImage
                            v-if="project.image"
                            :image="project.image"
                            :alt="project.title"
                            sizes="(min-width: 640px) 264px, 208px"
                            class="absolute inset-0 size-full object-cover object-top transition duration-700 group-hover:scale-[1.04]"
                            draggable="false"
                        />
                        <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-b from-ink-950/55 via-ink-950/10 to-ink-950/95" />

                        <!-- Logo -->
                        <div class="absolute inset-x-0 top-5 flex justify-center px-5">
                            <span v-if="project.logo" class="flex h-12 max-w-full items-center rounded-xl bg-white px-3 shadow-lg sm:h-14">
                                <AppImage :image="project.logo" :alt="''" sizes="160px" fit="contain" class="max-h-8 w-auto max-w-[9rem] sm:max-h-10" draggable="false" />
                            </span>
                            <span v-else class="rounded-xl bg-white px-3 py-2 font-display text-base font-semibold text-ink-950">{{ project.title }}</span>
                        </div>

                        <!-- Facts -->
                        <div class="absolute inset-x-3 bottom-3 grid gap-2 sm:inset-x-4 sm:bottom-4">
                            <p class="flex items-center justify-between gap-2 px-1 font-display text-lg font-semibold sm:text-xl">
                                <span class="truncate">{{ project.title }}</span>
                                <ArrowUpRight :size="18" class="shrink-0 text-brand-600 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true" />
                            </p>
                            <dl class="grid gap-1.5 text-[0.7rem] sm:text-xs">
                                <div v-if="project.platform" class="flex items-baseline justify-between gap-3 rounded-xl border border-white/15 bg-white/10 px-3 py-2 backdrop-blur-md">
                                    <dt class="text-white/70 uppercase">{{ t('Platform') }}</dt>
                                    <dd class="truncate text-right font-semibold">{{ project.platform }}</dd>
                                </div>
                                <div v-if="project.industry" class="flex items-baseline justify-between gap-3 rounded-xl border border-white/15 bg-white/10 px-3 py-2 backdrop-blur-md">
                                    <dt class="text-white/70 uppercase">{{ t('Industry') }}</dt>
                                    <dd class="truncate text-right font-semibold">{{ project.industry }}</dd>
                                </div>
                                <div v-if="project.modules || project.tech" class="flex items-baseline justify-between gap-3 rounded-xl border border-white/15 bg-white/10 px-3 py-2 backdrop-blur-md">
                                    <dt class="text-white/70 uppercase">{{ project.modules ? t('Modules') : t('Built with') }}</dt>
                                    <dd class="truncate text-right font-semibold">
                                        <template v-if="project.modules">{{ tc(':count module', ':count modules', project.modules) }}</template>
                                        <template v-else>{{ project.tech }}</template>
                                    </dd>
                                </div>
                            </dl>
                            <span v-if="project.demo" class="absolute -top-8 right-1 rounded-full bg-brand-600 px-2.5 py-0.5 text-[0.65rem] font-semibold text-ink-950 uppercase">{{ t('Demo') }}</span>
                        </div>
                    </Link>
                </li>
            </ul>
        </div>

        <div class="relative container-page mt-6 flex items-center justify-center gap-3 text-sm text-white/70">
            <button type="button" class="grid size-10 place-items-center rounded-full border border-white/15 transition hover:border-brand-600 hover:text-brand-600" :aria-label="t('Previous')" @click="nudge(-1)">
                <ArrowRight :size="16" class="rotate-180" aria-hidden="true" />
            </button>
            <span class="inline-flex items-center gap-2"><Hand :size="16" aria-hidden="true" />{{ t('Drag to explore') }}</span>
            <button type="button" class="grid size-10 place-items-center rounded-full border border-white/15 transition hover:border-brand-600 hover:text-brand-600" :aria-label="t('Next')" @click="nudge(1)">
                <ArrowRight :size="16" aria-hidden="true" />
            </button>
        </div>
    </section>
</template>
