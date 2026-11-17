<script setup lang="ts">
import { computed } from 'vue';
import type { Image } from '@/types/models';

/** Responsive image from a media-library record (thumb 480, medium 960, large 1600). */
const props = withDefaults(
    defineProps<{ image: Image; sizes?: string; priority?: boolean; alt?: string; fit?: 'cover' | 'contain' }>(),
    { sizes: '(min-width: 1024px) 50vw, 100vw', priority: false, fit: 'cover' },
);

const srcset = computed(() => {
    const entries = [
        [props.image.thumb, 480],
        [props.image.medium, 960],
        [props.image.large, 1600],
    ] as const;

    return [...new Map(entries.map(([url, width]) => [url, `${url} ${width}w`])).values()].join(', ');
});
</script>

<template>
    <img
        :src="image.medium"
        :srcset="srcset"
        :sizes="sizes"
        :alt="alt ?? image.alt"
        :width="image.width ?? 1280"
        :height="image.height ?? 800"
        :loading="priority ? 'eager' : 'lazy'"
        :fetchpriority="priority ? 'high' : 'auto'"
        decoding="async"
        :class="fit === 'cover' ? 'object-cover' : 'object-contain'"
    />
</template>
