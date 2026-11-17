<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AuthShell from '@/components/auth/AuthShell.vue';
import { button, input } from '@/components/admin/ui';

const form = useForm({ password: '' });
</script>

<template>
    <AuthShell title="Confirm your password" description="For your security, enter your password to continue.">
        <form class="grid gap-4" @submit.prevent="form.post('/user/confirm-password', { onFinish: () => form.reset() })">
            <input v-model="form.password" type="password" autocomplete="current-password" aria-label="Password" autofocus :class="input" />
            <p v-if="form.errors.password" role="alert" class="text-sm text-red-600">{{ form.errors.password }}</p>
            <button type="submit" :disabled="form.processing" :class="[button.primary, 'min-h-11 w-full']">Confirm</button>
        </form>
    </AuthShell>
</template>
