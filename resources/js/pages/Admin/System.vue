<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Database, Eraser, Mail, RotateCcw, Trash2 } from '@lucide/vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import { button, card } from '@/components/admin/ui';
import { timeAgo } from '@/composables/useToast';

/** Maintenance tools that replace shell access on shared hosting. */
defineProps<{
    version: string;
    php: string;
    laravel: string;
    database: string;
    pendingMigrations: number;
    mailConfigured: boolean;
    realtime: boolean;
    failedTasks: { id: number; task: string; error: string | null; attempts: number; created_at: string }[];
}>();

const post = (url: string) => router.post(url, {}, { preserveScroll: true });
</script>

<template>
    <PageHeader title="System" description="Updates, caches, email check and failed background tasks." />

    <div class="grid gap-6 lg:grid-cols-3">
        <section :class="[card, 'p-5 lg:col-span-2']">
            <h2 class="text-sm font-semibold">After uploading a new version</h2>
            <p class="mt-1 text-sm text-[#55607a]">Extract the new zip over the old files, then apply database changes here. Your settings, uploads and leads are kept.</p>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button :class="pendingMigrations ? button.primary : button.secondary" @click="post('/admin/system/migrate')"><Database :size="16" />Update database</button>
                <span :class="['text-sm', pendingMigrations ? 'font-medium text-amber-700' : 'text-[#55607a]']">{{ pendingMigrations ? `${pendingMigrations} change${pendingMigrations > 1 ? 's' : ''} waiting` : 'Database is up to date' }}</span>
            </div>
            <div class="mt-6 flex flex-wrap gap-3 border-t border-[#e4e8f0] pt-5">
                <button :class="button.secondary" @click="post('/admin/system/clear-cache')"><Eraser :size="16" />Clear caches</button>
                <button :class="button.secondary" @click="post('/admin/system/test-mail')"><Mail :size="16" />Send a test email</button>
            </div>
        </section>

        <section :class="[card, 'p-5 text-sm']">
            <h2 class="font-semibold">About this installation</h2>
            <dl class="mt-3 grid gap-2">
                <div class="flex justify-between"><dt class="text-[#55607a]">Version</dt><dd>{{ version }}</dd></div>
                <div class="flex justify-between"><dt class="text-[#55607a]">PHP</dt><dd>{{ php }}</dd></div>
                <div class="flex justify-between"><dt class="text-[#55607a]">Laravel</dt><dd>{{ laravel }}</dd></div>
                <div class="flex justify-between"><dt class="text-[#55607a]">Database</dt><dd>{{ database }}</dd></div>
                <div class="flex justify-between"><dt class="text-[#55607a]">Lead emails</dt><dd :class="mailConfigured ? 'text-green-700' : 'text-amber-700'">{{ mailConfigured ? 'On' : 'Off (no app password)' }}</dd></div>
                <div class="flex justify-between"><dt class="text-[#55607a]">Live updates</dt><dd :class="realtime ? 'text-green-700' : 'text-amber-700'">{{ realtime ? 'On (Pusher)' : 'Off' }}</dd></div>
            </dl>
        </section>
    </div>

    <section :class="[card, 'mt-6']">
        <h2 class="border-b border-[#e4e8f0] px-5 py-4 text-sm font-semibold">Failed background tasks</h2>
        <ul>
            <li v-for="task in failedTasks" :key="task.id" class="flex flex-wrap items-start gap-3 border-b border-[#f0f2f7] px-5 py-3 text-sm last:border-0">
                <div class="min-w-0 flex-1">
                    <p class="font-medium">{{ task.task }} <span class="font-normal text-[#9aa6c1]">· {{ timeAgo(task.created_at) }} · {{ task.attempts }} attempt{{ task.attempts > 1 ? 's' : '' }}</span></p>
                    <p class="mt-0.5 line-clamp-2 text-xs text-red-700">{{ task.error }}</p>
                </div>
                <button :class="button.secondary" @click="post(`/admin/system/tasks/${task.id}/retry`)"><RotateCcw :size="15" />Retry</button>
                <button :class="button.ghost" aria-label="Remove" @click="router.delete(`/admin/system/tasks/${task.id}`, { preserveScroll: true })"><Trash2 :size="15" /></button>
            </li>
            <li v-if="!failedTasks.length" class="px-5 py-10 text-center text-sm text-[#55607a]">Nothing has failed.</li>
        </ul>
    </section>
</template>
