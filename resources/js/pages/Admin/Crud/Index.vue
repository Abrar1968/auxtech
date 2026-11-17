<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ArchiveRestore, GripVertical, Pencil, Plus, Search, Trash2 } from '@lucide/vue';
import { useDebounceFn } from '@vueuse/core';
import { computed, ref, watch } from 'vue';
import ConfirmButton from '@/components/admin/ConfirmButton.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import Pagination from '@/components/admin/Pagination.vue';
import StatusBadge from '@/components/admin/StatusBadge.vue';
import { button, card, input, inputInline } from '@/components/admin/ui';

/** List screen for every content module: search, filters, sorting by drag, trash. */
type Module = { key: string; label: string; singular: string; sortable: boolean; softDeletes: boolean; can: { create: boolean; update: boolean; delete: boolean } };
type Row = Record<string, unknown> & { id: number; trashed: boolean };

const props = defineProps<{
    module: Module;
    columns: { name: string; label: string }[];
    filters: Record<string, Record<string, string>>;
    query: Record<string, string>;
    records: { data: Row[]; links: { url: string | null; label: string; active: boolean }[]; total: number; from: number | null; to: number | null };
}>();

const search = ref(props.query.search ?? '');
const rows = ref<Row[]>([...props.records.data]);
watch(() => props.records.data, (value) => (rows.value = [...value]));

function visit(change: Record<string, string | null>): void {
    const query = Object.fromEntries(Object.entries({ ...props.query, ...change }).filter(([, v]) => v));
    router.get(`/admin/${props.module.key}`, query, { preserveState: true, preserveScroll: true, replace: true });
}
const onSearch = useDebounceFn(() => visit({ search: search.value || null }), 300);

const trashed = computed(() => props.query.trashed === '1');
const canReorder = computed(() => props.module.sortable && props.module.can.update && !props.query.search && !trashed.value && !Object.keys(props.filters).some((f) => props.query[f]));

// Drag-and-drop ordering
const dragging = ref<number | null>(null);
function onDrop(target: number): void {
    if (dragging.value === null || dragging.value === target) return;
    const list = [...rows.value];
    const [moved] = list.splice(dragging.value, 1);
    list.splice(target, 0, moved);
    rows.value = list;
    dragging.value = null;
    router.post(`/admin/${props.module.key}/reorder`, { ids: list.map((r) => r.id) }, { preserveScroll: true, preserveState: true });
}

function display(column: string, value: unknown): string {
    if (typeof value === 'boolean') return value ? 'Yes' : '—';
    if (value === null || value === undefined || value === '') return '—';

    return String(value);
}
const badgeColumns = ['status', 'type'];
</script>

<template>
    <PageHeader :title="module.label" :description="`${records.total} ${records.total === 1 ? module.singular.toLowerCase() : module.label.toLowerCase()}`">
        <Link v-if="module.can.create" :href="`/admin/${module.key}/create`" :class="button.primary"><Plus :size="16" />New {{ module.singular.toLowerCase() }}</Link>
    </PageHeader>

    <div :class="card">
        <div class="flex flex-wrap items-center gap-2 border-b border-[#e4e8f0] p-3">
            <label class="relative min-w-56 flex-1">
                <span class="sr-only">Search</span>
                <Search :size="16" class="absolute top-1/2 left-3 -translate-y-1/2 text-[#9aa6c1]" />
                <input v-model="search" type="search" :class="[input, 'pl-9']" placeholder="Search…" @input="onSearch" />
            </label>
            <select v-for="(options, name) in filters" :key="name" :value="query[name] ?? ''" :class="inputInline" :aria-label="String(name)" @change="visit({ [name]: ($event.target as HTMLSelectElement).value || null })">
                <option value="">All {{ String(name).replaceAll('_', ' ') }}</option>
                <option v-for="(text, value) in options" :key="value" :value="value">{{ text }}</option>
            </select>
            <button v-if="module.softDeletes" :class="[button.ghost, trashed && '!bg-[#f0f2f7] !text-[#0a0f24]']" @click="visit({ trashed: trashed ? null : '1' })">
                <Trash2 :size="15" />{{ trashed ? 'Showing deleted' : 'Deleted' }}
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-[#e4e8f0] text-xs tracking-wide text-[#55607a] uppercase">
                    <tr>
                        <th v-if="canReorder" class="w-8" />
                        <th v-for="column in columns" :key="column.name" class="px-4 py-3 font-medium">{{ column.label }}</th>
                        <th class="px-4 py-3" />
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, index) in rows"
                        :key="row.id"
                        :draggable="canReorder"
                        :class="['border-b border-[#f0f2f7] transition last:border-0 hover:bg-[#fafbfd]', dragging === index && 'opacity-40']"
                        @dragstart="dragging = index"
                        @dragover.prevent
                        @drop="onDrop(index)"
                        @dragend="dragging = null"
                    >
                        <td v-if="canReorder" class="cursor-grab pl-3 text-[#9aa6c1]" title="Drag to reorder"><GripVertical :size="16" /></td>
                        <td v-for="(column, cIndex) in columns" :key="column.name" class="max-w-xs px-4 py-3">
                            <StatusBadge v-if="badgeColumns.includes(column.name)" :value="row[column.name] as string" />
                            <Link v-else-if="cIndex === 0 && !row.trashed" :href="`/admin/${module.key}/${row.id}/edit`" class="line-clamp-1 font-medium text-[#0a0f24] hover:text-brand-600">
                                {{ display(column.name, row[column.name]) }}
                            </Link>
                            <span v-else class="line-clamp-1 text-[#55607a]">{{ display(column.name, row[column.name]) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <template v-if="row.trashed">
                                <button v-if="module.can.delete" :class="button.ghost" @click="router.post(`/admin/${module.key}/${row.id}/restore`, {}, { preserveScroll: true })"><ArchiveRestore :size="15" />Restore</button>
                            </template>
                            <template v-else>
                                <Link :href="`/admin/${module.key}/${row.id}/edit`" :class="button.ghost" :aria-label="`Edit ${display(columns[0]?.name ?? 'id', row[columns[0]?.name ?? 'id'])}`"><Pencil :size="15" /></Link>
                                <ConfirmButton
                                    v-if="module.can.delete"
                                    :title="`Delete this ${module.singular.toLowerCase()}?`"
                                    :description="module.softDeletes ? 'You can restore it later from Deleted.' : 'This cannot be undone.'"
                                    :trigger-class="`${button.ghost} hover:!text-red-600`"
                                    @confirm="router.delete(`/admin/${module.key}/${row.id}`, { preserveScroll: true })"
                                >
                                    <Trash2 :size="15" /><span class="sr-only">Delete</span>
                                </ConfirmButton>
                            </template>
                        </td>
                    </tr>
                    <tr v-if="!rows.length">
                        <td :colspan="columns.length + 2" class="px-4 py-16 text-center text-[#55607a]">
                            Nothing here yet.
                            <Link v-if="module.can.create && !trashed" :href="`/admin/${module.key}/create`" class="font-medium text-brand-600 hover:underline">Create the first one</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav v-if="records.links.length > 3" class="flex flex-wrap items-center justify-between gap-2 border-t border-[#e4e8f0] p-3 text-sm" aria-label="Pagination">
            <span class="text-[#55607a]">{{ records.from }}–{{ records.to }} of {{ records.total }}</span>
            <div class="flex flex-wrap gap-1">
                <Pagination :links="records.links" />
            </div>
        </nav>
    </div>
    <p v-if="canReorder && rows.length > 1" class="mt-3 text-xs text-[#55607a]">Drag rows to change the order on the site.</p>
</template>
