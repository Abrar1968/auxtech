<script setup lang="ts">
import AppImage from '@/components/ui/AppImage.vue';
import type { Image } from '@/types/models';

/** A browser window drawn in CSS, framing a client screenshot. */
withDefaults(defineProps<{ image?: Image | null; url?: string | null; priority?: boolean; sizes?: string; dark?: boolean }>(), {
    dark: true,
});

function host(url: string): string {
    try {
        return new URL(url).host.replace(/^www\./, '');
    } catch {
        return url;
    }
}
</script>

<template>
    <div
        :class="[
            'overflow-hidden rounded-2xl border shadow-[0_30px_80px_-30px_rgb(1_20_80/0.55)]',
            dark ? 'border-white/10 bg-ink-800' : 'border-line bg-surface-raised',
        ]"
    >
        <div :class="['flex items-center gap-2 px-3.5 py-2.5', dark ? 'bg-white/5' : 'bg-surface-muted']">
            <span class="size-2.5 rounded-full bg-[#ff5f57]" />
            <span class="size-2.5 rounded-full bg-[#febc2e]" />
            <span class="size-2.5 rounded-full bg-[#28c840]" />
            <span
                v-if="url"
                :class="[
                    'ml-2 truncate rounded-md px-2.5 py-0.5 text-[11px] font-medium',
                    dark ? 'bg-white/8 text-white/60' : 'bg-surface text-muted',
                ]"
            >
                {{ host(url) }}
            </span>
        </div>
        <div class="relative aspect-[16/10] overflow-hidden">
            <AppImage
                v-if="image"
                :image="image"
                :priority="priority"
                :sizes="sizes ?? '(min-width: 1024px) 40vw, 90vw'"
                class="absolute inset-0 size-full object-top transition duration-[1.6s] ease-out-expo group-hover:scale-[1.03]"
            />
            <slot v-else />
        </div>
    </div>
</template>
