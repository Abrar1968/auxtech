<script setup lang="ts">
import { ArrowDown, ArrowUp, Plus, Trash2 } from '@lucide/vue';
import FieldInput from './FieldInput.vue';
import { button } from './ui';
import type { AdminField } from '@/types/models';

/** A list of rows, each with the same sub-fields (features, steps, metrics…). */
const props = defineProps<{ field: AdminField; locale: 'en' | 'bn'; errors: Record<string, string>; id: string }>();
const rows = defineModel<Record<string, unknown>[] | null>({ default: () => [] });

function blank(): Record<string, unknown> {
    return Object.fromEntries(props.field.children.map((child) => [child.name, child.translatable ? { en: '', bn: '' } : (child.default ?? '')]));
}

function add(): void {
    rows.value = [...(rows.value ?? []), blank()];
}

function remove(index: number): void {
    rows.value = (rows.value ?? []).filter((_, i) => i !== index);
}

function move(index: number, by: number): void {
    const list = [...(rows.value ?? [])];
    const [row] = list.splice(index, 1);
    list.splice(index + by, 0, row);
    rows.value = list;
}

function update(index: number, name: string, value: unknown): void {
    const list = [...(rows.value ?? [])];
    list[index] = { ...list[index], [name]: value };
    rows.value = list;
}
</script>

<template>
    <fieldset>
        <legend class="mb-1.5 text-sm font-medium text-[#232428]">{{ field.label }}</legend>
        <p v-if="field.help" class="mb-2 text-xs text-[#5c5f66]">{{ field.help }}</p>
        <ol class="grid gap-2">
            <li v-for="(row, index) in rows ?? []" :key="index" class="rounded-lg border border-[#e3e4e6] bg-[#f7f7f8] p-3">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#5c5f66]">#{{ index + 1 }}</span>
                    <div class="flex gap-0.5">
                        <button type="button" :class="button.ghost" :disabled="index === 0" aria-label="Move up" @click="move(index, -1)"><ArrowUp :size="14" /></button>
                        <button type="button" :class="button.ghost" :disabled="index === (rows?.length ?? 0) - 1" aria-label="Move down" @click="move(index, 1)"><ArrowDown :size="14" /></button>
                        <button type="button" :class="[button.ghost, 'hover:!text-red-600']" aria-label="Remove row" @click="remove(index)"><Trash2 :size="14" /></button>
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <FieldInput
                        v-for="child in field.children"
                        :id="`${id}-${index}-${child.name}`"
                        :key="child.name"
                        :class="child.type === 'textarea' && 'sm:col-span-2'"
                        :field="child"
                        :locale="locale"
                        :model-value="row[child.name]"
                        :error="errors[`${field.name}.${index}.${child.name}${child.translatable ? '.en' : ''}`]"
                        @update:model-value="update(index, child.name, $event)"
                    />
                </div>
            </li>
        </ol>
        <button type="button" :class="[button.secondary, 'mt-2']" @click="add"><Plus :size="16" />Add row</button>
    </fieldset>
</template>
