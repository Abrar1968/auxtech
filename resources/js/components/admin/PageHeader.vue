<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft } from '@lucide/vue';

defineProps<{ title: string; description?: string | null; back?: string }>();
</script>

<template>
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div class="min-w-0">
            <Link v-if="back" :href="back" class="mb-1 inline-flex items-center gap-1 text-sm text-[#55607a] hover:text-[#0a0f24]"><ChevronLeft :size="16" />Back</Link>
            <h1 class="truncate font-display text-2xl font-semibold tracking-tight">{{ title }}</h1>
            <p v-if="description" class="mt-1 text-sm text-[#55607a]">{{ description }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2"><slot /></div>
    </div>
</template>
