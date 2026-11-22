<script setup lang="ts">
import { computed, ref } from 'vue';
import RichEditor from './RichEditor.vue';
import { input } from './ui';
import type { AdminField } from '@/types/models';

/**
 * Input for one admin field, chosen by its type. Translatable fields edit
 * the language selected at the top of the form.
 */
const props = defineProps<{ field: AdminField; locale: 'en' | 'bn'; error?: string; id: string }>();
const model = defineModel<unknown>();

const text = computed({
    get: () => {
        const value = props.field.translatable ? (model.value as Record<string, string> | null)?.[props.locale] : model.value;

        return (value ?? '') as string;
    },
    set: (value: string) => {
        if (props.field.translatable) {
            model.value = { ...((model.value as Record<string, string>) ?? {}), [props.locale]: value };
        } else {
            model.value = value;
        }
    },
});

const selected = computed<(string | number)[]>({
    get: () => ((model.value as (string | number)[]) ?? []).map(String),
    set: (value) => (model.value = value),
});
const filter = ref('');
const visibleOptions = computed(() =>
    (props.field.options ?? []).filter((option) => !filter.value || option.label.toLowerCase().includes(filter.value.toLowerCase())),
);

function toggle(value: string | number): void {
    const key = String(value);
    selected.value = selected.value.includes(key) ? selected.value.filter((v) => v !== key) : [...selected.value, key];
}

const length = computed(() => text.value.length);
const invalid = computed(() => (props.error ? 'border-red-400 focus:border-red-500 focus:ring-red-500/15' : ''));
</script>

<template>
    <div v-if="field.type === 'toggle'" class="flex items-center justify-between gap-4">
        <label :for="id" class="text-sm font-medium text-[#232428]">{{ field.label }}</label>
        <button
            :id="id"
            type="button"
            role="switch"
            :aria-checked="!!model"
            :class="['relative h-6 w-11 shrink-0 rounded-full transition', model ? 'bg-brand-600' : 'bg-[#d9dadc]']"
            @click="model = !model"
        >
            <span :class="['absolute top-0.5 left-0.5 size-5 rounded-full bg-white shadow transition', model && 'translate-x-5']" />
        </button>
    </div>

    <div v-else>
        <div class="mb-1.5 flex items-baseline justify-between gap-2">
            <label :for="id" class="text-sm font-medium text-[#232428]">
                {{ field.label }}<span v-if="field.required" class="text-red-500"> *</span>
                <span v-if="field.translatable" class="ml-1.5 rounded bg-[#fff2c9] px-1.5 py-0.5 text-[10px] font-semibold text-brand-900 uppercase">{{ locale }}</span>
            </label>
            <span v-if="field.max && ['text', 'textarea'].includes(field.type) && length > field.max * 0.6" :class="['text-xs', length > field.max ? 'text-red-600' : 'text-[#85888f]']">{{ length }}/{{ field.max }}</span>
        </div>

        <textarea v-if="field.type === 'textarea'" :id="id" v-model="text" rows="4" :class="[input, invalid]" />

        <RichEditor v-else-if="field.type === 'richtext'" :id="id" :key="`${id}-${locale}`" v-model="text" :invalid="!!error" />

        <select v-else-if="field.type === 'select'" :id="id" v-model="model" :class="[input, invalid]">
            <option :value="null">{{ field.required ? 'Choose…' : '—' }}</option>
            <option v-for="option in field.options ?? []" :key="option.value" :value="option.value">{{ option.label }}</option>
        </select>

        <div v-else-if="field.type === 'multiselect'" :class="['rounded-lg border border-[#d9dadc] bg-white', invalid]">
            <input v-if="(field.options?.length ?? 0) > 8" v-model="filter" type="search" placeholder="Filter…" class="w-full border-b border-[#e3e4e6] px-3 py-2 text-sm outline-none" />
            <ul class="max-h-56 overflow-y-auto p-1.5">
                <li v-for="option in visibleOptions" :key="option.value">
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-md px-2 py-1.5 text-sm hover:bg-[#f4f4f5]">
                        <input type="checkbox" class="size-4 accent-brand-600" :checked="selected.includes(String(option.value))" @change="toggle(option.value)" />
                        {{ option.label }}
                    </label>
                </li>
                <li v-if="!visibleOptions.length" class="px-2 py-1.5 text-sm text-[#85888f]">Nothing to choose.</li>
            </ul>
        </div>

        <input
            v-else
            :id="id"
            v-model="text"
            :type="({ number: 'number', url: 'url', email: 'email', password: 'password', date: 'date', datetime: 'datetime-local' } as Record<string, string>)[field.type] ?? 'text'"
            :class="[input, invalid]"
            :autocomplete="field.type === 'password' ? 'new-password' : 'off'"
        />

        <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
        <p v-else-if="field.help" class="mt-1 text-xs text-[#5c5f66]">{{ field.help }}</p>
    </div>
</template>
