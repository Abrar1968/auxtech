<script setup lang="ts">
import CtaBand from '@/components/site/CtaBand.vue';
import PageHero from '@/components/site/PageHero.vue';
import ProjectCard from '@/components/site/ProjectCard.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { Industry, Project } from '@/types/models';

defineProps<{ industry: Industry; projects: Project[] }>();
const { tc } = useI18n();
</script>

<template>
    <PageHero :eyebrow="tc(':count project', ':count projects', projects.length)" :title="industry.name" :lead="industry.description">
        <WhatsAppButton placement="industry-hero" />
    </PageHero>
    <section class="section-y bg-surface">
        <div class="container-page grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
            <Reveal v-for="(project, index) in projects" :key="project.id" :delay="(index % 3) * 0.08"><ProjectCard :project="project" :priority="index < 3" /></Reveal>
        </div>
    </section>
    <CtaBand placement="industry-cta" />
</template>
