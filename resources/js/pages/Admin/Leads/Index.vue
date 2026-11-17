<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Download, KanbanSquare, List, MessageCircle, Search } from '@lucide/vue';
import { useDebounceFn } from '@vueuse/core';
import { computed, onMounted, ref, watch } from 'vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import Pagination from '@/components/admin/Pagination.vue';
import StatusBadge from '@/components/admin/StatusBadge.vue';
import { button, card, input, inputInline } from '@/components/admin/ui';
import { label, timeAgo } from '@/composables/useToast';

type Lead = { id: number; name: string | null; phone: string | null; source: string; status: string; assignee: string | null; utm_source: string | null; created_at: string };

const props = defineProps<{
    leads: { data: Lead[]; links: { url: string | null; label: string; active: boolean }[]; total: number };
    query: Record<string, string>;
    statuses: string[];
    sources: string[];
    counts: Record<string, number>;
    users: { id: number; name: string }[];
}>();

const view = ref<'table' | 'board'>('table');
onMounted(() => {
    try {
        view.value = (localStorage.getItem('aux-leads-view') as 'table' | 'board') ?? 'table';
    } catch {
        // Storage may be blocked.
    }
});
watch(view, (value) => {
    try {
        localStorage.setItem('aux-leads-view', value);
    } catch {
        // Storage may be blocked.
    }
});

const search = ref(props.query.search ?? '');
function visit(change: Record<string, string | null>): void {
    const query = Object.fromEntries(Object.entries({ ...props.query, ...change }).filter(([, v]) => v));
    router.get('/admin/leads', query, { preserveState: true, preserveScroll: true, replace: true });
}
const onSearch = useDebounceFn(() => visit({ search: search.value || null }), 300);

const board = computed(() => props.statuses.filter((s) => s !== 'spam').map((status) => ({ status, leads: props.leads.data.filter((l) => l.status === status) })));
const dragged = ref<Lead | null>(null);

function moveTo(status: string): void {
    if (!dragged.value || dragged.value.status === status) return;
    router.patch(`/admin/leads/${dragged.value.id}`, { status }, { preserveScroll: true, preserveState: true });
    dragged.value = null;
}

const exportUrl = computed(() => `/admin/leads/export?${new URLSearchParams(props.query).toString()}`);
const wa = (phone: string | null) => (phone ? `https://wa.me/${phone.replace(/\D/g, '')}` : null);
</script>

<template>
    <PageHeader title="Leads" :description="`${leads.total} total`">
        <div class="inline-flex rounded-lg border border-[#d5dbe7] bg-white p-0.5">
            <button :class="['inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm', view === 'table' ? 'bg-brand-600 text-white' : 'text-[#55607a]']" @click="view = 'table'"><List :size="15" />Table</button>
            <button :class="['inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm', view === 'board' ? 'bg-brand-600 text-white' : 'text-[#55607a]']" @click="view = 'board'"><KanbanSquare :size="15" />Board</button>
        </div>
        <a :href="exportUrl" :class="button.secondary"><Download :size="16" />Export CSV</a>
    </PageHeader>

    <div class="mb-4 flex flex-wrap gap-2">
        <button :class="['rounded-full px-3 py-1.5 text-sm', !query.status ? 'bg-[#0a0f24] text-white' : 'bg-white text-[#55607a] ring-1 ring-[#e4e8f0]']" @click="visit({ status: null })">All</button>
        <button v-for="status in statuses" :key="status" :class="['rounded-full px-3 py-1.5 text-sm capitalize', query.status === status ? 'bg-[#0a0f24] text-white' : 'bg-white text-[#55607a] ring-1 ring-[#e4e8f0]']" @click="visit({ status })">
            {{ status }} <span class="opacity-60">{{ counts[status] ?? 0 }}</span>
        </button>
    </div>

    <div :class="[card, 'mb-4 flex flex-wrap gap-2 p-3']">
        <label class="relative min-w-56 flex-1">
            <span class="sr-only">Search</span>
            <Search :size="16" class="absolute top-1/2 left-3 -translate-y-1/2 text-[#9aa6c1]" />
            <input v-model="search" type="search" :class="[input, 'pl-9']" placeholder="Name, phone, email or company" @input="onSearch" />
        </label>
        <select :value="query.source ?? ''" :class="inputInline" aria-label="Source" @change="visit({ source: ($event.target as HTMLSelectElement).value || null })">
            <option value="">All sources</option>
            <option v-for="source in sources" :key="source" :value="source">{{ label(source) }}</option>
        </select>
        <select :value="query.assigned_to ?? ''" :class="inputInline" aria-label="Assigned to" @change="visit({ assigned_to: ($event.target as HTMLSelectElement).value || null })">
            <option value="">Anyone</option>
            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
        </select>
    </div>

    <div v-if="view === 'table'" :class="[card, 'overflow-x-auto']">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-[#e4e8f0] text-xs tracking-wide text-[#55607a] uppercase">
                <tr><th class="px-4 py-3 font-medium">Lead</th><th class="px-4 py-3 font-medium">Source</th><th class="px-4 py-3 font-medium">Status</th><th class="px-4 py-3 font-medium">Assigned</th><th class="px-4 py-3 font-medium">Received</th><th /></tr>
            </thead>
            <tbody>
                <tr v-for="lead in leads.data" :key="lead.id" class="border-b border-[#f0f2f7] last:border-0 hover:bg-[#fafbfd]">
                    <td class="px-4 py-3">
                        <Link :href="`/admin/leads/${lead.id}`" class="font-medium hover:text-brand-600">{{ lead.name ?? 'No name' }}</Link>
                        <p class="text-xs text-[#55607a]">{{ lead.phone }}</p>
                    </td>
                    <td class="px-4 py-3 text-[#55607a]">{{ label(lead.source) }}<span v-if="lead.utm_source" class="block text-xs">{{ lead.utm_source }}</span></td>
                    <td class="px-4 py-3"><StatusBadge :value="lead.status" /></td>
                    <td class="px-4 py-3 text-[#55607a]">{{ lead.assignee ?? '—' }}</td>
                    <td class="px-4 py-3 text-[#55607a]">{{ timeAgo(lead.created_at) }}</td>
                    <td class="px-4 py-3 text-right"><a v-if="wa(lead.phone)" :href="wa(lead.phone)!" target="_blank" rel="noopener" :class="button.ghost" aria-label="Open WhatsApp chat"><MessageCircle :size="16" class="text-whatsapp-dark" /></a></td>
                </tr>
                <tr v-if="!leads.data.length"><td colspan="6" class="px-4 py-16 text-center text-[#55607a]">No leads match.</td></tr>
            </tbody>
        </table>
        <nav v-if="leads.links.length > 3" class="flex flex-wrap justify-end gap-1 border-t border-[#e4e8f0] p-3 text-sm">
            <Pagination :links="leads.links" />
        </nav>
    </div>

    <div v-else class="flex gap-4 overflow-x-auto pb-4">
        <section v-for="column in board" :key="column.status" class="w-72 shrink-0 rounded-xl bg-[#eef1f6] p-2" @dragover.prevent @drop="moveTo(column.status)">
            <h2 class="flex items-center justify-between px-2 py-2 text-sm font-semibold capitalize">{{ column.status }}<span class="text-[#55607a]">{{ column.leads.length }}</span></h2>
            <ul class="grid min-h-24 gap-2">
                <li v-for="lead in column.leads" :key="lead.id" draggable="true" class="cursor-grab rounded-lg border border-[#e4e8f0] bg-white p-3 shadow-sm active:cursor-grabbing" @dragstart="dragged = lead">
                    <Link :href="`/admin/leads/${lead.id}`" class="font-medium hover:text-brand-600">{{ lead.name ?? 'No name' }}</Link>
                    <p class="text-xs text-[#55607a]">{{ lead.phone }}</p>
                    <p class="mt-2 flex justify-between text-xs text-[#9aa6c1]"><span>{{ label(lead.source) }}</span><span>{{ timeAgo(lead.created_at) }}</span></p>
                </li>
            </ul>
        </section>
    </div>
    <p v-if="view === 'board'" class="text-xs text-[#55607a]">Drag a card to another column to change its status. The board shows the current page of results.</p>
</template>
