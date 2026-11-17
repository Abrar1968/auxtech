<script setup lang="ts">
import { ImagePlus, Trash2 } from '@lucide/vue';
import { computed, onBeforeUnmount, ref } from 'vue';
import { input } from './ui';
import type { Image } from '@/types/models';

/**
 * One image: preview, replace, remove and alt text in both languages.
 * The new file, the remove flag and the alt text are separate form values.
 */
const props = defineProps<{ current: Image | null; locale: 'en' | 'bn'; error?: string }>();
const file = defineModel<File | null>('file', { default: null });
const remove = defineModel<boolean>('remove', { default: false });
const alt = defineModel<Record<string, string>>('alt', { default: () => ({ en: '', bn: '' }) });

const preview = ref<string | null>(null);
const shown = computed(() => (remove.value ? null : (preview.value ?? props.current?.medium ?? null)));

function pick(event: Event): void {
    const chosen = (event.target as HTMLInputElement).files?.[0];

    if (!chosen) return;
    file.value = chosen;
    remove.value = false;
    if (preview.value) URL.revokeObjectURL(preview.value);
    preview.value = URL.createObjectURL(chosen);
}

function clear(): void {
    file.value = null;
    remove.value = true;
    if (preview.value) URL.revokeObjectURL(preview.value);
    preview.value = null;
}

onBeforeUnmount(() => preview.value && URL.revokeObjectURL(preview.value));
</script>

<template>
    <div class="grid gap-2">
        <div class="group relative overflow-hidden rounded-lg border border-dashed border-[#c5ccda] bg-[#fafbfd]">
            <img v-if="shown" :src="shown" alt="" class="aspect-[16/10] w-full object-cover object-top" />
            <label v-else class="grid aspect-[16/10] cursor-pointer place-items-center text-center text-sm text-[#55607a] hover:bg-[#f0f2f7]">
                <span><ImagePlus :size="24" class="mx-auto mb-1" />Upload image<br /><span class="text-xs">JPG, PNG or WebP, up to 6 MB</span></span>
                <input type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick" />
            </label>
            <div v-if="shown" class="absolute inset-x-0 bottom-0 flex justify-end gap-1 bg-gradient-to-t from-black/50 p-2 opacity-0 transition group-hover:opacity-100 focus-within:opacity-100">
                <label class="cursor-pointer rounded-md bg-white px-2.5 py-1 text-xs font-medium">Replace<input type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick" /></label>
                <button type="button" class="rounded-md bg-white p-1 text-red-600" aria-label="Remove image" @click="clear"><Trash2 :size="14" /></button>
            </div>
        </div>
        <input v-if="shown" v-model="alt[locale]" :class="input" :placeholder="locale === 'en' ? 'Alt text (describe the image)' : 'Alt text in Bangla'" maxlength="255" />
        <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
    </div>
</template>
