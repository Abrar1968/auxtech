<script setup lang="ts">
import { statusColours } from './ui';

defineProps<{ value: string | null }>();
</script>

<template>
    <span v-if="value" :class="['inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium capitalize ring-1 ring-inset', statusColours[value] ?? 'bg-[#ececed] text-[#5c5f66] ring-[#d9dadc]']">
        {{ value.replaceAll('_', ' ') }}
    </span>
</template>
