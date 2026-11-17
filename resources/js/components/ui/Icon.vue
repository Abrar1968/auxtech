<script setup lang="ts">
import { computed } from 'vue';
import { iconFor } from '@/lib/icons';

const props = defineProps<{ name?: string | null; size?: number }>();
const component = computed(() => iconFor(props.name));
</script>

<template>
    <component :is="component" :size="size ?? 20" :stroke-width="1.6" aria-hidden="true" />
</template>
