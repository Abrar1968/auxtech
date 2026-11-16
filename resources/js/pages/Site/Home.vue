<script setup lang="ts">
/**
 * Environment check page. It proves the stack is wired end to end
 * (Laravel → Inertia → Vue → Tailwind v4 → Motion). The real home page
 * is built in Phase 2 from docs/03-FRONTEND-DESIGN.md.
 */
import { motion } from 'motion-v';

defineProps<{
    company: {
        name: string;
        tagline: string;
        domain: string;
        email: string;
        address: { full: string };
        phone: { local: string };
    };
    whatsappUrl: string;
    stack: { laravel: string; php: string; database: string };
}>();
</script>

<template>
    <main class="relative grid min-h-screen place-items-center overflow-hidden bg-ink-950 px-4 py-16 text-white">
        <div
            aria-hidden="true"
            class="pointer-events-none absolute -top-40 left-1/2 h-[36rem] w-[36rem] -translate-x-1/2 rounded-full bg-brand-600/40 blur-[120px]"
        />

        <motion.section
            :initial="{ opacity: 0, y: 24 }"
            :animate="{ opacity: 1, y: 0 }"
            :transition="{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }"
            class="relative w-full max-w-xl rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl sm:p-10"
        >
            <img src="/brand/auxtech-logo-white.svg" :alt="company.name" width="1125" height="174" class="h-8 w-auto" />

            <h1 class="mt-8 font-display text-3xl font-semibold tracking-tight sm:text-4xl">Environment ready</h1>
            <p class="mt-3 text-white/70">{{ company.tagline }}.</p>

            <dl class="mt-8 grid grid-cols-3 gap-3 text-sm">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <dt class="text-white/50">Laravel</dt>
                    <dd class="mt-1 font-medium" data-testid="laravel-version">{{ stack.laravel }}</dd>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <dt class="text-white/50">PHP</dt>
                    <dd class="mt-1 font-medium">{{ stack.php }}</dd>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <dt class="text-white/50">Database</dt>
                    <dd class="mt-1 font-medium">{{ stack.database }}</dd>
                </div>
            </dl>

            <p class="mt-8 text-sm text-white/60">{{ company.address.full }}</p>
            <p class="mt-1 text-sm text-white/60">{{ company.email }} · {{ company.domain }}</p>

            <a
                :href="whatsappUrl"
                target="_blank"
                rel="noopener"
                class="mt-6 inline-flex min-h-11 items-center justify-center rounded-full bg-whatsapp px-6 font-medium text-ink-950 transition hover:brightness-110"
            >
                WhatsApp {{ company.phone.local }}
            </a>
        </motion.section>
    </main>
</template>
