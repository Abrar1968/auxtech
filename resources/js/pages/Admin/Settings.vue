<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ArrowDown, ArrowUp, CircleCheck, LoaderCircle, Plus, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import { button, card, input, inputInline } from '@/components/admin/ui';
import Icon from '@/components/ui/Icon.vue';

/** Settings, grouped in tabs. The schema (config/settings.php) decides each input. */
type SchemaField = { key: string; type: string; label: string; help: string | null; item: Record<string, string> | null; translatable: boolean };
type Group = { key: string; label: string; fields: SchemaField[] };
type T = Record<string, string | null>;

const props = defineProps<{ values: Record<string, unknown>; secrets: Record<string, boolean>; schema: Group[]; icons: string[] }>();

const tab = ref(props.schema[0]?.key ?? '');
const locale = ref<'en' | 'bn'>('en');
const group = computed(() => props.schema.find((g) => g.key === tab.value) ?? props.schema[0]);

const clone = <V,>(value: V): V => JSON.parse(JSON.stringify(value ?? null));
const form = useForm({
    values: Object.fromEntries(
        props.schema.flatMap((g) => g.fields).map((f) => {
            const value = clone(props.values[f.key]);

            if (f.translatable) return [f.key, { en: '', bn: '', ...((value as T) ?? {}) }];
            if (f.type === 'copy') return [f.key, { title: { en: '', bn: '' }, lead: { en: '', bn: '' }, ...((value as object) ?? {}) }];
            if (f.type === 'secret') return [f.key, ''];
            return [f.key, value ?? (['list', 'sections', 'days'].includes(f.type) ? [] : '')];
        }),
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
    ) as Record<string, any>,
});
const saved = ref(false);

function save(): void {
    // Only the open tab is sent, so other tabs cannot be overwritten by accident.
    const keys = group.value.fields.map((f) => f.key);
    form.transform((data) => ({ values: Object.fromEntries(keys.map((k) => [k, data.values[k]])) })).put('/admin/settings', {
        preserveScroll: true,
        onSuccess: () => {
            saved.value = true;
            setTimeout(() => (saved.value = false), 2500);
            for (const f of group.value.fields) if (f.type === 'secret') form.values[f.key] = '';
        },
    });
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const v = form.values as Record<string, any>;
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const list = (key: string) => v[key] as any[];
const days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
const sectionNames: Record<string, string> = {
    hero: 'Hero', clients: 'Client strip', stats: 'Numbers', services: 'Services', flagship: 'Auxtech Ecommerce', work: 'Featured work', solutions: 'Beyond e-commerce',
    process: 'Process', testimonials: 'Testimonials', reasons: 'Why Auxtech', pricing: 'Pricing', posts: 'Blog', faq: 'FAQ', cta: 'Closing call to action',
};

function move(list: unknown[], index: number, by: number): void {
    const [item] = list.splice(index, 1);
    list.splice(index + by, 0, item);
}
function addRow(field: SchemaField): void {
    const row = Object.fromEntries(Object.entries(field.item ?? {}).map(([name, type]) => [name, type === 'icon' ? 'circle-check' : { en: '', bn: '' }]));
    (v[field.key] as unknown[]).push(row);
}
function toggleDay(key: string, day: string): void {
    const list = v[key] as string[];
    v[key] = list.includes(day) ? list.filter((d) => d !== day) : days.filter((d) => [...list, day].includes(d));
}
const hasTranslatable = computed(() => group.value.fields.some((f) => f.translatable || f.type === 'copy' || f.type === 'list'));
</script>

<template>
    <PageHeader title="Settings" description="Everything on the public site that is not a page or record lives here.">
        <span v-if="saved" class="inline-flex items-center gap-1.5 text-sm text-green-700"><CircleCheck :size="16" />Saved</span>
        <button :class="button.primary" :disabled="form.processing" @click="save"><LoaderCircle v-if="form.processing" :size="16" class="animate-spin" />Save {{ group.label.toLowerCase() }}</button>
    </PageHeader>

    <div class="grid gap-6 lg:grid-cols-[13rem_minmax(0,1fr)]">
        <nav class="flex gap-1 overflow-x-auto lg:flex-col" aria-label="Settings sections">
            <button v-for="item in schema" :key="item.key" :class="['rounded-lg px-3 py-2 text-left text-sm whitespace-nowrap', tab === item.key ? 'bg-white font-medium shadow-sm ring-1 ring-[#e3e4e6]' : 'text-[#5c5f66] hover:bg-white/60']" @click="tab = item.key">
                {{ item.label }}
            </button>
        </nav>

        <form :class="[card, 'grid gap-6 p-5 sm:p-6']" @submit.prevent="save">
            <div v-if="hasTranslatable" class="flex items-center gap-3">
                <div class="inline-flex rounded-lg border border-[#d9dadc] bg-white p-0.5" role="tablist" aria-label="Language">
                    <button v-for="code in ['en', 'bn'] as const" :key="code" type="button" role="tab" :aria-selected="locale === code" :class="['rounded-md px-3.5 py-1.5 text-sm font-medium', locale === code ? 'bg-brand-600 text-ink-950' : 'text-[#5c5f66]']" @click="locale = code">{{ code === 'en' ? 'English' : 'বাংলা' }}</button>
                </div>
                <p class="text-xs text-[#5c5f66]">Empty Bangla text falls back to English.</p>
            </div>

            <div v-for="field in group.fields" :key="field.key" class="grid gap-1.5">
                <!-- toggle -->
                <div v-if="field.type === 'toggle'" class="flex items-start justify-between gap-6">
                    <div><p class="text-sm font-medium">{{ field.label }}</p><p v-if="field.help" class="text-xs text-[#5c5f66]">{{ field.help }}</p></div>
                    <button type="button" role="switch" :aria-checked="!!v[field.key]" :aria-label="field.label" :class="['relative h-6 w-11 shrink-0 rounded-full transition', v[field.key] ? 'bg-brand-600' : 'bg-[#d9dadc]']" @click="v[field.key] = !v[field.key]">
                        <span :class="['absolute top-0.5 left-0.5 size-5 rounded-full bg-white shadow transition', v[field.key] && 'translate-x-5']" />
                    </button>
                </div>

                <template v-else>
                    <label :for="`s-${field.key}`" class="text-sm font-medium">
                        {{ field.label }}
                        <span v-if="field.translatable" class="ml-1 rounded bg-[#fff2c9] px-1.5 py-0.5 text-[10px] font-semibold text-brand-900 uppercase">{{ locale }}</span>
                        <span v-if="field.type === 'secret' && secrets[field.key]" class="ml-2 text-xs font-normal text-green-700">Saved. Leave empty to keep it.</span>
                    </label>

                    <template v-if="field.translatable">
                        <textarea v-if="field.type === 'textarea'" :id="`s-${field.key}`" v-model="v[field.key][locale]" rows="3" :class="input" />
                        <input v-else :id="`s-${field.key}`" v-model="v[field.key][locale]" :class="input" />
                    </template>

                    <textarea v-else-if="field.type === 'textarea'" :id="`s-${field.key}`" v-model="v[field.key]" rows="3" :class="input" />

                    <input v-else-if="field.type === 'secret'" :id="`s-${field.key}`" v-model="v[field.key]" type="password" autocomplete="new-password" :class="input" :placeholder="secrets[field.key] ? '••••••••••••' : ''" />

                    <div v-else-if="field.type === 'days'" class="flex flex-wrap gap-2">
                        <label v-for="day in days" :key="day" :class="['cursor-pointer rounded-full px-3 py-1.5 text-sm ring-1', (v[field.key] as string[]).includes(day) ? 'bg-brand-600 text-ink-950 ring-brand-600' : 'ring-[#d9dadc]']">
                            <input type="checkbox" class="sr-only" :checked="(v[field.key] as string[]).includes(day)" @change="toggleDay(field.key, day)" />{{ day.slice(0, 3) }}
                        </label>
                    </div>

                    <ol v-else-if="field.type === 'sections'" class="grid gap-1.5">
                        <li v-for="(section, index) in list(field.key)" :key="section.key" class="flex items-center gap-3 rounded-lg border border-[#e3e4e6] px-3 py-2">
                            <input :id="`sec-${section.key}`" v-model="section.enabled" type="checkbox" class="size-4 accent-brand-600" :disabled="section.key === 'hero'" />
                            <label :for="`sec-${section.key}`" :class="['flex-1 text-sm', !section.enabled && 'text-[#85888f] line-through']">{{ sectionNames[section.key] ?? section.key }}</label>
                            <button type="button" :class="button.ghost" :disabled="index === 0" aria-label="Move up" @click="move(v[field.key], index, -1)"><ArrowUp :size="14" /></button>
                            <button type="button" :class="button.ghost" :disabled="index === list(field.key).length - 1" aria-label="Move down" @click="move(v[field.key], index, 1)"><ArrowDown :size="14" /></button>
                        </li>
                    </ol>

                    <div v-else-if="field.type === 'copy'" class="grid gap-2 rounded-lg bg-[#f7f7f8] p-3 ring-1 ring-[#e3e4e6]">
                        <input v-model="v[field.key].title[locale]" :class="input" :placeholder="`Heading (${locale})`" :aria-label="`${field.label} heading`" />
                        <textarea v-model="v[field.key].lead[locale]" rows="2" :class="input" :placeholder="`Intro line, optional (${locale})`" :aria-label="`${field.label} intro`" />
                    </div>

                    <div v-else-if="field.type === 'list'" class="grid gap-2">
                        <div v-for="(row, index) in list(field.key)" :key="index" class="grid gap-2 rounded-lg bg-[#f7f7f8] p-3 ring-1 ring-[#e3e4e6]">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-[#5c5f66]">#{{ index + 1 }}</span>
                                <select v-if="field.item?.icon" v-model="row.icon" :class="inputInline" aria-label="Icon">
                                    <option v-for="icon in icons" :key="icon" :value="icon">{{ icon }}</option>
                                </select>
                                <span v-if="field.item?.icon" class="grid size-9 place-items-center rounded-lg bg-[#fff2c9] text-link"><Icon :name="row.icon" :size="18" /></span>
                                <span class="ml-auto flex">
                                    <button type="button" :class="button.ghost" :disabled="index === 0" aria-label="Move up" @click="move(v[field.key], index, -1)"><ArrowUp :size="14" /></button>
                                    <button type="button" :class="button.ghost" :disabled="index === list(field.key).length - 1" aria-label="Move down" @click="move(v[field.key], index, 1)"><ArrowDown :size="14" /></button>
                                    <button type="button" :class="[button.ghost, 'hover:!text-red-600']" aria-label="Remove" @click="v[field.key].splice(index, 1)"><Trash2 :size="14" /></button>
                                </span>
                            </div>
                            <template v-for="(type, name) in field.item" :key="name">
                                <textarea v-if="type === 'textarea'" v-model="row[name][locale]" rows="2" :class="input" :placeholder="`${name} (${locale})`" :aria-label="String(name)" />
                                <input v-else-if="type === 'text'" v-model="row[name][locale]" :class="input" :placeholder="`${name} (${locale})`" :aria-label="String(name)" />
                            </template>
                        </div>
                        <button type="button" :class="[button.secondary, 'justify-self-start']" @click="addRow(field)"><Plus :size="16" />Add</button>
                    </div>

                    <input v-else :id="`s-${field.key}`" v-model="v[field.key]" :type="{ url: 'url', email: 'email', number: 'number' }[field.type] ?? 'text'" :class="input" />

                    <p v-if="field.help" class="text-xs text-[#5c5f66]">{{ field.help }}</p>
                    <p v-if="(form.errors as Record<string, string>)[`values.${field.key}`]" class="text-xs text-red-600">{{ (form.errors as Record<string, string>)[`values.${field.key}`] }}</p>
                </template>
            </div>
        </form>
    </div>
</template>
