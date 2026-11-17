<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import CtaBand from '@/components/site/CtaBand.vue';
import DetailSections from '@/components/site/DetailSections.vue';
import PageHero from '@/components/site/PageHero.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import { useI18n } from '@/lib/i18n';
import type { Faq, Project, Service, Solution } from '@/types/models';

defineProps<{
    landing: { title: string; intro: string | null; body: string | null; location: string | null };
    service: Service | null;
    solution: Solution | null;
    projects: Project[];
    faqs: Faq[];
    whatsappUrl: string;
}>();
const { t, lp } = useI18n();
</script>

<template>
    <PageHero :eyebrow="landing.location" :title="landing.title" :lead="landing.intro">
        <WhatsAppButton :href="whatsappUrl" placement="landing-hero" />
        <Button :href="lp('/estimate')" variant="glass">{{ t('Get an estimate') }}</Button>
    </PageHero>

    <section v-if="landing.body || service || solution" class="section-y bg-surface">
        <div class="container-page grid max-w-5xl gap-10 lg:grid-cols-[minmax(0,1fr)_18rem]">
            <div class="prose-aux" v-html="landing.body" />
            <aside class="grid content-start gap-3">
                <Link v-if="service" :href="service.url" class="flex items-center justify-between rounded-2xl border border-line p-5 font-medium text-fg hover:border-brand-400">
                    {{ service.title }} <ArrowRight :size="18" class="text-link" aria-hidden="true" />
                </Link>
                <Link v-if="solution" :href="solution.url" class="flex items-center justify-between rounded-2xl border border-line p-5 font-medium text-fg hover:border-brand-400">
                    {{ solution.name }} <ArrowRight :size="18" class="text-link" aria-hidden="true" />
                </Link>
            </aside>
        </div>
    </section>

    <DetailSections :projects="projects" :plans="[]" :faqs="faqs" placement="landing" />
    <CtaBand :whatsapp-url="whatsappUrl" placement="landing-cta" />
</template>
