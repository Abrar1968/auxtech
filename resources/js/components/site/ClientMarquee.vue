<script setup lang="ts">
import { computed } from 'vue';
import AppImage from '@/components/ui/AppImage.vue';
import type { Client } from '@/types/models';

/**
 * Client logo carousel: two rows moving in opposite directions (one row when
 * there are few clients). Pauses on hover; static and wrapped with reduced motion.
 * Clients without an uploaded logo show their name.
 */
const props = defineProps<{ clients: Client[]; dark?: boolean }>();

const rows = computed(() => {
    if (props.clients.length < 10) {
        return [props.clients];
    }
    const half = Math.ceil(props.clients.length / 2);
    return [props.clients.slice(0, half), props.clients.slice(half)];
});
</script>

<template>
    <div class="flex flex-col gap-4">
        <div
            v-for="(row, r) in rows"
            :key="r"
            class="group relative overflow-hidden mask-fade-x"
            :style="{ '--marquee-duration': `${Math.max(30, row.length * 4)}s` }"
        >
            <ul
                :class="[
                    'flex w-max gap-4 pr-4 group-hover:[animation-play-state:paused] motion-reduce:animate-none motion-reduce:flex-wrap',
                    r % 2 ? 'animate-marquee-reverse' : 'animate-marquee',
                ]"
            >
                <li
                    v-for="(client, index) in [...row, ...row]"
                    :key="`${client.id}-${index}`"
                    :aria-hidden="index >= row.length"
                    :class="[
                        'flex h-24 w-48 shrink-0 items-center justify-center rounded-2xl border px-5 transition duration-300 sm:h-28 sm:w-56',
                        dark ? 'border-white/10 bg-white/[0.04] hover:border-white/25' : 'border-line bg-surface-raised hover:border-brand-300 hover:shadow-[0_18px_40px_-24px_rgb(1_69_255/0.45)]',
                    ]"
                >
                    <a
                        v-if="client.website_url"
                        :href="client.website_url"
                        target="_blank"
                        rel="noopener"
                        :tabindex="index >= row.length ? -1 : undefined"
                        class="peer flex size-full items-center justify-center"
                        :aria-label="client.name"
                    >
                        <AppImage
                            v-if="client.logo"
                            :image="client.logo"
                            sizes="200px"
                            fit="contain"
                            class="max-h-14 w-auto max-w-full opacity-75 grayscale transition duration-300 hover:opacity-100 hover:grayscale-0 dark:brightness-0 dark:invert dark:hover:brightness-100 dark:hover:invert-0"
                        />
                        <span v-else :class="['text-center font-display text-base font-semibold', dark ? 'text-white/70' : 'text-muted']">{{ client.name }}</span>
                    </a>
                    <template v-else>
                        <AppImage v-if="client.logo" :image="client.logo" sizes="200px" fit="contain" class="max-h-14 w-auto max-w-full opacity-75 grayscale" />
                        <span v-else class="text-center font-display text-base font-semibold text-muted">{{ client.name }}</span>
                    </template>
                </li>
            </ul>
        </div>
    </div>
</template>
