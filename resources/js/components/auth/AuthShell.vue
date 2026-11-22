<script setup lang="ts">
/** Two-panel frame for the sign-in screens. */
defineProps<{ title: string; description?: string }>();
</script>

<template>
    <div class="grid min-h-screen bg-white text-[#232428] lg:grid-cols-2">
        <div class="relative isolate hidden overflow-hidden bg-ink-950 p-12 text-white lg:flex lg:flex-col">
            <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid" />
            <div aria-hidden="true" class="absolute -top-32 -left-32 -z-10 size-[30rem] animate-aurora rounded-full bg-[radial-gradient(closest-side,rgb(255_201_60/0.16),transparent)] blur-2xl" />
            <img src="/brand/auxtech-logo-white.svg" alt="Auxtech" width="1125" height="174" class="h-7 w-auto self-start" />
            <p class="mt-auto max-w-md font-display text-3xl leading-snug font-semibold">Leads, projects and the whole website, in one place.</p>
        </div>
        <main class="grid place-items-center px-5 py-12">
            <div class="w-full max-w-sm">
                <img src="/brand/auxtech-logo.svg" alt="Auxtech" width="1125" height="174" class="mb-10 h-7 w-auto lg:hidden" />
                <h1 class="font-display text-2xl font-semibold">{{ title }}</h1>
                <p v-if="description" class="mt-2 text-sm text-[#5c5f66]">{{ description }}</p>
                <div class="mt-8"><slot /></div>
            </div>
        </main>
    </div>
</template>
