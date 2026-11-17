<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ExternalLink, ImagePlus, LoaderCircle, Trash2, X } from '@lucide/vue';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import ConfirmButton from '@/components/admin/ConfirmButton.vue';
import FieldInput from '@/components/admin/FieldInput.vue';
import ImageField from '@/components/admin/ImageField.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import RepeaterField from '@/components/admin/RepeaterField.vue';
import SeoPanel, { type SeoValues } from '@/components/admin/SeoPanel.vue';
import { button, card } from '@/components/admin/ui';
import type { AdminField, Image } from '@/types/models';

/**
 * Create / edit screen for every content module. The backend sends the
 * field list; this page renders it, validates on the server and shows errors.
 */
type Module = { key: string; label: string; singular: string; hasSeo: boolean; softDeletes: boolean; can: { create: boolean; update: boolean; delete: boolean } };
type RecordValues = Record<string, unknown> & { id: number; public_url?: string; seo?: SeoValues };

const props = defineProps<{ module: Module; fields: AdminField[]; record: RecordValues | null; defaults: Record<string, unknown> }>();

const locale = ref<'en' | 'bn'>('en');
const editing = computed(() => !!props.record);
const readOnly = computed(() => (editing.value ? !props.module.can.update : !props.module.can.create));
const main = computed(() => props.fields.filter((f) => f.group === 'main'));
const side = computed(() => props.fields.filter((f) => f.group === 'side'));
const hasTranslatable = computed(() => props.fields.some((f) => f.translatable || f.children.some((c) => c.translatable)));

/** Initial form values from the record or the field defaults. */
function initial(): Record<string, unknown> {
    const values: Record<string, unknown> = {};

    for (const field of props.fields) {
        const current = props.record?.[field.name];

        if (field.type === 'image') {
            values[field.name] = null;
            values[`${field.name}_remove`] = false;
            const image = current as (Image & { alts?: Record<string, string> }) | null;
            values[`${field.name}_alt`] = { en: '', bn: '', ...(image?.alts ?? {}) };
        } else if (field.type === 'images') {
            values[field.name] = [];
            values[`${field.name}_remove`] = [];
        } else if (field.translatable) {
            values[field.name] = { en: '', bn: '', ...((current as Record<string, string>) ?? {}) };
        } else if (field.type === 'multiselect' || field.type === 'repeater') {
            values[field.name] = (current as unknown[]) ?? [];
        } else if (field.type === 'toggle') {
            values[field.name] = (current ?? props.defaults[field.name] ?? false) as boolean;
        } else {
            values[field.name] = current ?? props.defaults[field.name] ?? (field.type === 'select' ? null : '');
        }
    }

    if (props.module.hasSeo) {
        values.seo = props.record?.seo ?? { title: { en: '', bn: '' }, description: { en: '', bn: '' }, canonical: null, robots: null };
    }

    return values;
}

// Field names come from the server, so the form is keyed dynamically.
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const form = useForm(initial() as Record<string, any>);
const errors = computed(() => form.errors as Record<string, string>);

/** Empty lists must still be sent so that clearing them works (FormData drops empty arrays). */
function payload(data: Record<string, unknown>): Record<string, unknown> {
    const out: Record<string, unknown> = {};

    for (const [key, value] of Object.entries(data)) {
        const field = props.fields.find((f) => f.name === key);

        if (field?.type === 'image' && !value) continue;
        if (Array.isArray(value) && value.length === 0) {
            if (field?.type === 'images' || key.endsWith('_remove')) continue;
            out[key] = '';
            continue;
        }
        if (field?.type === 'password' && !value) continue;
        out[key] = typeof value === 'boolean' ? (value ? 1 : 0) : value;
    }

    return out;
}

function submit(): void {
    const url = editing.value ? `/admin/${props.module.key}/${props.record!.id}` : `/admin/${props.module.key}`;

    form.transform(payload).post(url, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            if (editing.value) {
                // Fresh values (new image URLs, generated slug) from the server.
                form.defaults(initial());
                form.reset();
            }
        },
        onError: (errs) => {
            const first = Object.keys(errs)[0] ?? '';
            if (first.endsWith('.bn')) locale.value = 'bn';
            else if (first.endsWith('.en')) locale.value = 'en';
        },
    });
}

function destroy(): void {
    router.delete(`/admin/${props.module.key}/${props.record!.id}`);
}

function addImages(name: string, event: Event): void {
    const files = Array.from((event.target as HTMLInputElement).files ?? []);
    form[name] = [...((form[name] as File[]) ?? []), ...files];
}

// Ctrl/Cmd + S saves; leaving with unsaved changes asks first.
function onKey(event: KeyboardEvent): void {
    if ((event.metaKey || event.ctrlKey) && event.key === 's') {
        event.preventDefault();
        if (!readOnly.value) submit();
    }
}
function onLeave(event: BeforeUnloadEvent): void {
    if (form.isDirty) event.preventDefault();
}
let stopGuard: (() => void) | undefined;
onMounted(() => {
    window.addEventListener('keydown', onKey);
    window.addEventListener('beforeunload', onLeave);
    stopGuard = router.on('before', (event) => {
        const visit = event.detail.visit;
        if (form.isDirty && !form.processing && visit.method === 'get' && !confirm('You have unsaved changes. Leave anyway?')) return false;
    });
});
onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKey);
    window.removeEventListener('beforeunload', onLeave);
    stopGuard?.();
});

const titleValue = computed(() => {
    const first = props.fields[0];
    const value = first ? form[first.name] : '';

    return typeof value === 'object' && value ? ((value as Record<string, string>).en ?? '') : String(value ?? '');
});
</script>

<template>
    <PageHeader :title="editing ? titleValue || module.singular : `New ${module.singular.toLowerCase()}`" :back="`/admin/${module.key}`">
        <a v-if="record?.public_url" :href="record.public_url" target="_blank" :class="button.secondary">View on site <ExternalLink :size="14" /></a>
        <ConfirmButton
            v-if="editing && module.can.delete"
            :title="`Delete this ${module.singular.toLowerCase()}?`"
            :description="module.softDeletes ? 'It disappears from the site. You can restore it from the list.' : 'This cannot be undone.'"
            :trigger-class="`${button.secondary} !text-red-600`"
            @confirm="destroy"
        >
            <Trash2 :size="16" />Delete
        </ConfirmButton>
        <button v-if="!readOnly" :class="button.primary" :disabled="form.processing" @click="submit">
            <LoaderCircle v-if="form.processing" :size="16" class="animate-spin" />
            {{ editing ? 'Save changes' : `Create ${module.singular.toLowerCase()}` }}
        </button>
    </PageHeader>

    <div v-if="hasTranslatable" class="mb-5 flex items-center gap-3">
        <div class="inline-flex rounded-lg border border-[#d5dbe7] bg-white p-0.5" role="tablist" aria-label="Language">
            <button v-for="code in ['en', 'bn'] as const" :key="code" role="tab" :aria-selected="locale === code" :class="['rounded-md px-3.5 py-1.5 text-sm font-medium transition', locale === code ? 'bg-brand-600 text-white' : 'text-[#55607a] hover:text-[#0a0f24]']" @click="locale = code">
                {{ code === 'en' ? 'English' : 'বাংলা' }}
            </button>
        </div>
        <p class="text-xs text-[#55607a]">{{ locale === 'bn' ? 'Empty Bangla fields show the English text on the Bangla site.' : 'English is required; Bangla is optional.' }}</p>
    </div>

    <p v-if="Object.keys(errors).length" class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
        Please fix the highlighted fields{{ Object.keys(errors).some((k) => k.endsWith('.bn')) ? ' (some are in the Bangla tab)' : '' }}.
    </p>

    <form class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]" @submit.prevent="submit">
        <fieldset :disabled="readOnly" class="grid content-start gap-6">
            <section :class="[card, 'grid gap-5 p-5 sm:p-6']">
                <template v-for="field in main" :key="field.name">
                    <RepeaterField v-if="field.type === 'repeater'" :id="`f-${field.name}`" v-model="form[field.name] as Record<string, unknown>[]" :field="field" :locale="locale" :errors="errors" />
                    <div v-else-if="field.type === 'images'">
                        <p class="mb-1.5 text-sm font-medium">{{ field.label }}</p>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                            <div v-for="image in (record?.[field.name] as Image[]) ?? []" :key="image.id" :class="['group relative overflow-hidden rounded-lg border', (form[`${field.name}_remove`] as number[]).includes(image.id) ? 'opacity-30' : '']">
                                <img :src="image.thumb" :alt="image.alt" class="aspect-[4/3] w-full object-cover" />
                                <button type="button" class="absolute top-1 right-1 rounded-md bg-white p-1 text-red-600 shadow" aria-label="Remove image" @click="form[`${field.name}_remove`] = [...(form[`${field.name}_remove`] as number[]), image.id]"><X :size="14" /></button>
                            </div>
                            <div v-for="(file, index) in form[field.name] as File[]" :key="`new-${index}`" class="grid aspect-[4/3] place-items-center rounded-lg border border-dashed border-brand-400 bg-brand-50 p-2 text-center text-xs text-brand-700">{{ file.name }}</div>
                            <label class="grid aspect-[4/3] cursor-pointer place-items-center rounded-lg border border-dashed border-[#c5ccda] text-sm text-[#55607a] hover:bg-[#f6f7fb]">
                                <span class="text-center"><ImagePlus :size="20" class="mx-auto" />Add</span>
                                <input type="file" multiple accept="image/jpeg,image/png,image/webp" class="sr-only" @change="addImages(field.name, $event)" />
                            </label>
                        </div>
                        <p v-if="errors[field.name] || errors[`${field.name}.0`]" class="mt-1 text-xs text-red-600">{{ errors[field.name] ?? errors[`${field.name}.0`] }}</p>
                    </div>
                    <FieldInput
                        v-else
                        :id="`f-${field.name}`"
                        v-model="form[field.name]"
                        :field="field"
                        :locale="locale"
                        :error="errors[field.translatable ? `${field.name}.${locale}` : field.name] ?? errors[field.name]"
                    />
                </template>
            </section>
        </fieldset>

        <aside class="grid content-start gap-6">
            <fieldset v-if="side.length" :disabled="readOnly" :class="[card, 'grid gap-5 p-5']">
                <template v-for="field in side" :key="field.name">
                    <div v-if="field.type === 'image'">
                        <p class="mb-1.5 text-sm font-medium">{{ field.label }}</p>
                        <ImageField
                            v-model:file="form[field.name] as File | null"
                            v-model:remove="form[`${field.name}_remove`] as boolean"
                            v-model:alt="form[`${field.name}_alt`] as Record<string, string>"
                            :current="(record?.[field.name] as Image | null) ?? null"
                            :locale="locale"
                            :error="errors[field.name]"
                        />
                    </div>
                    <FieldInput v-else :id="`f-${field.name}`" v-model="form[field.name]" :field="field" :locale="locale" :error="errors[field.name]" />
                </template>
            </fieldset>

            <SeoPanel
                v-if="module.hasSeo"
                v-model="form.seo as SeoValues"
                :locale="locale"
                :fallback-title="titleValue"
                :public-url="record?.public_url"
                :errors="errors"
            />
        </aside>
    </form>
</template>
