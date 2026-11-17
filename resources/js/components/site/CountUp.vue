<script setup lang="ts">
import { animate, useInView } from 'motion-v';
import { computed, ref, watch } from 'vue';
import { useI18n } from '@/lib/i18n';

/**
 * Counts up to a number once it is visible. Non-numeric values (e.g.
 * "24/7") are shown as they are.
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
const inView = useInView(element, { once: true });

watch(inView, (visible) => {
    if (!visible || target.value === null || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    animate(0, target.value, {
        duration: 1.6,
        ease: [0.16, 1, 0.3, 1],
        onUpdate: (latest) => (shown.value = Math.round(latest)),
    });
});
</script>

<template>
    <span ref="element" class="tabular-nums">{{ shown === null ? value : n(shown) + rest }}</span>
</template>
