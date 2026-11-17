<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthShell from '@/components/auth/AuthShell.vue';
import { button, input } from '@/components/admin/ui';

const recovery = ref(false);
const form = useForm({ code: '', recovery_code: '' });

function submit(): void {
    form.transform((data) => (recovery.value ? { recovery_code: data.recovery_code } : { code: data.code })).post('/two-factor-challenge');
}
</script>

<template>
    <AuthShell title="Two-factor sign-in" :description="recovery ? 'Enter one of your recovery codes.' : 'Enter the 6-digit code from your authenticator app.'">
        <form class="grid gap-4" @submit.prevent="submit">
            <input v-if="!recovery" v-model="form.code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" aria-label="Code" autofocus :class="[input, 'text-center text-xl tracking-[0.4em]']" />
            <input v-else v-model="form.recovery_code" autocomplete="off" aria-label="Recovery code" :class="input" />
            <p v-for="error in form.errors" :key="error" role="alert" class="text-sm text-red-600">{{ error }}</p>
            <button type="submit" :disabled="form.processing" :class="[button.primary, 'min-h-11 w-full']">Continue</button>
            <button type="button" class="text-sm text-brand-600 hover:underline" @click="recovery = !recovery">{{ recovery ? 'Use an authenticator code' : 'Use a recovery code' }}</button>
        </form>
    </AuthShell>
</template>
