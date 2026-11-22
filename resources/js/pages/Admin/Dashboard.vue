<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { ArrowDownRight, ArrowUpRight, Mail, MessageCircle, ShieldAlert, TriangleAlert, Trophy, Users } from '@lucide/vue';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import BarChart from '@/components/admin/BarChart.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import StatusBadge from '@/components/admin/StatusBadge.vue';
import { card } from '@/components/admin/ui';
import { label, timeAgo } from '@/composables/useToast';
import type { SharedProps } from '@/types/models';

type Kpi = { value: number; previous?: number };

const props = defineProps<{
    days: number;
    kpis: { leads: Kpi; leadsToday: Kpi; whatsappClicks: Kpi; emailClicks: Kpi; won: Kpi };
    leadsBySource: Record<string, number>;
    leadsByStatus: Record<string, number>;
    clicksByPlacement: Record<string, number>;
    leadsByDay: Record<string, number>;
    clicksByDay: Record<string, number>;
    topPages: Record<string, number>;
    recentLeads: { id: number; name: string | null; phone: string | null; source: string; status: string; created_at: string }[];
    content: { projects: number; publishedProjects: number; posts: number };
    health: { mailConfigured: boolean; realtime: boolean; failedTasks: number };
}>();

const page = usePage();
const auth = computed(() => (page.props as unknown as SharedProps).auth);
const conversion = computed(() => (props.kpis.whatsappClicks.value ? Math.round((props.kpis.leads.value / props.kpis.whatsappClicks.value) * 100) : null));

function change(kpi: Kpi): number | null {
    if (kpi.previous === undefined || kpi.previous === 0) return null;

    return Math.round(((kpi.value - kpi.previous) / kpi.previous) * 100);
}

const cards = computed(() => [
    { label: 'Leads', kpi: props.kpis.leads, icon: Users, href: '/admin/leads' },
    { label: 'WhatsApp clicks', kpi: props.kpis.whatsappClicks, icon: MessageCircle },
    { label: 'Email clicks', kpi: props.kpis.emailClicks, icon: Mail },
    { label: 'Won', kpi: props.kpis.won, icon: Trophy },
]);

const placementMax = computed(() => Math.max(1, ...Object.values(props.clicksByPlacement)));
const sourceTotal = computed(() => Math.max(1, Object.values(props.leadsBySource).reduce((a, b) => a + b, 0)));

// Live feed of clicks while the dashboard is open.
const feed = ref<{ placement: string | null; type: string; at: string }[]>([]);
function onClick(event: Event): void {
    const detail = (event as CustomEvent<{ placement: string | null; type: string }>).detail;
    feed.value = [{ ...detail, at: new Date().toISOString() }, ...feed.value].slice(0, 8);
}
onMounted(() => window.addEventListener('aux:click', onClick));
onBeforeUnmount(() => window.removeEventListener('aux:click', onClick));

const path = (url: string) => {
    try {
        return new URL(url).pathname;
    } catch {
        return url;
    }
};
</script>

<template>
    <PageHeader :title="`Hello, ${auth?.name?.split(' ')[0] ?? ''}`" :description="`Last ${days} days`">
        <div class="inline-flex rounded-lg border border-[#d9dadc] bg-white p-0.5">
            <button v-for="d in [7, 30, 90]" :key="d" :class="['rounded-md px-3 py-1.5 text-sm', d === days ? 'bg-brand-600 text-ink-950' : 'text-[#5c5f66]']" @click="router.get('/admin', { days: d }, { preserveState: true })">{{ d }}d</button>
        </div>
    </PageHeader>

    <div class="mb-6 grid gap-3">
        <Link v-if="!health.mailConfigured && auth?.roles.includes('Super Admin')" href="/admin/settings" class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 hover:bg-amber-100">
            <TriangleAlert :size="18" class="mt-0.5 shrink-0" />
            <span><strong>Lead emails are off.</strong> Leads are saved and shown here, but no email is sent until you add a Google App Password in Settings → Email.</span>
        </Link>
        <Link v-if="health.failedTasks" href="/admin/system" class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-900 hover:bg-red-100">
            <TriangleAlert :size="18" class="mt-0.5 shrink-0" />
            <span><strong>{{ health.failedTasks }} background task{{ health.failedTasks > 1 ? 's' : '' }} failed</strong> (for example an email). Open System to retry.</span>
        </Link>
        <Link v-if="auth && !auth.twoFactor" href="/admin/profile" class="flex items-start gap-3 rounded-xl border border-[#d9dadc] bg-white p-4 text-sm hover:bg-[#f7f7f8]">
            <ShieldAlert :size="18" class="mt-0.5 shrink-0 text-link" />
            <span>Protect your account with <strong>two-factor sign-in</strong>. It takes a minute.</span>
        </Link>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <component :is="item.href ? Link : 'div'" v-for="item in cards" :key="item.label" :href="item.href" :class="[card, 'p-5', item.href && 'transition hover:border-brand-300']">
            <div class="flex items-center justify-between text-sm text-[#5c5f66]">{{ item.label }}<component :is="item.icon" :size="18" /></div>
            <p class="mt-3 font-display text-3xl font-semibold">{{ item.kpi.value }}</p>
            <p v-if="change(item.kpi) !== null" :class="['mt-1 inline-flex items-center gap-0.5 text-xs font-medium', change(item.kpi)! >= 0 ? 'text-green-700' : 'text-red-600']">
                <ArrowUpRight v-if="change(item.kpi)! >= 0" :size="14" /><ArrowDownRight v-else :size="14" />{{ Math.abs(change(item.kpi)!) }}% vs previous {{ days }} days
            </p>
            <p v-else class="mt-1 text-xs text-[#85888f]">{{ item.label === 'Leads' ? `${kpis.leadsToday.value} today` : ' ' }}</p>
        </component>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section :class="[card, 'p-5 xl:col-span-2']">
            <BarChart
                title="Leads and WhatsApp clicks"
                :days="days"
                :series="[
                    { label: 'Leads', colour: '#2b2d31', values: leadsByDay },
                    { label: 'WhatsApp clicks', colour: '#25d366', values: clicksByDay },
                ]"
            />
            <p v-if="conversion !== null" class="mt-4 text-sm text-[#5c5f66]">About <strong class="text-[#232428]">{{ conversion }}%</strong> of WhatsApp clicks also left their details in a form.</p>
        </section>

        <section :class="[card, 'p-5']">
            <h2 class="text-sm font-semibold">Where WhatsApp clicks come from</h2>
            <ul class="mt-4 grid gap-3">
                <li v-for="(count, placement) in clicksByPlacement" :key="placement" class="text-sm">
                    <div class="flex justify-between"><span>{{ label(String(placement || 'unknown').replaceAll('-', ' ')) }}</span><span class="text-[#5c5f66]">{{ count }}</span></div>
                    <div class="mt-1 h-1.5 rounded-full bg-[#ececed]"><div class="h-full rounded-full bg-whatsapp" :style="{ width: `${(count / placementMax) * 100}%` }" /></div>
                </li>
                <li v-if="!Object.keys(clicksByPlacement).length" class="text-sm text-[#85888f]">No clicks yet.</li>
            </ul>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section :class="[card, 'xl:col-span-2']">
            <div class="flex items-center justify-between border-b border-[#e3e4e6] px-5 py-4">
                <h2 class="text-sm font-semibold">Recent leads</h2>
                <Link href="/admin/leads" class="text-sm font-medium text-link hover:underline">All leads</Link>
            </div>
            <ul>
                <li v-for="lead in recentLeads" :key="lead.id" class="border-b border-[#ececed] last:border-0">
                    <Link :href="`/admin/leads/${lead.id}`" class="flex items-center gap-3 px-5 py-3 text-sm hover:bg-[#f7f7f8]">
                        <span class="grid size-9 place-items-center rounded-full bg-[#fff2c9] font-semibold text-brand-900">{{ (lead.name ?? '?').charAt(0) }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-medium">{{ lead.name ?? 'No name' }}</span>
                            <span class="block text-xs text-[#5c5f66]">{{ lead.phone }} · {{ label(lead.source) }}</span>
                        </span>
                        <StatusBadge :value="lead.status" />
                        <span class="hidden w-24 text-right text-xs text-[#85888f] sm:block">{{ timeAgo(lead.created_at) }}</span>
                    </Link>
                </li>
                <li v-if="!recentLeads.length" class="px-5 py-10 text-center text-sm text-[#5c5f66]">No leads yet. They appear here the moment someone fills in a form.</li>
            </ul>
        </section>

        <div class="grid content-start gap-6">
            <section :class="[card, 'p-5']">
                <h2 class="text-sm font-semibold">Lead sources</h2>
                <ul class="mt-3 grid gap-2 text-sm">
                    <li v-for="(count, source) in leadsBySource" :key="source" class="flex items-center gap-3">
                        <span class="w-28 truncate">{{ label(String(source)) }}</span>
                        <span class="h-1.5 flex-1 rounded-full bg-[#ececed]"><span class="block h-full rounded-full bg-brand-600" :style="{ width: `${(count / sourceTotal) * 100}%` }" /></span>
                        <span class="w-6 text-right text-[#5c5f66]">{{ count }}</span>
                    </li>
                    <li v-if="!Object.keys(leadsBySource).length" class="text-[#85888f]">No data yet.</li>
                </ul>
            </section>
            <section :class="[card, 'p-5']">
                <h2 class="flex items-center gap-2 text-sm font-semibold"><span class="size-2 animate-pulse rounded-full bg-whatsapp" />Live clicks</h2>
                <ul class="mt-3 grid gap-2 text-sm">
                    <li v-for="(item, index) in feed" :key="index" class="flex justify-between"><span>{{ label(String(item.placement ?? '').replaceAll('-', ' ')) }} <span class="text-[#85888f]">({{ item.type === 'email_click' ? 'email' : 'WhatsApp' }})</span></span><span class="text-xs text-[#85888f]">{{ timeAgo(item.at) }}</span></li>
                    <li v-if="!feed.length" class="text-[#85888f]">{{ health.realtime ? 'Waiting for the next click…' : 'Live updates are off.' }}</li>
                </ul>
            </section>
            <section v-if="Object.keys(topPages).length" :class="[card, 'p-5']">
                <h2 class="text-sm font-semibold">Pages that drive WhatsApp clicks</h2>
                <ul class="mt-3 grid gap-2 text-sm">
                    <li v-for="(count, url) in topPages" :key="url" class="flex justify-between gap-3"><span class="truncate text-[#5c5f66]">{{ path(String(url)) }}</span><span>{{ count }}</span></li>
                </ul>
            </section>
            <section :class="[card, 'p-5 text-sm']">
                <h2 class="font-semibold">Content</h2>
                <p class="mt-2 text-[#5c5f66]">{{ content.publishedProjects }} of {{ content.projects }} projects published · {{ content.posts }} articles</p>
            </section>
        </div>
    </div>
</template>
