<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ExternalLink, Mail, MessageCircle, Phone, Trash2 } from '@lucide/vue';
import ConfirmButton from '@/components/admin/ConfirmButton.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import StatusBadge from '@/components/admin/StatusBadge.vue';
import { button, card, input } from '@/components/admin/ui';
import { formatDate, label, timeAgo } from '@/composables/useToast';

type Lead = {
    id: number;
    name: string | null;
    phone: string | null;
    email: string | null;
    company: string | null;
    message: string | null;
    budget_range: string | null;
    meta: Record<string, string> | null;
    source: string;
    status: string;
    assigned_to: number | null;
    locale: string;
    page_url: string | null;
    landing_url: string | null;
    referrer: string | null;
    utm: Record<string, string | null>;
    service: string | null;
    solution: string | null;
    project: string | null;
    whatsapp_url: string | null;
    created_at: string;
    contacted_at: string | null;
    closed_at: string | null;
    notes: { id: number; body: string; user: string | null; created_at: string }[];
};

const props = defineProps<{ lead: Lead; statuses: string[]; users: { id: number; name: string }[] }>();
const note = useForm({ body: '' });

function update(change: Record<string, string | null>): void {
    router.patch(`/admin/leads/${props.lead.id}`, change, { preserveScroll: true });
}
function addNote(): void {
    note.post(`/admin/leads/${props.lead.id}/notes`, { preserveScroll: true, onSuccess: () => note.reset() });
}
const utm = Object.entries(props.lead.utm).filter(([, value]) => value);
</script>

<template>
    <PageHeader :title="lead.name ?? 'Lead'" :description="`${label(lead.source)} · ${formatDate(lead.created_at)}`" back="/admin/leads">
        <a v-if="lead.whatsapp_url" :href="lead.whatsapp_url" target="_blank" rel="noopener" :class="[button.primary, '!bg-whatsapp !text-ink-950 hover:!brightness-95']"><MessageCircle :size="16" />WhatsApp</a>
        <a v-if="lead.phone" :href="`tel:${lead.phone}`" :class="button.secondary"><Phone :size="16" />Call</a>
        <a v-if="lead.email" :href="`mailto:${lead.email}`" :class="button.secondary"><Mail :size="16" />Email</a>
        <ConfirmButton title="Delete this lead?" description="It is removed from the list and exports." :trigger-class="`${button.secondary} !text-red-600`" @confirm="router.delete(`/admin/leads/${lead.id}`)"><Trash2 :size="16" /></ConfirmButton>
    </PageHeader>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="grid content-start gap-6">
            <section :class="[card, 'p-5']">
                <dl class="grid gap-4 text-sm sm:grid-cols-2">
                    <div><dt class="text-[#5c5f66]">Phone</dt><dd class="mt-0.5 font-medium">{{ lead.phone ?? '—' }}</dd></div>
                    <div><dt class="text-[#5c5f66]">Email</dt><dd class="mt-0.5 font-medium">{{ lead.email ?? '—' }}</dd></div>
                    <div v-if="lead.company"><dt class="text-[#5c5f66]">Company</dt><dd class="mt-0.5 font-medium">{{ lead.company }}</dd></div>
                    <div v-if="lead.service || lead.solution || lead.project"><dt class="text-[#5c5f66]">Interested in</dt><dd class="mt-0.5 font-medium">{{ [lead.service, lead.solution, lead.project].filter(Boolean).join(', ') }}</dd></div>
                    <div v-if="lead.budget_range"><dt class="text-[#5c5f66]">Budget</dt><dd class="mt-0.5 font-medium">{{ lead.budget_range }}</dd></div>
                    <div><dt class="text-[#5c5f66]">Language</dt><dd class="mt-0.5 font-medium">{{ lead.locale === 'bn' ? 'Bangla' : 'English' }}</dd></div>
                </dl>
                <div v-if="lead.message" class="mt-5 rounded-lg bg-[#f4f4f5] p-4 text-sm whitespace-pre-line">{{ lead.message }}</div>
                <dl v-if="lead.meta && Object.keys(lead.meta).length" class="mt-5 grid gap-2 text-sm">
                    <div v-for="(value, key) in lead.meta" :key="key" class="flex gap-3"><dt class="w-28 shrink-0 text-[#5c5f66]">{{ label(String(key)) }}</dt><dd>{{ value }}</dd></div>
                </dl>
            </section>

            <section :class="card">
                <h2 class="border-b border-[#e3e4e6] px-5 py-4 text-sm font-semibold">Notes</h2>
                <form class="flex gap-2 border-b border-[#e3e4e6] p-4" @submit.prevent="addNote">
                    <label class="sr-only" for="note">Add a note</label>
                    <textarea id="note" v-model="note.body" rows="2" :class="input" placeholder="What happened? e.g. Called, sending a quote tomorrow." />
                    <button :class="[button.primary, 'self-end']" :disabled="!note.body.trim() || note.processing">Add</button>
                </form>
                <ol>
                    <li v-for="item in lead.notes" :key="item.id" class="border-b border-[#ececed] px-5 py-3 text-sm last:border-0">
                        <p class="whitespace-pre-line">{{ item.body }}</p>
                        <p class="mt-1 text-xs text-[#85888f]">{{ item.user ?? 'Website' }} · {{ timeAgo(item.created_at) }}</p>
                    </li>
                    <li v-if="!lead.notes.length" class="px-5 py-6 text-center text-sm text-[#85888f]">No notes yet.</li>
                </ol>
            </section>
        </div>

        <aside class="grid content-start gap-6">
            <section :class="[card, 'grid gap-4 p-5']">
                <div>
                    <label class="text-sm font-medium" for="status">Status</label>
                    <select id="status" :value="lead.status" :class="[input, 'mt-1 capitalize']" @change="update({ status: ($event.target as HTMLSelectElement).value })">
                        <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium" for="assignee">Assigned to</label>
                    <select id="assignee" :value="lead.assigned_to ?? ''" :class="[input, 'mt-1']" @change="update({ assigned_to: ($event.target as HTMLSelectElement).value || null })">
                        <option value="">Nobody</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                    </select>
                </div>
                <dl class="grid gap-1 text-xs text-[#5c5f66]">
                    <div class="flex justify-between"><dt>Received</dt><dd>{{ formatDate(lead.created_at) }}</dd></div>
                    <div v-if="lead.contacted_at" class="flex justify-between"><dt>First contact</dt><dd>{{ formatDate(lead.contacted_at) }}</dd></div>
                    <div v-if="lead.closed_at" class="flex justify-between"><dt>Closed</dt><dd>{{ formatDate(lead.closed_at) }}</dd></div>
                    <div class="flex justify-between"><dt>Status</dt><dd><StatusBadge :value="lead.status" /></dd></div>
                </dl>
            </section>

            <section :class="[card, 'p-5 text-sm']">
                <h2 class="font-semibold">Where they came from</h2>
                <dl class="mt-3 grid gap-2">
                    <div v-for="[key, value] in utm" :key="key"><dt class="text-xs text-[#5c5f66]">{{ key }}</dt><dd class="break-all">{{ value }}</dd></div>
                    <div v-if="lead.referrer"><dt class="text-xs text-[#5c5f66]">Referrer</dt><dd class="break-all">{{ lead.referrer }}</dd></div>
                    <div v-if="lead.landing_url"><dt class="text-xs text-[#5c5f66]">First page</dt><dd><a :href="lead.landing_url" target="_blank" class="inline-flex items-center gap-1 break-all text-link hover:underline">{{ lead.landing_url }} <ExternalLink :size="12" /></a></dd></div>
                    <div v-if="lead.page_url"><dt class="text-xs text-[#5c5f66]">Form page</dt><dd><a :href="lead.page_url" target="_blank" class="inline-flex items-center gap-1 break-all text-link hover:underline">{{ lead.page_url }} <ExternalLink :size="12" /></a></dd></div>
                    <p v-if="!utm.length && !lead.referrer && !lead.landing_url" class="text-[#85888f]">Direct visit; no campaign data.</p>
                </dl>
            </section>
        </aside>
    </div>
</template>
