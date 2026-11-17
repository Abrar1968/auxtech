<script setup lang="ts">
import CtaBand from '@/components/site/CtaBand.vue';
import FaqList from '@/components/site/FaqList.vue';
import PageHero from '@/components/site/PageHero.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { Faq, PageIntro } from '@/types/models';

defineProps<{ intro: PageIntro; groups: { group: string; faqs: Faq[] }[] }>();
const { t } = useI18n();

const groupNames: Record<string, string> = {
    general: 'General',
    pricing: 'Pricing',
    ecommerce: 'E-commerce',
    process: 'Process',
    support: 'Support',
};
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead">
        <WhatsAppButton placement="faq-hero" :label="t('Ask your question on WhatsApp')" />
    </PageHero>
    <section class="section-y bg-surface">
        <div class="container-page grid max-w-4xl gap-16">
            <Reveal v-for="group in groups" :key="group.group">
                <h2 v-if="groups.length > 1" class="mb-4 font-display text-2xl font-semibold text-fg">{{ t(groupNames[group.group] ?? group.group) }}</h2>
                <FaqList :faqs="group.faqs" />
            </Reveal>
        </div>
    </section>
    <CtaBand placement="faq-cta" />
</template>
