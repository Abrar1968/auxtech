<script setup lang="ts">
/** Minimal working sign-in form for Phase 1. Restyled in Phase 2. */
import { useForm } from '@inertiajs/vue3';

const form = useForm({ email: '', password: '', remember: false });

function submit(): void {
    form.post('/login', { onFinish: () => form.reset('password') });
}
</script>

<template>
    <main class="mx-auto grid min-h-screen max-w-sm content-center px-4">
        <img src="/brand/auxtech-logo.svg" alt="Auxtech" width="1125" height="174" class="h-8 w-auto justify-self-start" />
        <h1 class="mt-8 font-display text-2xl font-semibold">Sign in</h1>

        <form class="mt-6 grid gap-4" @submit.prevent="submit">
            <label class="grid gap-1 text-sm font-medium">
                Email
                <input v-model="form.email" type="email" autocomplete="username" required class="min-h-11 rounded-xl border border-slate-300 px-3" />
            </label>
            <label class="grid gap-1 text-sm font-medium">
                Password
                <input v-model="form.password" type="password" autocomplete="current-password" required class="min-h-11 rounded-xl border border-slate-300 px-3" />
            </label>
            <label class="flex items-center gap-2 text-sm"><input v-model="form.remember" type="checkbox" /> Keep me signed in</label>
            <p v-if="form.errors.email" role="alert" class="text-sm text-red-700">{{ form.errors.email }}</p>
            <button type="submit" :disabled="form.processing" class="min-h-11 rounded-full bg-brand-600 px-6 font-medium text-white disabled:opacity-60">
                Sign in
            </button>
        </form>
    </main>
</template>
