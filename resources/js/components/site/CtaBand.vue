<script setup lang="ts">
import { Clock, MapPin } from '@lucide/vue';
import EmailLink from './EmailLink.vue';
import WhatsAppButton from './WhatsAppButton.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useShared } from '@/composables/useSite';

/** The closing call to action used at the end of most pages. */
defineProps<{ title?: string | null; lead?: string | null; whatsappUrl?: string; placement?: string }>();

const { site } = useShared();
</script>

<template>
    <section class="relative isolate overflow-hidden bg-ink-950 text-white section-y">
        <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid" />
        <div
            aria-hidden="true"
            class="absolute top-1/2 left-1/2 -z-10 h-[30rem] w-[60rem] -translate-1/2 animate-aurora rounded-full bg-[radial-gradient(closest-side,rgb(255_201_60/0.16),transparent)] blur-2xl"
        />
        <Reveal class="container-page text-center">
            <h2 class="mx-auto max-w-3xl font-display text-3xl leading-tight font-semibold tracking-tight sm:text-5xl">{{ title ?? site.cta.title }}</h2>
            <p v-if="lead ?? site.cta.lead" class="mx-auto mt-5 max-w-2xl text-lg text-white/70">{{ lead ?? site.cta.lead }}</p>
            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <WhatsAppButton :href="whatsappUrl" :placement="placement ?? 'cta-band'" size="lg" />
                <EmailLink :placement="`${placement ?? 'cta-band'}-email`" dark />
            </div>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-white/70">
                <span v-if="site.hours" class="inline-flex items-center gap-2"><Clock :size="16" aria-hidden="true" />{{ site.hours }}</span>
                <span v-if="site.address" class="inline-flex items-center gap-2"><MapPin :size="16" aria-hidden="true" />{{ site.address }}</span>
            </div>
        </Reveal>
    </section>
</template>
