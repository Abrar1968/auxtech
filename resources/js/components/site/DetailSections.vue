<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import FaqList from './FaqList.vue';
import PricingCards from './PricingCards.vue';
import ProjectCard from './ProjectCard.vue';
import Button from '@/components/ui/Button.vue';
import Reveal from '@/components/ui/Reveal.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { Faq, PricingPlan, Project } from '@/types/models';

/** Proof, pricing and questions: the lower half of service, solution and landing pages. */
defineProps<{ projects: Project[]; plans: PricingPlan[]; faqs: Faq[]; projectsTitle?: string; placement: string }>();
const { t, lp } = useI18n();
</script>

<template>
    <section v-if="projects.length" class="section-y bg-surface-muted">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <SectionHeading :title="projectsTitle ?? t('Projects')" />
                <Button :href="lp('/work')" variant="secondary">{{ t('All work') }} <ArrowRight :size="18" aria-hidden="true" /></Button>
            </div>
            <div class="mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                <Reveal v-for="(project, index) in projects" :key="project.id" :delay="(index % 3) * 0.08"><ProjectCard :project="project" /></Reveal>
            </div>
        </div>
    </section>

    <section v-if="plans.length" class="section-y bg-surface">
        <div class="container-page">
            <SectionHeading :title="t('Packages')" />
            <div class="mt-12"><PricingCards :plans="plans" :placement="`${placement}-pricing`" /></div>
        </div>
    </section>

    <section v-if="faqs.length" class="section-y bg-surface">
        <div class="container-page grid gap-12 lg:grid-cols-[minmax(0,4fr)_minmax(0,7fr)]">
            <SectionHeading :title="t('Frequently asked questions')" />
            <Reveal><FaqList :faqs="faqs" /></Reveal>
        </div>
    </section>
</template>
