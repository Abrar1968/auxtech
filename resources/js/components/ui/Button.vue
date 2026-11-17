<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * One button for the whole site. Renders an Inertia <Link> for internal
 * paths, <a> for external ones and <button> otherwise.
 */
const props = withDefaults(
    defineProps<{
        href?: string;
        variant?: 'primary' | 'whatsapp' | 'secondary' | 'glass' | 'ghost' | 'danger';
        size?: 'sm' | 'md' | 'lg';
        type?: 'button' | 'submit';
        external?: boolean;
        disabled?: boolean;
        block?: boolean;
    }>(),
    { variant: 'primary', size: 'md', type: 'button', external: undefined },
);

const isExternal = computed(
    () => props.external ?? (!!props.href && /^(https?:|mailto:|tel:)/.test(props.href) && !props.href.startsWith(location?.origin ?? '#')),
);

const classes = computed(() => [
    'group/button relative inline-flex items-center justify-center gap-2 rounded-full font-medium whitespace-nowrap transition duration-300 ease-out-expo select-none',
    'disabled:pointer-events-none disabled:opacity-50 active:scale-[0.98]',
    props.block && 'w-full',
    {
        sm: 'min-h-9 px-4 text-sm',
        md: 'min-h-11 px-5 text-[0.95rem]',
        lg: 'min-h-13 px-7 text-base',
    }[props.size],
    {
        primary:
            'bg-brand-600 text-white shadow-[0_8px_30px_-8px_rgb(1_69_255/0.7)] hover:bg-brand-500 hover:shadow-[0_10px_40px_-8px_rgb(1_69_255/0.9)]',
        whatsapp:
            'bg-whatsapp text-ink-950 shadow-[0_8px_30px_-8px_rgb(37_211_102/0.65)] hover:brightness-105 hover:shadow-[0_10px_40px_-6px_rgb(37_211_102/0.8)]',
        secondary: 'border border-line bg-surface-raised text-fg hover:border-brand-400 hover:text-brand-600',
        glass: 'glass-dark text-white hover:bg-white/12',
        ghost: 'text-fg hover:bg-surface-muted',
        danger: 'bg-red-600 text-white hover:bg-red-500',
    }[props.variant],
]);
</script>

<template>
    <Link v-if="href && !isExternal" :href="href" :class="classes" prefetch="hover">
        <slot />
    </Link>
    <a v-else-if="href" :href="href" :class="classes" :target="href.startsWith('http') ? '_blank' : undefined" rel="noopener">
        <slot />
    </a>
    <button v-else :type="type" :disabled="disabled" :class="classes">
        <slot />
    </button>
</template>
