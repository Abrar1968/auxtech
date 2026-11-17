<script setup lang="ts">
import { computed } from 'vue';
import { card, input } from './ui';

export interface SeoValues {
    title: Record<string, string | null>;
    description: Record<string, string | null>;
    canonical: string | null;
    robots: string | null;
}

/** Search appearance overrides with a Google result preview. */
const props = defineProps<{ locale: 'en' | 'bn'; fallbackTitle: string; publicUrl?: string; errors: Record<string, string> }>();
const seo = defineModel<SeoValues>({ required: true });

const title = computed({
    get: () => seo.value.title[props.locale] ?? '',
    set: (value: string) => (seo.value = { ...seo.value, title: { ...seo.value.title, [props.locale]: value } }),
});
const description = computed({
    get: () => seo.value.description[props.locale] ?? '',
    set: (value: string) => (seo.value = { ...seo.value, description: { ...seo.value.description, [props.locale]: value } }),
});
const previewTitle = computed(() => `${title.value || props.fallbackTitle || 'Page title'} | Auxtech`);
const counter = (length: number, ideal: number, max: number) => (length > max ? 'text-red-600' : length > ideal ? 'text-amber-600' : 'text-[#9aa6c1]');
</script>

<template>
    <section :class="[card, 'p-5']">
        <h2 class="text-sm font-semibold">Search appearance <span class="ml-1 rounded bg-[#eef4ff] px-1.5 py-0.5 text-[10px] font-semibold text-brand-700 uppercase">{{ locale }}</span></h2>
        <p class="mt-1 text-xs text-[#55607a]">Optional. Empty fields use the title and summary.</p>

        <div class="mt-4 rounded-lg border border-[#e4e8f0] p-3">
            <p class="truncate text-xs text-[#202124]">{{ publicUrl ?? 'auxtechbd.com' }}</p>
            <p class="mt-0.5 line-clamp-1 text-[17px] text-[#1a0dab]">{{ previewTitle }}</p>
            <p class="mt-0.5 line-clamp-2 text-[13px] text-[#4d5156]">{{ description || 'The page summary will be used.' }}</p>
        </div>

        <label class="mt-4 block text-sm font-medium" for="seo-title">Title</label>
        <input id="seo-title" v-model="title" :class="[input, 'mt-1']" maxlength="70" />
        <p :class="['mt-1 text-right text-xs', counter(title.length, 55, 60)]">{{ title.length }}/60</p>
        <p v-if="errors[`seo.title.${locale}`]" class="text-xs text-red-600">{{ errors[`seo.title.${locale}`] }}</p>

        <label class="mt-2 block text-sm font-medium" for="seo-description">Description</label>
        <textarea id="seo-description" v-model="description" rows="3" :class="[input, 'mt-1']" maxlength="170" />
        <p :class="['mt-1 text-right text-xs', counter(description.length, 150, 160)]">{{ description.length }}/160</p>

        <details class="mt-2 text-sm">
            <summary class="cursor-pointer text-[#55607a]">Advanced</summary>
            <label class="mt-3 block text-sm font-medium" for="seo-canonical">Canonical URL</label>
            <input id="seo-canonical" v-model="seo.canonical" type="url" :class="[input, 'mt-1']" placeholder="Leave empty for this page" />
            <label class="mt-3 block text-sm font-medium" for="seo-robots">Search engines</label>
            <select id="seo-robots" v-model="seo.robots" :class="[input, 'mt-1']">
                <option :value="null">Index (default)</option>
                <option value="noindex, follow">Do not index</option>
                <option value="noindex, nofollow">Do not index or follow links</option>
            </select>
        </details>
    </section>
</template>
