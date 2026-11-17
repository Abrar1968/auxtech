<script setup lang="ts">
import Icon from '@/components/ui/Icon.vue';
import Reveal from '@/components/ui/Reveal.vue';
import type { TextRow } from '@/types/models';

/** Grid of reasons / values with icons and a spotlight that follows the pointer. */
defineProps<{ reasons: TextRow[] }>();

function spotlight(event: PointerEvent): void {
    const target = event.currentTarget as HTMLElement;
    const rect = target.getBoundingClientRect();
    target.style.setProperty('--x', `${event.clientX - rect.left}px`);
    target.style.setProperty('--y', `${event.clientY - rect.top}px`);
}
</script>

<template>
    <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <Reveal v-for="(reason, index) in reasons" :key="index" as="li" :delay="(index % 3) * 0.08">
            <div
                class="group relative h-full overflow-hidden rounded-3xl border border-line bg-surface-raised p-7 transition duration-500 hover:border-brand-300"
                @pointermove="spotlight"
            >
                <div
                    aria-hidden="true"
                    class="pointer-events-none absolute inset-0 opacity-0 transition duration-500 group-hover:opacity-100"
                    style="background: radial-gradient(320px circle at var(--x) var(--y), rgb(1 69 255 / 0.09), transparent 70%)"
                />
                <span class="relative grid size-12 place-items-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-200">
                    <Icon :name="reason.icon ?? 'circle-check'" :size="22" />
                </span>
                <h3 class="relative mt-6 font-display text-xl font-semibold text-fg">{{ reason.title }}</h3>
                <p v-if="reason.description" class="relative mt-2 leading-relaxed text-muted">{{ reason.description }}</p>
            </div>
        </Reveal>
    </ul>
</template>
