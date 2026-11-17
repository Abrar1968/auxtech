<script setup lang="ts">
import { Calculator } from '@lucide/vue';
import CtaBand from '@/components/site/CtaBand.vue';
import PageHero from '@/components/site/PageHero.vue';
import PricingCards from '@/components/site/PricingCards.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { PageIntro, PricingPlan } from '@/types/models';

defineProps<{ intro: PageIntro; groups: { name: string | null; plans: PricingPlan[] }[] }>();
const { t, lp } = useI18n();
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead">
        <WhatsAppButton placement="pricing-hero" :label="t('Ask for a quote')" />
        <Button :href="lp('/estimate')" variant="glass"><Calculator :size="18" aria-hidden="true" />{{ t('Get an estimate') }}</Button>
    </PageHero>

    <section class="section-y bg-surface">
        <div class="container-page grid gap-20">
            <div v-for="group in groups" :key="group.name ?? 'general'">
                <h2 v-if="group.name" class="mb-10 font-display text-3xl font-semibold text-fg">{{ group.name }}</h2>
                <PricingCards :plans="group.plans" placement="pricing-page" />
            </div>

            <Reveal v-if="!groups.length" class="mx-auto max-w-2xl text-center">
                <h2 class="font-display text-3xl font-semibold text-fg">{{ t('Every project is priced to its scope') }}</h2>
                <p class="mt-4 text-lg text-muted">{{ t('Tell us what you sell and what you need. We reply with a clear quote, usually the same day.') }}</p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <WhatsAppButton placement="pricing-empty" />
                    <Button :href="lp('/estimate')" variant="secondary">{{ t('Get an estimate') }}</Button>
                </div>
            </Reveal>
        </div>
    </section>

    <CtaBand placement="pricing-cta" />
</template>
