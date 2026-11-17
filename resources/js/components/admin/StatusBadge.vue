<script setup lang="ts">
import { statusColours } from './ui';

defineProps<{ value: string | null }>();
</script>

<template>
    <span v-if="value" :class="['inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium capitalize ring-1 ring-inset', statusColours[value] ?? 'bg-[#f0f2f7] text-[#55607a] ring-[#d5dbe7]']">
        {{ value.replaceAll('_', ' ') }}
    </span>
</template>
