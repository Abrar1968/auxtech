<script setup lang="ts">
import { ArrowRight, Calculator } from '@lucide/vue';
import { computed } from 'vue';
import HomeHero from '@/components/home/HomeHero.vue';
import HomeProcess from '@/components/home/HomeProcess.vue';
import HomeServices from '@/components/home/HomeServices.vue';
import HomeWork from '@/components/home/HomeWork.vue';
import ReasonGrid from '@/components/home/ReasonGrid.vue';
import ClientMarquee from '@/components/site/ClientMarquee.vue';
import CountUp from '@/components/site/CountUp.vue';
import CtaBand from '@/components/site/CtaBand.vue';
import FaqList from '@/components/site/FaqList.vue';
import ModuleExplorer from '@/components/site/ModuleExplorer.vue';
import PostCard from '@/components/site/PostCard.vue';
import PricingCards from '@/components/site/PricingCards.vue';
import SolutionCard from '@/components/site/SolutionCard.vue';
import TestimonialGrid from '@/components/site/TestimonialGrid.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import Reveal from '@/components/ui/Reveal.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { Client, Faq, Post, PricingPlan, Project, SectionCopy, Service, Solution, Stat, Testimonial, TextRow } from '@/types/models';

/**
 * Home. Sections appear in the order set in Settings > Home page; a section
 * with no content hides itself.
 */
const props = defineProps<{
    hero: { title: string | null; subtitle: string | null };
    sections: string[];
    copy: Record<string, SectionCopy>;
    clients: Client[];
    stats: Stat[];
    services: Service[];
    flagship: Solution | null;
    projects: Project[];
    projectCount: number;
    liveCount: number;
    industryCount: number;
    solutions: Solution[];
    process: TextRow[];
    reasons: TextRow[];
    testimonials: Testimonial[];
    plans: PricingPlan[];
    posts: Post[];
    faqs: Faq[];
    whatsappUrl: string;
}>();

const { t, lp } = useI18n();
const otherSolutions = computed(() => props.solutions.filter((s) => !s.is_flagship));

const has: Record<string, () => boolean> = {
    hero: () => true,
    clients: () => props.clients.length > 0,
    stats: () => props.stats.length > 0,
    services: () => props.services.length > 0,
    flagship: () => !!props.flagship?.feature_groups?.length,
    work: () => props.projects.length > 0,
    solutions: () => otherSolutions.value.length > 0,
    process: () => props.process.length > 0,
    testimonials: () => props.testimonials.length > 0,
    reasons: () => props.reasons.length > 0,
    pricing: () => true,
    posts: () => props.posts.length > 0,
    faq: () => props.faqs.length > 0,
    cta: () => true,
};
const visible = computed(() => props.sections.filter((key) => has[key]?.()));
</script>

<template>
    <template v-for="key in visible" :key="key">
        <HomeHero
            v-if="key === 'hero'"
            :title="hero.title"
            :subtitle="hero.subtitle"
            :projects="projects"
            :flagship="flagship"
            :live-count="liveCount"
            :industry-count="industryCount"
        />

        <section v-else-if="key === 'clients'" class="border-b border-line bg-surface py-12">
            <div class="container-page">
                <p class="text-center text-sm font-medium text-muted">{{ copy.clients?.title }}</p>
            </div>
            <ClientMarquee :clients="clients" class="mt-7" />
        </section>

        <section v-else-if="key === 'stats'" class="bg-surface py-16">
            <div class="container-page">
                <h2 v-if="copy.stats?.title" class="sr-only">{{ copy.stats.title }}</h2>
                <dl class="grid grid-cols-2 gap-8 lg:grid-cols-4">
                    <Reveal v-for="(stat, index) in stats" :key="index" :delay="index * 0.08" class="flex flex-col text-center lg:border-l lg:border-line lg:first:border-0">
                        <dt class="order-2 mt-2 text-muted">{{ stat.label }}</dt>
                        <dd class="font-display text-5xl font-semibold tracking-tight text-fg sm:text-6xl">
                            <CountUp :value="stat.value" /><span class="text-link">{{ stat.suffix }}</span>
                        </dd>
                    </Reveal>
                </dl>
            </div>
        </section>

        <HomeServices v-else-if="key === 'services'" :services="services" :copy="copy.services" />

        <section v-else-if="key === 'flagship' && flagship" class="relative isolate overflow-hidden bg-ink-950 text-white section-y">
            <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid" />
            <div aria-hidden="true" class="absolute top-0 right-0 -z-10 size-[36rem] rounded-full bg-[radial-gradient(closest-side,rgb(1_69_255/0.4),transparent)] blur-2xl" />
            <div class="container-page">
                <SectionHeading :eyebrow="flagship.name" :title="copy.flagship?.title" :lead="copy.flagship?.lead" dark />
                <Reveal class="mt-12"><ModuleExplorer :groups="flagship.feature_groups ?? []" /></Reveal>
                <div class="mt-12 flex flex-wrap items-center gap-3">
                    <WhatsAppButton placement="flagship" :label="t('Ask for a demo')" />
                    <Button v-if="flagship.demo_url" :href="flagship.demo_url" variant="glass" external>{{ t('See a live demo') }}</Button>
                    <Button :href="flagship.url" variant="glass">{{ t('Explore all features') }} <ArrowRight :size="18" aria-hidden="true" /></Button>
                </div>
            </div>
        </section>

        <HomeWork v-else-if="key === 'work'" :projects="projects" :total="projectCount" :copy="copy.work" />

        <section v-else-if="key === 'solutions'" class="section-y bg-surface">
            <div class="container-page">
                <SectionHeading :title="copy.solutions?.title" :lead="copy.solutions?.lead" />
                <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <Reveal v-for="(solution, index) in otherSolutions" :key="solution.id" :delay="(index % 3) * 0.08">
                        <SolutionCard :solution="solution" />
                    </Reveal>
                </div>
            </div>
        </section>

        <HomeProcess v-else-if="key === 'process'" :steps="process" :copy="copy.process" />

        <section v-else-if="key === 'testimonials'" class="section-y bg-surface">
            <div class="container-page">
                <SectionHeading :title="copy.testimonials?.title" :lead="copy.testimonials?.lead" align="center" />
                <div class="mt-14"><TestimonialGrid :testimonials="testimonials" /></div>
            </div>
        </section>

        <section v-else-if="key === 'reasons'" class="section-y bg-surface">
            <div class="container-page">
                <SectionHeading :title="copy.reasons?.title" :lead="copy.reasons?.lead" />
                <div class="mt-14"><ReasonGrid :reasons="reasons" /></div>
            </div>
        </section>

        <section v-else-if="key === 'pricing'" class="section-y bg-surface-muted">
            <div class="container-page">
                <SectionHeading :title="copy.pricing?.title" :lead="copy.pricing?.lead" />
                <div v-if="plans.length" class="mt-14"><PricingCards :plans="plans" placement="home-pricing" /></div>
                <Reveal class="mt-8">
                    <div class="flex flex-col gap-6 rounded-3xl bg-gradient-to-r from-brand-700 via-brand-600 to-violet-accent p-8 text-white sm:flex-row sm:items-center sm:justify-between sm:p-10">
                        <div class="flex items-center gap-5">
                            <span class="hidden size-14 shrink-0 place-items-center rounded-2xl bg-white/15 sm:grid"><Calculator :size="26" aria-hidden="true" /></span>
                            <div>
                                <h3 class="font-display text-2xl font-semibold">{{ t('Get a price in 60 seconds') }}</h3>
                                <p class="mt-1 text-white/75">{{ t('Answer four quick questions. We send the estimate on WhatsApp.') }}</p>
                            </div>
                        </div>
                        <Button :href="lp('/estimate')" variant="glass" size="lg" class="!bg-white !text-brand-700">{{ t('Start the estimate') }}</Button>
                    </div>
                </Reveal>
            </div>
        </section>

        <section v-else-if="key === 'posts'" class="section-y bg-surface">
            <div class="container-page">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <SectionHeading :title="copy.posts?.title" :lead="copy.posts?.lead" />
                    <Button :href="lp('/blog')" variant="secondary">{{ t('All articles') }} <ArrowRight :size="18" aria-hidden="true" /></Button>
                </div>
                <div class="mt-14 grid gap-10 md:grid-cols-3">
                    <Reveal v-for="(post, index) in posts" :key="post.id" :delay="index * 0.08"><PostCard :post="post" /></Reveal>
                </div>
            </div>
        </section>

        <section v-else-if="key === 'faq'" class="section-y bg-surface">
            <div class="container-page grid gap-12 lg:grid-cols-[minmax(0,4fr)_minmax(0,7fr)]">
                <SectionHeading :title="copy.faq?.title" :lead="copy.faq?.lead">
                    <Button :href="lp('/faq')" variant="secondary" class="mt-8">{{ t('More questions') }}</Button>
                </SectionHeading>
                <Reveal><FaqList :faqs="faqs" /></Reveal>
            </div>
        </section>

        <CtaBand v-else-if="key === 'cta'" :title="copy.cta?.title" :lead="copy.cta?.lead" :whatsapp-url="whatsappUrl" placement="home-cta" />
    </template>
</template>
