<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { Bell, CircleCheck, CircleX, ExternalLink, LogOut, Menu, MessageCircle, Radio, X } from '@lucide/vue';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import Icon from '@/components/ui/Icon.vue';
import { timeAgo, useToast } from '@/composables/useToast';
import type { SharedProps } from '@/types/models';

/** Persistent layout of the admin panel: sidebar, top bar, notifications and live updates. */
const page = usePage();
const props = computed(() => page.props as unknown as SharedProps);
const { toasts, push, dismiss } = useToast();

const sidebar = ref(false);
const bellOpen = ref(false);
const live = ref<'off' | 'connecting' | 'on'>('off');
const unread = ref(props.value.notifications?.unread ?? 0);
watch(() => props.value.notifications?.unread, (value) => (unread.value = value ?? 0));

function isActive(href: string): boolean {
    return href === '/admin' ? page.url === '/admin' || page.url.startsWith('/admin?') : page.url.startsWith(href);
}

// Flash messages from the server become toasts.
watch(
    () => props.value.flash,
    (flash) => {
        if (flash?.success) push({ kind: 'success', title: flash.success });
        if (flash?.error) push({ kind: 'error', title: flash.error }, 8000);
    },
    { immediate: true, deep: true },
);

let leave: (() => void) | undefined;

onMounted(async () => {
    document.title = props.value.seo?.title ?? 'Admin';
    router.on('navigate', () => {
        sidebar.value = false;
        document.title = (page.props.seo as { title?: string })?.title ?? 'Admin';
    });

    if (!props.value.realtime?.enabled || !props.value.auth?.permissions) return;

    // Live updates through Pusher.
    live.value = 'connecting';

    try {
        const { useEcho } = await import('@/lib/echo');
        const echo = await useEcho();
        const channel = echo.private('admin');
        const connection = (echo.connector as unknown as { pusher: { connection: { bind: (e: string, cb: () => void) => void } } }).pusher.connection;
        connection.bind('connected', () => (live.value = 'on'));
        connection.bind('unavailable', () => (live.value = 'off'));
        connection.bind('disconnected', () => (live.value = 'off'));

        const canSeeLeads = props.value.auth?.roles.includes('Super Admin') || props.value.auth?.permissions.includes('leads.view');

        channel.listen('.lead.captured', (lead: { id: number; name: string | null; phone: string | null; source: string }) => {
            unread.value += 1;

            if (canSeeLeads) {
                push({ kind: 'lead', title: `New lead: ${lead.name ?? lead.phone ?? 'Unknown'}`, body: lead.source.replaceAll('_', ' '), href: `/admin/leads/${lead.id}` }, 10000);
                chime();
            }

            if (page.component.startsWith('Admin/Leads') || page.component === 'Admin/Dashboard') {
                router.reload();
            }
        });
        channel.listen('.lead.updated', () => {
            if (page.component.startsWith('Admin/Leads')) router.reload();
        });
        channel.listen('.whatsapp.clicked', (event: { placement: string | null; type: string }) => {
            window.dispatchEvent(new CustomEvent('aux:click', { detail: event }));
        });

        leave = () => echo.leave('admin');
    } catch {
        live.value = 'off';
    }
});

onBeforeUnmount(() => leave?.());

/** Short two-tone sound for a new lead. */
function chime(): void {
    try {
        const audio = new AudioContext();
        [880, 1320].forEach((frequency, index) => {
            const oscillator = audio.createOscillator();
            const gain = audio.createGain();
            oscillator.frequency.value = frequency;
            gain.gain.setValueAtTime(0.0001, audio.currentTime + index * 0.14);
            gain.gain.exponentialRampToValueAtTime(0.12, audio.currentTime + index * 0.14 + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, audio.currentTime + index * 0.14 + 0.3);
            oscillator.connect(gain).connect(audio.destination);
            oscillator.start(audio.currentTime + index * 0.14);
            oscillator.stop(audio.currentTime + index * 0.14 + 0.32);
        });
    } catch {
        // Sound is optional.
    }
}

function markRead(): void {
    router.post('/admin/notifications/read', {}, { preserveScroll: true, preserveState: true, onSuccess: () => (unread.value = 0) });
}
</script>

<template>
    <div class="min-h-screen bg-[#f4f4f5] text-[#232428]" data-admin>
        <!-- Sidebar -->
        <div v-if="sidebar" class="fixed inset-0 z-40 bg-ink-950/50 lg:hidden" @click="sidebar = false" />
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-ink-950 text-white/75 transition-transform duration-300 lg:translate-x-0',
                sidebar ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <div class="flex h-16 items-center justify-between px-5">
                <Link href="/admin"><img src="/brand/auxtech-logo-white.svg" alt="Auxtech" width="1125" height="174" class="h-6 w-auto" /></Link>
                <button class="grid size-9 place-items-center rounded-lg hover:bg-white/10 lg:hidden" aria-label="Close menu" @click="sidebar = false"><X :size="18" /></button>
            </div>
            <nav class="flex-1 overflow-y-auto px-3 pb-6" aria-label="Admin">
                <div v-for="group in props.adminMenu" :key="group.label" class="mt-5 first:mt-2">
                    <p class="px-3 pb-1.5 text-[11px] font-semibold tracking-wider text-white/35 uppercase">{{ group.label }}</p>
                    <ul class="grid gap-0.5">
                        <li v-for="item in group.items" :key="item.href">
                            <Link
                                :href="item.href"
                                :class="[
                                    'flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition',
                                    isActive(item.href) ? 'bg-brand-600 font-medium text-ink-950' : 'hover:bg-white/8 hover:text-white',
                                ]"
                            >
                                <Icon :name="item.icon" :size="17" />
                                {{ item.label }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <div class="lg:pl-64">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-[#e3e4e6] bg-white/85 px-4 backdrop-blur sm:px-6">
                <button class="grid size-10 place-items-center rounded-lg hover:bg-[#ececed] lg:hidden" aria-label="Open menu" @click="sidebar = true"><Menu :size="20" /></button>
                <span
                    :class="['hidden items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium sm:inline-flex', live === 'on' ? 'bg-green-50 text-green-700' : 'bg-[#ececed] text-[#5c5f66]']"
                    :title="live === 'on' ? 'Live updates are on' : 'Live updates are off; refresh to see new leads'"
                >
                    <Radio :size="13" /> {{ live === 'on' ? 'Live' : live === 'connecting' ? 'Connecting…' : 'Live updates off' }}
                </span>
                <div class="ml-auto flex items-center gap-1">
                    <a href="/" target="_blank" class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-sm text-[#5c5f66] hover:bg-[#ececed] sm:inline-flex">View site <ExternalLink :size="14" /></a>

                    <div class="relative">
                        <button class="relative grid size-10 place-items-center rounded-lg hover:bg-[#ececed]" aria-label="Notifications" @click="bellOpen = !bellOpen">
                            <Bell :size="19" />
                            <span v-if="unread" class="absolute top-1.5 right-1.5 grid min-w-4 place-items-center rounded-full bg-red-500 px-1 text-[10px] font-semibold text-white">{{ unread > 9 ? '9+' : unread }}</span>
                        </button>
                        <div v-if="bellOpen" class="absolute right-0 mt-2 w-80 overflow-hidden rounded-xl border border-[#e3e4e6] bg-white shadow-xl" @mouseleave="bellOpen = false">
                            <div class="flex items-center justify-between border-b border-[#e3e4e6] px-4 py-3">
                                <p class="text-sm font-semibold">Notifications</p>
                                <button v-if="unread" class="text-xs font-medium text-link hover:underline" @click="markRead">Mark all read</button>
                            </div>
                            <ul class="max-h-96 overflow-y-auto">
                                <li v-for="item in props.notifications?.items ?? []" :key="item.id">
                                    <Link :href="item.data.lead_id ? `/admin/leads/${item.data.lead_id}` : '/admin'" class="flex gap-3 px-4 py-3 text-sm hover:bg-[#f4f4f5]" @click="bellOpen = false">
                                        <MessageCircle :size="18" :class="item.read ? 'text-[#85888f]' : 'text-link'" />
                                        <span>
                                            <span :class="['block', !item.read && 'font-semibold']">New lead: {{ item.data.name ?? item.data.phone }}</span>
                                            <span class="text-xs text-[#5c5f66]">{{ timeAgo(item.created_at) }}</span>
                                        </span>
                                    </Link>
                                </li>
                                <li v-if="!(props.notifications?.items ?? []).length" class="px-4 py-8 text-center text-sm text-[#5c5f66]">Nothing yet.</li>
                            </ul>
                        </div>
                    </div>

                    <Link href="/admin/profile" class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-[#ececed]">
                        <span class="grid size-8 place-items-center rounded-full bg-brand-600 text-sm font-semibold text-ink-950">{{ props.auth?.name.charAt(0) }}</span>
                        <span class="hidden text-sm font-medium md:block">{{ props.auth?.name }}</span>
                    </Link>
                    <Link href="/logout" method="post" as="button" class="grid size-10 place-items-center rounded-lg text-[#5c5f66] hover:bg-[#ececed]" aria-label="Sign out"><LogOut :size="18" /></Link>
                </div>
            </header>

            <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>

        <!-- Toasts -->
        <div class="fixed right-4 bottom-4 z-[60] grid w-[min(24rem,calc(100vw-2rem))] gap-2" aria-live="polite">
            <TransitionGroup enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-2" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                <div v-for="toast in toasts" :key="toast.id" :class="['flex items-start gap-3 rounded-xl border bg-white p-4 shadow-lg', toast.kind === 'lead' ? 'border-whatsapp' : 'border-[#e3e4e6]']">
                    <CircleCheck v-if="toast.kind === 'success'" :size="20" class="shrink-0 text-green-600" />
                    <CircleX v-else-if="toast.kind === 'error'" :size="20" class="shrink-0 text-red-600" />
                    <MessageCircle v-else :size="20" class="shrink-0 text-whatsapp-dark" />
                    <div class="min-w-0 flex-1 text-sm">
                        <component :is="toast.href ? Link : 'p'" :href="toast.href" class="font-medium hover:underline">{{ toast.title }}</component>
                        <p v-if="toast.body" class="mt-0.5 text-[#5c5f66]">{{ toast.body }}</p>
                    </div>
                    <button class="text-[#85888f] hover:text-[#232428]" aria-label="Dismiss" @click="dismiss(toast.id)"><X :size="16" /></button>
                </div>
            </TransitionGroup>
        </div>
    </div>
</template>
