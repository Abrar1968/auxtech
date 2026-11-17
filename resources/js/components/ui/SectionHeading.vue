<script setup lang="ts">
import Reveal from './Reveal.vue';

withDefaults(
    defineProps<{
        eyebrow?: string | null;
        title?: string | null;
        lead?: string | null;
        align?: 'left' | 'center';
        dark?: boolean;
        as?: 'h1' | 'h2';
    }>(),
    { align: 'left', dark: false, as: 'h2' },
);
</script>

<template>
    <Reveal :class="['max-w-3xl', align === 'center' && 'mx-auto text-center']">
        <p
            v-if="eyebrow"
            :class="[
                'mb-4 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold tracking-[0.14em] uppercase',
                dark ? 'glass-dark text-brand-200' : 'bg-brand-50 text-brand-700 dark:bg-brand-950 dark:text-brand-200',
            ]"
        >
            <span class="size-1.5 rounded-full bg-current" />{{ eyebrow }}
        </p>
        <component
            :is="as"
            v-if="title"
            :class="[
                'font-display font-semibold tracking-tight',
                as === 'h1' ? 'text-4xl leading-[1.08] sm:text-5xl lg:text-6xl' : 'text-3xl leading-tight sm:text-4xl lg:text-[2.75rem]',
                dark ? 'text-white' : 'text-fg',
            ]"
        >
            {{ title }}
        </component>
        <p v-if="lead" :class="['mt-5 text-lg leading-relaxed sm:text-xl', dark ? 'text-white/70' : 'text-muted']">{{ lead }}</p>
        <slot />
    </Reveal>
</template>
