<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AuthShell from '@/components/auth/AuthShell.vue';
import { button, input } from '@/components/admin/ui';

defineProps<{ status?: string | null }>();
const form = useForm({ email: '' });
</script>

<template>
    <AuthShell title="Reset your password" description="We will email you a link to choose a new password.">
        <p v-if="status" class="mb-4 rounded-lg bg-green-50 px-3 py-2 text-sm text-green-800">{{ status }}</p>
        <form class="grid gap-4" @submit.prevent="form.post('/forgot-password')">
            <label class="grid gap-1.5 text-sm font-medium">Email<input v-model="form.email" type="email" autocomplete="username" required :class="input" /></label>
            <p v-if="form.errors.email" role="alert" class="text-sm text-red-600">{{ form.errors.email }}</p>
            <button type="submit" :disabled="form.processing" :class="[button.primary, 'min-h-11 w-full']">Email me a link</button>
            <p class="text-xs text-[#5c5f66]">This needs email to be set up in Settings → Email. Otherwise ask a Super Admin to set a new password for you.</p>
            <Link href="/login" class="text-sm text-link hover:underline">Back to sign in</Link>
        </form>
    </AuthShell>
</template>
