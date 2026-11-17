<script setup lang="ts">
import { Clock, ExternalLink, Mail, MapPin } from '@lucide/vue';
import EmailLink from '@/components/site/EmailLink.vue';
import LeadForm from '@/components/site/LeadForm.vue';
import PageHero from '@/components/site/PageHero.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';
import type { PageIntro } from '@/types/models';

defineProps<{ intro: PageIntro; services: { id: number; title: string }[]; whatsappUrl: string; mapUrl: string }>();

const { site } = useShared();
const { t } = useI18n();
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead" />

    <section class="relative bg-surface pb-24">
        <div class="container-page -mt-10 grid gap-6 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
            <div class="grid content-start gap-4">
                <!-- WhatsApp first: it is the fastest route -->
                <Reveal class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-whatsapp-dark to-[#075e54] p-8 text-white shadow-[0_30px_60px_-30px_rgb(18_140_126/0.8)]">
                    <p class="text-sm font-semibold tracking-wider text-white/70 uppercase">{{ t('Fastest reply') }}</p>
                    <h2 class="mt-2 font-display text-3xl font-semibold">WhatsApp</h2>
                    <p class="mt-1 font-display text-2xl tabular-nums">{{ site.phoneDisplay }}</p>
                    <p class="mt-3 text-white/75">{{ t('We usually reply within minutes during office hours.') }}</p>
                    <div class="mt-6"><WhatsAppButton :href="whatsappUrl" placement="contact-card" size="lg" class="!bg-white" /></div>
                </Reveal>

                <Reveal :delay="0.06" class="rounded-3xl border border-line bg-surface-raised p-7">
                    <div class="flex items-start gap-4">
                        <span class="grid size-11 shrink-0 place-items-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-200"><Mail :size="20" aria-hidden="true" /></span>
                        <div>
                            <h2 class="font-display text-lg font-semibold text-fg">{{ t('Email') }}</h2>
                            <EmailLink placement="contact-email" :show-icon="false" class="!min-h-0 text-brand-600" />
                        </div>
                    </div>
                </Reveal>

                <Reveal :delay="0.12" class="rounded-3xl border border-line bg-surface-raised p-7">
                    <div class="flex items-start gap-4">
                        <span class="grid size-11 shrink-0 place-items-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-200"><MapPin :size="20" aria-hidden="true" /></span>
                        <div>
                            <h2 class="font-display text-lg font-semibold text-fg">{{ t('Office') }}</h2>
                            <address class="mt-1 text-muted not-italic">{{ site.address }}</address>
                            <a :href="mapUrl" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:underline">
                                {{ t('Open in Google Maps') }} <ExternalLink :size="14" aria-hidden="true" />
                            </a>
                        </div>
                    </div>
                    <div v-if="site.hours" class="mt-6 flex items-start gap-4 border-t border-line pt-6">
                        <span class="grid size-11 shrink-0 place-items-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-200"><Clock :size="20" aria-hidden="true" /></span>
                        <div>
                            <h2 class="font-display text-lg font-semibold text-fg">{{ t('Hours') }}</h2>
                            <p class="mt-1 text-muted">{{ site.hours }}</p>
                        </div>
                    </div>
                </Reveal>
            </div>

            <Reveal :delay="0.08" class="rounded-3xl border border-line bg-surface-raised p-7 shadow-[0_30px_70px_-50px_rgb(5_8_22/0.5)] sm:p-10">
                <h2 class="font-display text-2xl font-semibold text-fg">{{ t('Prefer a form?') }}</h2>
                <p class="mt-2 mb-8 text-muted">{{ t('Leave your details and we will get back to you.') }}</p>
                <LeadForm :services="services" placement="contact-form" />
            </Reveal>
        </div>
    </section>
</template>
