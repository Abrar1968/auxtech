<script setup lang="ts">
import PageHeader from '@/components/admin/PageHeader.vue';
import Pagination from '@/components/admin/Pagination.vue';
import { card } from '@/components/admin/ui';
import { formatDate } from '@/composables/useToast';

defineProps<{
    entries: {
        data: { id: number; description: string; event: string | null; subject: string | null; user: string | null; properties: { changed?: string[] }; created_at: string }[];
        links: { url: string | null; label: string; active: boolean }[];
    };
}>();
</script>

<template>
    <PageHeader title="Activity log" description="Who changed what, and when." />
    <div :class="card">
        <ul>
            <li v-for="entry in entries.data" :key="entry.id" class="flex flex-wrap gap-x-4 gap-y-1 border-b border-[#f0f2f7] px-5 py-3 text-sm last:border-0">
                <span class="w-40 shrink-0 text-[#55607a]">{{ formatDate(entry.created_at) }}</span>
                <span class="min-w-0 flex-1">
                    <strong class="font-medium">{{ entry.user ?? 'System' }}</strong> {{ entry.description.charAt(0).toLowerCase() + entry.description.slice(1) }}
                    <span v-if="entry.subject" class="text-[#9aa6c1]">({{ entry.subject }})</span>
                    <span v-if="entry.properties?.changed?.length" class="block text-xs text-[#55607a]">Changed: {{ entry.properties.changed.join(', ') }}</span>
                </span>
            </li>
            <li v-if="!entries.data.length" class="px-5 py-10 text-center text-sm text-[#55607a]">No activity yet.</li>
        </ul>
        <nav v-if="entries.links.length > 3" class="flex flex-wrap justify-end gap-1 border-t border-[#e4e8f0] p-3 text-sm">
            <Pagination :links="entries.links" />
        </nav>
    </div>
</template>
