<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from '@lucide/vue';
import { motion } from 'motion-v';
import { computed } from 'vue';
import { useShared } from '@/composables/useSite';

/** Dark opening section of inner pages, with breadcrumbs from the SEO data. */
defineProps<{ eyebrow?: string | null; title: string | null; lead?: string | null }>();

const { seo } = useShared();
const crumbs = computed(() => seo.value.breadcrumbs ?? []);
</script>

<template>
    <section class="relative isolate overflow-hidden bg-ink-950 pt-32 pb-16 text-white sm:pt-40 sm:pb-24">
        <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid" />
        <div
            aria-hidden="true"
            class="absolute -top-48 left-1/2 -z-10 h-[34rem] w-[54rem] -translate-x-1/2 animate-aurora rounded-full bg-[radial-gradient(closest-side,rgb(1_69_255/0.55),transparent)] blur-2xl"
        />
        <div aria-hidden="true" class="absolute -right-32 bottom-0 -z-10 size-[26rem] rounded-full bg-[radial-gradient(closest-side,rgb(34_211_238/0.18),transparent)] blur-2xl" />

        <div class="container-page">
            <nav v-if="crumbs.length > 1" aria-label="Breadcrumb" class="mb-8">
                <ol class="flex flex-wrap items-center gap-1.5 text-sm text-white/50">
                    <li v-for="(crumb, index) in crumbs" :key="crumb.url" class="flex items-center gap-1.5">
                        <ChevronRight v-if="index > 0" :size="14" aria-hidden="true" />
                        <Link v-if="index < crumbs.length - 1" :href="crumb.url" class="transition hover:text-white">{{ crumb.name }}</Link>
                        <span v-else aria-current="page" class="text-white/80">{{ crumb.name }}</span>
                    </li>
                </ol>
            </nav>

            <motion.div
                :initial="{ y: 18 }"
                :animate="{ y: 0 }"
                :transition="{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }"
                class="max-w-4xl"
            >
                <p v-if="eyebrow" class="mb-5 inline-flex items-center gap-2 rounded-full glass-dark px-3 py-1 text-xs font-semibold tracking-[0.14em] text-brand-200 uppercase">
                    <span class="size-1.5 rounded-full bg-current" />{{ eyebrow }}
                </p>
                <h1 class="font-display text-4xl leading-[1.08] font-semibold tracking-tight sm:text-5xl lg:text-6xl">{{ title }}</h1>
                <p v-if="lead" class="mt-6 max-w-2xl text-lg leading-relaxed text-white/70 sm:text-xl">{{ lead }}</p>
                <div v-if="$slots.default" class="mt-9 flex flex-wrap items-center gap-3">
                    <slot />
                </div>
            </motion.div>
            <slot name="after" />
        </div>
    </section>
</template>
