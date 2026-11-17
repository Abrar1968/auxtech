<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import { computed } from 'vue';
import CtaBand from '@/components/site/CtaBand.vue';
import PageHero from '@/components/site/PageHero.vue';
import SolutionCard from '@/components/site/SolutionCard.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Icon from '@/components/ui/Icon.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { PageIntro, Solution } from '@/types/models';

const props = defineProps<{ intro: PageIntro; solutions: Solution[] }>();
const { t, tc } = useI18n();
const flagship = computed(() => props.solutions.find((s) => s.is_flagship));
const others = computed(() => props.solutions.filter((s) => !s.is_flagship));
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead">
        <WhatsAppButton placement="solutions-hero" />
    </PageHero>

    <section class="section-y bg-surface">
        <div class="container-page">
            <Reveal v-if="flagship">
                <Link
                    :href="flagship.url"
                    class="group relative isolate block overflow-hidden rounded-[2rem] bg-ink-950 p-8 text-white sm:p-12"
                >
                    <div aria-hidden="true" class="absolute -top-32 -right-32 -z-10 size-[30rem] rounded-full bg-brand-600/50 blur-3xl transition duration-700 group-hover:scale-110" />
                    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid" />
                    <span class="inline-flex items-center gap-2 rounded-full glass-dark px-3 py-1 text-xs font-semibold tracking-[0.14em] text-brand-200 uppercase">{{ t('Flagship') }}</span>
                    <div class="mt-6 flex items-center gap-4">
                        <span class="grid size-14 place-items-center rounded-2xl bg-brand-600"><Icon :name="flagship.icon" :size="26" /></span>
                        <h2 class="font-display text-3xl font-semibold sm:text-5xl">{{ flagship.name }}</h2>
                    </div>
                    <p class="mt-6 max-w-2xl text-lg text-white/70">{{ flagship.summary }}</p>
                    <p class="mt-8 inline-flex items-center gap-2 font-medium text-brand-200">
                        <span v-if="flagship.projects_count">{{ tc(':count project', ':count projects', flagship.projects_count) }} ·</span>
                        {{ t('Explore all features') }} <ArrowRight :size="18" class="transition group-hover:translate-x-1" aria-hidden="true" />
                    </p>
                </Link>
            </Reveal>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Reveal v-for="(solution, index) in others" :key="solution.id" :delay="(index % 3) * 0.08"><SolutionCard :solution="solution" /></Reveal>
            </div>
        </div>
    </section>

    <CtaBand placement="solutions-cta" />
</template>
