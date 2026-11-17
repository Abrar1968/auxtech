<script setup lang="ts">
import { computed } from 'vue';
import AppImage from '@/components/ui/AppImage.vue';
import type { Client } from '@/types/models';

/** Endless strip of client names or logos. Pauses on hover; still with reduced motion. */
const props = defineProps<{ clients: Client[]; dark?: boolean }>();
const loop = computed(() => [...props.clients, ...props.clients]);
</script>

<template>
    <div class="group relative overflow-hidden mask-fade-x" :style="{ '--marquee-duration': `${Math.max(25, clients.length * 3.5)}s` }">
        <ul class="flex w-max animate-marquee gap-3 group-hover:[animation-play-state:paused] motion-reduce:animate-none motion-reduce:flex-wrap">
            <li
                v-for="(client, index) in loop"
                :key="`${client.id}-${index}`"
                :aria-hidden="index >= clients.length"
                :class="[
                    'flex h-14 shrink-0 items-center gap-3 rounded-2xl border px-6 font-display text-[0.95rem] font-medium whitespace-nowrap transition',
                    dark ? 'border-white/10 bg-white/[0.03] text-white/70 hover:text-white' : 'border-line bg-surface-raised text-muted hover:text-fg',
                ]"
            >
                <AppImage v-if="client.logo" :image="client.logo" sizes="120px" fit="contain" class="h-7 w-auto max-w-28 grayscale transition group-hover:grayscale-0" />
                <span v-else>{{ client.name }}</span>
            </li>
        </ul>
    </div>
</template>
