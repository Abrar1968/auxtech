<script setup lang="ts">
import { ExternalLink } from '@lucide/vue';
import { computed } from 'vue';
import CtaBand from '@/components/site/CtaBand.vue';
import DetailSections from '@/components/site/DetailSections.vue';
import ModuleExplorer from '@/components/site/ModuleExplorer.vue';
import PageHero from '@/components/site/PageHero.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';
import Reveal from '@/components/ui/Reveal.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { Faq, PricingPlan, Project, Solution } from '@/types/models';

const props = defineProps<{ solution: Solution; projects: Project[]; faqs: Faq[]; plans: PricingPlan[]; whatsappUrl: string }>();
const { t, tc } = useI18n();
const groups = computed(() => props.solution.feature_groups ?? []);
const featureCount = computed(() => groups.value.reduce((sum, g) => sum + g.features.length, 0));
</script>

<template>
    <PageHero :eyebrow="solution.tagline" :title="solution.name" :lead="solution.summary">
        <WhatsAppButton :href="whatsappUrl" placement="solution-hero" :label="t('Ask for a demo')" />
        <Button v-if="solution.demo_url" :href="solution.demo_url" variant="glass" external>{{ t('See a live demo') }} <ExternalLink :size="16" aria-hidden="true" /></Button>
        <Button v-if="solution.external_url" :href="solution.external_url" variant="glass" external>{{ t('Visit the website') }} <ExternalLink :size="16" aria-hidden="true" /></Button>
    </PageHero>

    <section v-if="groups.length" class="relative isolate overflow-hidden bg-ink-950 pb-24 text-white">
        <div aria-hidden="true" class="absolute right-0 bottom-0 -z-10 size-[30rem] rounded-full bg-brand-600/25 blur-3xl" />
        <div class="container-page">
            <SectionHeading :title="tc(':count module', ':count modules', featureCount)" :lead="t('Choose a group, then a module.')" dark />
            <Reveal class="mt-10"><ModuleExplorer :groups="groups" /></Reveal>
        </div>
    </section>

    <section v-if="groups.length" class="section-y bg-surface">
        <div class="container-page">
            <SectionHeading :title="t('Every module at a glance')" />
            <div class="mt-12 grid gap-10 md:grid-cols-2 xl:grid-cols-4">
                <Reveal v-for="(group, index) in groups" :key="group.key" :delay="index * 0.06">
                    <h3 class="font-display text-lg font-semibold text-fg">{{ group.name }}</h3>
                    <p v-if="group.description" class="mt-1 text-sm text-muted">{{ group.description }}</p>
                    <ul class="mt-4 grid gap-2">
                        <li v-for="feature in group.features" :key="feature.slug" class="flex items-center gap-3 rounded-2xl border border-line bg-surface-raised px-4 py-3">
                            <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-200"><Icon :name="feature.icon" :size="16" /></span>
                            <span class="text-[0.95rem] text-fg">{{ feature.title }}</span>
                        </li>
                    </ul>
                </Reveal>
            </div>
        </div>
    </section>

    <section v-if="solution.body" class="section-y bg-surface">
        <div class="container-page max-w-3xl"><div class="prose-aux" v-html="solution.body" /></div>
    </section>

    <DetailSections :projects="projects" :plans="plans" :faqs="faqs" :projects-title="t('Built with :name', { name: solution.name })" placement="solution" />
    <CtaBand :whatsapp-url="whatsappUrl" placement="solution-cta" />
</template>
