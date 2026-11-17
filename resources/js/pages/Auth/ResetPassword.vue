<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AuthShell from '@/components/auth/AuthShell.vue';
import { button, input } from '@/components/admin/ui';

const props = defineProps<{ token: string; email: string | null }>();
const form = useForm({ token: props.token, email: props.email ?? '', password: '', password_confirmation: '' });
</script>

<template>
    <AuthShell title="Choose a new password">
        <form class="grid gap-4" @submit.prevent="form.post('/reset-password', { onFinish: () => form.reset('password', 'password_confirmation') })">
            <label class="grid gap-1.5 text-sm font-medium">Email<input v-model="form.email" type="email" autocomplete="username" required :class="input" /></label>
            <label class="grid gap-1.5 text-sm font-medium">New password<input v-model="form.password" type="password" autocomplete="new-password" required :class="input" /></label>
            <label class="grid gap-1.5 text-sm font-medium">Repeat password<input v-model="form.password_confirmation" type="password" autocomplete="new-password" required :class="input" /></label>
            <p v-for="error in form.errors" :key="error" role="alert" class="text-sm text-red-600">{{ error }}</p>
            <button type="submit" :disabled="form.processing" :class="[button.primary, 'min-h-11 w-full']">Save password</button>
        </form>
    </AuthShell>
</template>
