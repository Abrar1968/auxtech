<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AuthShell from '@/components/auth/AuthShell.vue';
import { button, input } from '@/components/admin/ui';

defineProps<{ status?: string | null }>();
const form = useForm({ email: '', password: '', remember: false });

function submit(): void {
    form.post('/login', { onFinish: () => form.reset('password') });
}
</script>

<template>
    <AuthShell title="Sign in" description="Admin panel for the Auxtech website.">
        <p v-if="status" class="mb-4 rounded-lg bg-green-50 px-3 py-2 text-sm text-green-800">{{ status }}</p>
        <form class="grid gap-4" @submit.prevent="submit">
            <label class="grid gap-1.5 text-sm font-medium">Email<input v-model="form.email" type="email" autocomplete="username" required autofocus :class="input" /></label>
            <label class="grid gap-1.5 text-sm font-medium">
                <span class="flex justify-between">Password<Link href="/forgot-password" class="font-normal text-brand-600 hover:underline">Forgot?</Link></span>
                <input v-model="form.password" type="password" autocomplete="current-password" required :class="input" />
            </label>
            <label class="flex items-center gap-2 text-sm"><input v-model="form.remember" type="checkbox" class="size-4 accent-brand-600" />Keep me signed in</label>
            <p v-if="form.errors.email" role="alert" class="text-sm text-red-600">{{ form.errors.email }}</p>
            <button type="submit" :disabled="form.processing" :class="[button.primary, 'min-h-11 w-full']">Sign in</button>
        </form>
    </AuthShell>
</template>
