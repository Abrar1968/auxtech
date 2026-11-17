<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { LoaderCircle, SearchX, X } from '@lucide/vue';
import { AnimatePresence, motion } from 'motion-v';
import { computed, ref } from 'vue';
import CtaBand from '@/components/site/CtaBand.vue';
import PageHero from '@/components/site/PageHero.vue';
import ProjectCard from '@/components/site/ProjectCard.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import { useI18n } from '@/lib/i18n';
import type { Industry, PageIntro, Project } from '@/types/models';

type Filters = { industry: string | null; solution: string | null; service: string | null };

const props = defineProps<{
    intro: PageIntro;
    projects: Project[];
    filters: Filters;
    industries: Industry[];
    solutions: { slug: string; name: string; projects_count: number }[];
    services: { slug: string; title: string; projects_count: number }[];
}>();

const { t, n, tc } = useI18n();
const loading = ref(false);
const active = computed(() => Object.values(props.filters).some(Boolean));

/** Filters update the URL (shareable) without a full reload. */
function apply(change: Partial<Filters>): void {
    const next = { ...props.filters, ...change };
    const query = Object.fromEntries(Object.entries(next).filter(([, value]) => value));

    router.get(window.location.pathname, query, {
        only: ['projects', 'filters'],
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onStart: () => (loading.value = true),
        onFinish: () => (loading.value = false),
    });
}

const chip = (selected: boolean) => [
    'inline-flex min-h-10 items-center gap-2 rounded-full border px-4 text-sm font-medium whitespace-nowrap transition',
    selected ? 'border-brand-600 bg-brand-600 text-white' : 'border-line bg-surface-raised text-fg/80 hover:border-brand-400 hover:text-fg',
];
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead">
        <WhatsAppButton placement="work-hero" :label="t('Get a site like these')" />
    </PageHero>

    <section class="bg-surface pb-24">
        <!-- Sticky filter bar -->
        <div class="sticky top-20 z-30 -mt-8 mb-12">
            <div class="container-page">
                <div class="glass flex flex-col gap-3 rounded-3xl p-3 shadow-[0_20px_50px_-30px_rgb(5_8_22/0.4)] lg:flex-row lg:items-center">
                    <div class="flex gap-2 overflow-x-auto [scrollbar-width:none]" role="group" :aria-label="t('Industry')">
                        <button :class="chip(!filters.industry)" :aria-pressed="!filters.industry" @click="apply({ industry: null })">
                            {{ t('All') }}
                        </button>
                        <button
                            v-for="industry in industries"
                            :key="industry.slug"
                            :class="chip(filters.industry === industry.slug)"
                            :aria-pressed="filters.industry === industry.slug"
                            @click="apply({ industry: filters.industry === industry.slug ? null : industry.slug })"
                        >
                            {{ industry.name }}
                            <span :class="filters.industry === industry.slug ? 'text-white/80' : 'text-muted'">{{ n(industry.projects_count ?? 0) }}</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-2 lg:ml-auto lg:flex">
                        <select
                            :value="filters.solution ?? ''"
                            :aria-label="t('Solution')"
                            class="min-h-10 w-full min-w-0 rounded-full border border-line bg-surface-raised px-4 text-sm text-fg lg:w-auto"
                            @change="apply({ solution: ($event.target as HTMLSelectElement).value || null })"
                        >
                            <option value="">{{ t('All solutions') }}</option>
                            <option v-for="item in solutions" :key="item.slug" :value="item.slug">{{ item.name }} ({{ n(item.projects_count) }})</option>
                        </select>
                        <select
                            :value="filters.service ?? ''"
                            :aria-label="t('Service')"
                            class="min-h-10 w-full min-w-0 rounded-full border border-line bg-surface-raised px-4 text-sm text-fg lg:w-auto"
                            @change="apply({ service: ($event.target as HTMLSelectElement).value || null })"
                        >
                            <option value="">{{ t('All services') }}</option>
                            <option v-for="item in services" :key="item.slug" :value="item.slug">{{ item.title }} ({{ n(item.projects_count) }})</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-page">
            <div class="mb-8 flex items-center gap-3 text-sm text-muted" aria-live="polite">
                <LoaderCircle v-if="loading" :size="16" class="animate-spin" aria-hidden="true" />
                <span>{{ tc(':count project', ':count projects', projects.length) }}</span>
                <button v-if="active" class="inline-flex items-center gap-1 font-medium text-brand-600 hover:underline" @click="apply({ industry: null, solution: null, service: null })">
                    <X :size="14" aria-hidden="true" />{{ t('Clear filters') }}
                </button>
            </div>

            <h2 class="sr-only">{{ t('Projects') }}</h2>
            <motion.div layout class="grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
                <AnimatePresence mode="popLayout">
                    <motion.div
                        v-for="(project, index) in projects"
                        :key="project.id"
                        layout
                        :initial="{ opacity: 0, scale: 0.96, y: 20 }"
                        :animate="{ opacity: 1, scale: 1, y: 0 }"
                        :exit="{ opacity: 0, scale: 0.96 }"
                        :transition="{ duration: 0.45, ease: [0.16, 1, 0.3, 1], delay: Math.min(index, 8) * 0.03 }"
                    >
                        <ProjectCard :project="project" :priority="index < 3" />
                    </motion.div>
                </AnimatePresence>
            </motion.div>

            <div v-if="!projects.length" class="rounded-3xl border border-dashed border-line px-6 py-20 text-center">
                <SearchX :size="40" class="mx-auto text-muted" aria-hidden="true" />
                <p class="mt-4 text-lg text-fg">{{ t('No projects match these filters yet.') }}</p>
                <p class="mt-1 text-muted">{{ t('Tell us what you need; we have probably built something close.') }}</p>
                <div class="mt-6 flex justify-center"><WhatsAppButton placement="work-empty" /></div>
            </div>
        </div>
    </section>

    <CtaBand placement="work-cta" />
</template>
