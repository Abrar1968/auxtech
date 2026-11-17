<script setup lang="ts">
import { computed } from 'vue';

/** Small accessible column chart (SVG) for daily counts, with a table fallback for screen readers. */
const props = defineProps<{ series: { label: string; colour: string; values: Record<string, number> }[]; days: number; title: string }>();

const keys = computed(() => {
    const list: string[] = [];
    const today = new Date();

    for (let i = props.days - 1; i >= 0; i--) {
        const d = new Date(today);
        d.setDate(today.getDate() - i);
        list.push(d.toISOString().slice(0, 10));
    }

    return list;
});
const max = computed(() => Math.max(1, ...props.series.flatMap((s) => keys.value.map((k) => s.values[k] ?? 0))));
const width = 100 / Math.max(1, keys.value.length);
const short = (key: string) => new Intl.DateTimeFormat('en-GB', { day: 'numeric', month: 'short' }).format(new Date(key));
</script>

<template>
    <figure>
        <figcaption class="flex flex-wrap items-center gap-4 text-sm">
            <span class="font-semibold">{{ title }}</span>
            <span v-for="s in series" :key="s.label" class="inline-flex items-center gap-1.5 text-[#55607a]"><span class="size-2.5 rounded-sm" :style="{ background: s.colour }" />{{ s.label }}</span>
        </figcaption>
        <svg viewBox="0 0 100 40" preserveAspectRatio="none" class="mt-4 h-44 w-full" aria-hidden="true">
            <line v-for="y in [10, 20, 30]" :key="y" x1="0" x2="100" :y1="y" :y2="y" stroke="#eef1f6" stroke-width="0.15" />
            <g v-for="(key, i) in keys" :key="key">
                <rect
                    v-for="(s, si) in series"
                    :key="s.label"
                    :x="i * width + (width / (series.length + 1)) * si + width * 0.12"
                    :y="40 - ((s.values[key] ?? 0) / max) * 38"
                    :width="width / (series.length + 1)"
                    :height="((s.values[key] ?? 0) / max) * 38"
                    :fill="s.colour"
                    rx="0.4"
                >
                    <title>{{ short(key) }} · {{ s.label }}: {{ s.values[key] ?? 0 }}</title>
                </rect>
            </g>
        </svg>
        <div class="mt-1 flex justify-between text-[11px] text-[#9aa6c1]"><span>{{ short(keys[0]) }}</span><span>{{ short(keys[keys.length - 1]) }}</span></div>
        <table class="sr-only">
            <thead><tr><th>Day</th><th v-for="s in series" :key="s.label">{{ s.label }}</th></tr></thead>
            <tbody><tr v-for="key in keys" :key="key"><td>{{ key }}</td><td v-for="s in series" :key="s.label">{{ s.values[key] ?? 0 }}</td></tr></tbody>
        </table>
    </figure>
</template>
