<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ShieldCheck } from '@lucide/vue';
import { ref } from 'vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import { button, card, input, inputInline } from '@/components/admin/ui';
import { xsrfToken } from '@/lib/tracking';

/**
 * My account: change password and set up two-factor sign-in. The actions
 * are Fortify endpoints; risky ones ask for the password first.
 */
const props = defineProps<{ twoFactor: { enabled: boolean; confirmed: boolean; required: boolean }; status: string | null }>();

const password = useForm({ current_password: '', password: '', password_confirmation: '' });
const confirmForm = useForm({ code: '' });
const qr = ref<string | null>(null);
const codes = ref<string[]>([]);

function changePassword(): void {
    password.put('/user/password', { errorBag: 'updatePassword', preserveScroll: true, onSuccess: () => password.reset() });
}

async function getJson<T>(url: string): Promise<T> {
    const response = await fetch(url, { headers: { Accept: 'application/json', 'X-XSRF-TOKEN': xsrfToken() }, credentials: 'same-origin' });

    return (await response.json()) as T;
}

async function loadSetup(): Promise<void> {
    qr.value = (await getJson<{ svg: string }>('/user/two-factor-qr-code')).svg;
    codes.value = await getJson<string[]>('/user/two-factor-recovery-codes');
}

function enable(): void {
    router.post('/user/two-factor-authentication', {}, { preserveScroll: true, onSuccess: () => loadSetup() });
}
function confirmCode(): void {
    confirmForm.post('/user/confirmed-two-factor-authentication', { errorBag: 'confirmTwoFactorAuthentication', preserveScroll: true, onSuccess: () => (qr.value = null) });
}
function disable(): void {
    router.delete('/user/two-factor-authentication', { preserveScroll: true });
}
async function showCodes(): Promise<void> {
    codes.value = await getJson<string[]>('/user/two-factor-recovery-codes');
}

if (props.twoFactor.enabled && !props.twoFactor.confirmed) void loadSetup();
</script>

<template>
    <PageHeader title="My account" />

    <div class="grid max-w-3xl gap-6">
        <section :class="[card, 'p-5 sm:p-6']">
            <div class="flex items-start gap-3">
                <ShieldCheck :size="22" :class="twoFactor.confirmed ? 'text-green-600' : 'text-[#85888f]'" />
                <div class="flex-1">
                    <h2 class="font-semibold">Two-factor sign-in</h2>
                    <p class="mt-1 text-sm text-[#5c5f66]">
                        {{ twoFactor.confirmed ? 'On. You enter a code from your authenticator app when you sign in.' : 'Off. Turn it on so a stolen password is not enough to get in.' }}
                    </p>
                    <p v-if="twoFactor.required && !twoFactor.confirmed" class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-sm text-amber-900">Your organisation requires this before you can use the admin panel.</p>
                </div>
            </div>

            <div v-if="qr" class="mt-5 grid gap-4 rounded-lg bg-[#f7f7f8] p-4 ring-1 ring-[#e3e4e6] sm:grid-cols-[auto_1fr]">
                <div class="size-44 rounded-lg bg-white p-2" v-html="qr" />
                <form class="grid content-start gap-3" @submit.prevent="confirmCode">
                    <p class="text-sm">1. Scan this code with Google Authenticator, Microsoft Authenticator or a similar app.<br />2. Enter the 6-digit code it shows.</p>
                    <label class="sr-only" for="code">Code</label>
                    <input id="code" v-model="confirmForm.code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" :class="[inputInline, 'w-40 tracking-[0.3em]']" />
                    <p v-if="confirmForm.errors.code" class="text-xs text-red-600">{{ confirmForm.errors.code }}</p>
                    <button :class="[button.primary, 'justify-self-start']" :disabled="confirmForm.code.length < 6">Confirm</button>
                </form>
            </div>

            <div v-if="codes.length" class="mt-5 rounded-lg bg-[#f7f7f8] p-4 ring-1 ring-[#e3e4e6]">
                <p class="text-sm font-medium">Recovery codes</p>
                <p class="text-xs text-[#5c5f66]">Keep these somewhere safe. Each one signs you in once if you lose your phone.</p>
                <ul class="mt-3 grid grid-cols-2 gap-1 font-mono text-sm"><li v-for="code in codes" :key="code">{{ code }}</li></ul>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                <button v-if="!twoFactor.enabled" :class="button.primary" @click="enable">Turn on</button>
                <template v-else>
                    <button v-if="twoFactor.confirmed && !codes.length" :class="button.secondary" @click="showCodes">Show recovery codes</button>
                    <button v-if="!twoFactor.required" :class="[button.secondary, '!text-red-600']" @click="disable">Turn off</button>
                </template>
            </div>
        </section>

        <section :class="[card, 'p-5 sm:p-6']">
            <h2 class="font-semibold">Change password</h2>
            <form class="mt-4 grid max-w-md gap-3" @submit.prevent="changePassword">
                <label class="grid gap-1 text-sm font-medium">Current password<input v-model="password.current_password" type="password" autocomplete="current-password" :class="input" /></label>
                <p v-if="password.errors.current_password" class="text-xs text-red-600">{{ password.errors.current_password }}</p>
                <label class="grid gap-1 text-sm font-medium">New password<input v-model="password.password" type="password" autocomplete="new-password" :class="input" /></label>
                <p v-if="password.errors.password" class="text-xs text-red-600">{{ password.errors.password }}</p>
                <label class="grid gap-1 text-sm font-medium">Repeat new password<input v-model="password.password_confirmation" type="password" autocomplete="new-password" :class="input" /></label>
                <button :class="[button.primary, 'justify-self-start']" :disabled="password.processing">Update password</button>
                <p v-if="status === 'password-updated'" class="text-sm text-green-700">Password updated.</p>
            </form>
        </section>
    </div>
</template>
