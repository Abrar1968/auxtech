<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useI18n } from '@/lib/i18n';

/**
 * Counts up to a number once it is visible. Non-numeric values (e.g.
 * "24/7") are shown as they are. Plain requestAnimationFrame: no library.
 */
const props = defineProps<{ value: string }>();
const { n } = useI18n();

const target = computed(() => {
    const match = props.value.match(/^(\d+(?:\.\d+)?)/);

    return match ? Number(match[1]) : null;
});
const rest = computed(() => (target.value === null ? props.value : props.value.slice(String(target.value).length)));

const element = ref<HTMLElement>();
const shown = ref<number | null>(target.value);
let observer: IntersectionObserver | undefined;

function run(): void {
    const end = target.value ?? 0;
    const start = performance.now();
    const duration = 1600;
    const step = (now: number) => {
        const t = Math.min(1, (now - start) / duration);
        shown.value = Math.round(end * (1 - Math.pow(1 - t, 4)));
        if (t < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}

onMounted(() => {
    if (target.value === null || window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;

    shown.value = 0;
    observer = new IntersectionObserver(([entry]) => {
        if (entry.isIntersecting) {
            observer?.disconnect();
            run();
        }
    });
    if (element.value) observer.observe(element.value);
});
onBeforeUnmount(() => observer?.disconnect());
</script>

<template>
    <span ref="element" class="tabular-nums">{{ shown === null ? value : n(shown) + rest }}</span>
</template>
