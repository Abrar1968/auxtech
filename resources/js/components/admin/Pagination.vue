<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

/** Laravel paginator links. Labels arrive as HTML entities («, »), decoded here. */
defineProps<{ links: { url: string | null; label: string; active: boolean }[] }>();

const text = (label: string) => label.replace('&laquo;', '‹').replace('&raquo;', '›');
</script>

<template>
    <nav v-if="links.length > 3" class="flex flex-wrap justify-end gap-1 text-sm" aria-label="Pagination">
        <template v-for="link in links" :key="link.label">
            <Link v-if="link.url" :href="link.url" preserve-scroll :class="['min-w-9 rounded-md px-2.5 py-1.5 text-center', link.active ? 'bg-brand-600 text-white' : 'hover:bg-[#f0f2f7]']" :aria-current="link.active ? 'page' : undefined">{{ text(link.label) }}</Link>
            <span v-else class="min-w-9 rounded-md px-2.5 py-1.5 text-center text-[#c5ccda]">{{ text(link.label) }}</span>
        </template>
    </nav>
</template>
