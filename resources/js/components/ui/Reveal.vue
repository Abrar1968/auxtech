<script setup lang="ts">
import { motion } from 'motion-v';

/**
 * Fades and lifts its content in when it scrolls into view. Content is
 * never hidden without JavaScript (the initial state is applied by Motion).
 */
withDefaults(defineProps<{ delay?: number; y?: number; as?: string }>(), { delay: 0, y: 28, as: 'div' });
</script>

<template>
    <component
        :is="motion[as as 'div']"
        :initial="{ opacity: 0, y }"
        :while-in-view="{ opacity: 1, y: 0 }"
        :in-view-options="{ once: true, margin: '0px 0px -10% 0px' }"
        :transition="{ duration: 0.75, delay, ease: [0.16, 1, 0.3, 1] }"
    >
        <slot />
    </component>
</template>
