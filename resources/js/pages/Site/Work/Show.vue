<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight, ExternalLink } from '@lucide/vue';
import { motion, useScroll, useTransform } from 'motion-v';
import { computed, ref } from 'vue';
import CtaBand from '@/components/site/CtaBand.vue';
import DeviceFrame from '@/components/site/DeviceFrame.vue';
import PageHero from '@/components/site/PageHero.vue';
import ProjectCard from '@/components/site/ProjectCard.vue';
import TestimonialGrid from '@/components/site/TestimonialGrid.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import AppImage from '@/components/ui/AppImage.vue';
import Button from '@/components/ui/Button.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { Project, Testimonial } from '@/types/models';

const props = defineProps<{ project: Project; testimonials: Testimonial[]; related: Project[] }>();
const { t, n, locale } = useI18n();

const sections = computed(() =>
    [
        { key: 'challenge', title: t('Challenge'), html: props.project.challenge },
        { key: 'approach', title: t('What we built'), html: props.project.approach },
        { key: 'outcome', title: t('Result'), html: props.project.outcome },
    ].filter((s) => s.html),
);

const launched = computed(() =>
    props.project.launched_at
        ? new Intl.DateTimeFormat(locale.value === 'bn' ? 'bn-BD' : 'en-GB', { month: 'long', year: 'numeric' }).format(new Date(props.project.launched_at))
        : null,
);

const showcase = ref<HTMLElement>();
const { scrollYProgress } = useScroll({ target: showcase, offset: ['start end', 'end start'] });
const tilt = useTransform(scrollYProgress, [0, 0.5], [12, 0]);
const scale = useTransform(scrollYProgress, [0, 0.5], [0.92, 1]);
</script>

<template>
    <PageHero :eyebrow="project.industry?.name" :title="project.title" :lead="project.tagline">
        <WhatsAppButton :href="project.whatsapp_url" placement="case-study-hero" :label="t('I want a site like this')" />
        <Button v-if="project.live_url" :href="project.live_url" variant="glass" external>
            {{ project.is_demo ? t('Open the demo') : t('Visit the live site') }}
            <ExternalLink :size="16" aria-hidden="true" />
        </Button>
        <template #after>
            <dl class="mt-12 grid max-w-3xl grid-cols-2 gap-6 border-t border-white/10 pt-8 text-sm sm:grid-cols-4">
                <div v-if="project.client">
                    <dt class="text-white/45">{{ t('Client') }}</dt>
                    <dd class="mt-1 font-medium text-white">{{ project.client.name }}</dd>
                </div>
                <div v-if="project.industry">
                    <dt class="text-white/45">{{ t('Industry') }}</dt>
                    <dd class="mt-1 font-medium"><Link :href="project.industry.url" class="text-white hover:text-brand-200">{{ project.industry.name }}</Link></dd>
                </div>
                <div v-if="project.solution">
                    <dt class="text-white/45">{{ t('Solution') }}</dt>
                    <dd class="mt-1 font-medium"><Link :href="project.solution.url" class="text-white hover:text-brand-200">{{ project.solution.name }}</Link></dd>
                </div>
                <div v-if="launched || project.is_demo">
                    <dt class="text-white/45">{{ project.is_demo ? t('Status') : t('Launched') }}</dt>
                    <dd class="mt-1 font-medium text-white">{{ project.is_demo ? t('Demo / pre-launch') : launched }}</dd>
                </div>
            </dl>
        </template>
    </PageHero>

    <!-- Large screenshot that straightens as it scrolls in -->
    <section ref="showcase" class="relative bg-ink-950 pb-20 [perspective:1600px]">
        <div class="container-page">
            <motion.div :style="{ rotateX: tilt, scale }" class="origin-top">
                <DeviceFrame :image="project.cover" :url="project.live_url" priority sizes="(min-width: 1280px) 1200px, 95vw">
                    <div class="grid size-full place-items-center bg-gradient-to-br from-brand-700 to-ink-900 font-display text-3xl text-white/80">{{ project.title }}</div>
                </DeviceFrame>
            </motion.div>
        </div>
    </section>

    <section class="section-y bg-surface">
        <div class="container-page grid gap-14 lg:grid-cols-[minmax(0,7fr)_minmax(0,4fr)]">
            <div>
                <Reveal v-if="project.summary">
                    <p class="font-display text-2xl leading-relaxed text-fg sm:text-3xl">{{ project.summary }}</p>
                </Reveal>

                <dl v-if="project.metrics?.length" class="mt-12 grid grid-cols-2 gap-6 sm:grid-cols-3">
                    <Reveal v-for="(metric, index) in project.metrics" :key="index" :delay="index * 0.06" class="rounded-3xl border border-line bg-surface-muted p-6">
                        <dd class="font-display text-4xl font-semibold text-link">{{ metric.value ? n(metric.value) : '' }}</dd>
                        <dt class="mt-1 text-muted">{{ metric.label }}</dt>
                    </Reveal>
                </dl>

                <Reveal v-for="section in sections" :key="section.key" class="mt-14">
                    <h2 class="font-display text-2xl font-semibold text-fg sm:text-3xl">{{ section.title }}</h2>
                    <div class="prose-aux mt-5" v-html="section.html" />
                </Reveal>

                <p v-if="!sections.length && !project.summary" class="text-lg text-muted">
                    {{ t('Ask us on WhatsApp how we built this project and what it would take for your business.') }}
                </p>
            </div>

            <aside class="grid content-start gap-6 lg:sticky lg:top-28">
                <div v-if="project.features?.length" class="rounded-3xl border border-line bg-surface-raised p-7">
                    <h2 class="font-display text-lg font-semibold text-fg">{{ t('Modules used') }}</h2>
                    <ul class="mt-4 flex flex-wrap gap-2">
                        <li v-for="feature in project.features" :key="feature.slug" class="rounded-full bg-brand-50 px-3 py-1.5 text-sm text-brand-700 dark:bg-brand-950 dark:text-brand-200">
                            {{ feature.title }}
                        </li>
                    </ul>
                </div>
                <div v-if="project.services?.length || project.technologies?.length" class="rounded-3xl border border-line bg-surface-raised p-7">
                    <template v-if="project.services?.length">
                        <h2 class="font-display text-lg font-semibold text-fg">{{ t('Services') }}</h2>
                        <ul class="mt-3 grid gap-2">
                            <li v-for="service in project.services" :key="service.url">
                                <Link :href="service.url" class="inline-flex items-center gap-1 text-link hover:underline">{{ service.title }} <ArrowUpRight :size="14" aria-hidden="true" /></Link>
                            </li>
                        </ul>
                    </template>
                    <template v-if="project.technologies?.length">
                        <h2 class="mt-6 font-display text-lg font-semibold text-fg">{{ t('Technology') }}</h2>
                        <p class="mt-2 text-muted">{{ project.technologies.map((tech) => tech.name).join(' · ') }}</p>
                    </template>
                </div>
                <div class="rounded-3xl bg-ink-950 p-7 text-white">
                    <h2 class="font-display text-xl font-semibold">{{ t('Want something similar?') }}</h2>
                    <p class="mt-2 text-white/65">{{ t('Message us and mention this project. We will tell you what it takes.') }}</p>
                    <div class="mt-5"><WhatsAppButton :href="project.whatsapp_url" placement="case-study-aside" block /></div>
                </div>
            </aside>
        </div>
    </section>

    <section v-if="project.mobile || project.gallery?.length" class="bg-surface-muted section-y">
        <div class="container-page">
            <div class="flex gap-6 overflow-x-auto rounded-3xl pb-4 [scrollbar-width:thin] snap-x focus-visible:outline-offset-4" tabindex="0" role="region" :aria-label="t('Screenshots')">
                <div v-if="project.mobile" class="w-64 shrink-0 snap-start overflow-hidden rounded-[2.5rem] border-[10px] border-ink-950 bg-ink-950 shadow-2xl">
                    <AppImage :image="project.mobile" sizes="256px" class="aspect-[9/19] w-full object-top" />
                </div>
                <div v-for="image in project.gallery" :key="image.id" class="w-[min(80vw,40rem)] shrink-0 snap-start overflow-hidden rounded-3xl border border-line">
                    <AppImage :image="image" sizes="(min-width: 768px) 640px, 80vw" class="aspect-[16/10] w-full" />
                </div>
            </div>
        </div>
    </section>

    <section v-if="testimonials.length" class="section-y bg-surface">
        <div class="container-page"><TestimonialGrid :testimonials="testimonials" /></div>
    </section>

    <section v-if="related.length" class="section-y border-t border-line bg-surface">
        <div class="container-page">
            <h2 class="font-display text-3xl font-semibold text-fg">{{ t('Related projects') }}</h2>
            <div class="mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                <Reveal v-for="(item, index) in related" :key="item.id" :delay="index * 0.08"><ProjectCard :project="item" /></Reveal>
            </div>
        </div>
    </section>

    <CtaBand :title="t('I want a site like :name', { name: project.client?.name ?? project.title })" :whatsapp-url="project.whatsapp_url" placement="case-study-cta" />
</template>
