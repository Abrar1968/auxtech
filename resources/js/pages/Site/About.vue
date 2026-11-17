<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import ReasonGrid from '@/components/home/ReasonGrid.vue';
import ClientMarquee from '@/components/site/ClientMarquee.vue';
import CountUp from '@/components/site/CountUp.vue';
import CtaBand from '@/components/site/CtaBand.vue';
import PageHero from '@/components/site/PageHero.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import AppImage from '@/components/ui/AppImage.vue';
import Reveal from '@/components/ui/Reveal.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';
import type { Client, Industry, PageIntro, Stat, TeamMember, TextRow } from '@/types/models';

const props = defineProps<{
    intro: PageIntro;
    story: string | null;
    team: TeamMember[];
    stats: Stat[];
    projectCount: number;
    industries: Industry[];
    reasons: TextRow[];
    clients: Client[];
}>();

const { site } = useShared();
const { t, n } = useI18n();
const paragraphs = computed(() => (props.story ?? '').split(/\n\s*\n/).filter(Boolean));
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead">
        <WhatsAppButton placement="about-hero" />
    </PageHero>

    <section class="section-y bg-surface">
        <div class="container-page grid gap-14 lg:grid-cols-[minmax(0,7fr)_minmax(0,5fr)]">
            <Reveal>
                <p v-if="site.slogan" class="font-display text-2xl leading-snug font-medium text-link sm:text-3xl">“{{ site.slogan }}”</p>
                <div class="mt-8 grid gap-5 text-lg leading-relaxed text-fg/85">
                    <p v-for="(paragraph, index) in paragraphs" :key="index">{{ paragraph }}</p>
                </div>
            </Reveal>
            <Reveal :delay="0.1" class="grid content-start gap-4">
                <div class="rounded-3xl bg-ink-950 p-8 text-white">
                    <p class="font-display text-6xl font-semibold tracking-tight"><CountUp :value="String(projectCount)" /></p>
                    <p class="mt-2 text-white/65">{{ t('Published projects') }}</p>
                </div>
                <div v-for="(stat, index) in stats" :key="index" class="rounded-3xl border border-line bg-surface-raised p-8">
                    <p class="font-display text-5xl font-semibold tracking-tight text-fg"><CountUp :value="stat.value" /><span class="text-link">{{ stat.suffix }}</span></p>
                    <p class="mt-2 text-muted">{{ stat.label }}</p>
                </div>
                <div v-if="industries.length" class="rounded-3xl border border-line bg-surface-raised p-8">
                    <p class="font-medium text-fg">{{ t('Industries we work in') }}</p>
                    <ul class="mt-4 flex flex-wrap gap-2">
                        <li v-for="industry in industries" :key="industry.slug">
                            <Link :href="industry.url" class="inline-block rounded-full border border-line px-3.5 py-1.5 text-sm text-fg/80 transition hover:border-brand-400 hover:text-link">
                                {{ industry.name }} <span class="text-muted">{{ n(industry.projects_count ?? 0) }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </Reveal>
        </div>
    </section>

    <section v-if="clients.length" class="border-y border-line bg-surface-muted py-14">
        <div class="container-page"><p class="text-center text-sm font-medium text-muted">{{ t('Some of the brands we work with') }}</p></div>
        <ClientMarquee :clients="clients" class="mt-7" />
    </section>

    <section v-if="reasons.length" class="section-y bg-surface">
        <div class="container-page">
            <SectionHeading :title="t('How we work with clients')" />
            <div class="mt-12"><ReasonGrid :reasons="reasons" /></div>
        </div>
    </section>

    <section v-if="team.length" class="section-y bg-surface">
        <div class="container-page">
            <SectionHeading :title="t('Team')" />
            <ul class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <Reveal v-for="(member, index) in team" :key="member.name" as="li" :delay="(index % 4) * 0.06">
                    <div class="aspect-[4/5] overflow-hidden rounded-3xl bg-surface-muted">
                        <AppImage v-if="member.photo" :image="member.photo" sizes="(min-width: 1024px) 25vw, 50vw" class="size-full" />
                        <div v-else class="grid size-full place-items-center bg-gradient-to-br from-brand-600 to-ink-900 font-display text-5xl text-white/80">{{ member.name.charAt(0) }}</div>
                    </div>
                    <h3 class="mt-4 font-display text-lg font-semibold text-fg">{{ member.name }}</h3>
                    <p v-if="member.role" class="text-muted">{{ member.role }}</p>
                    <ul v-if="member.socials.length" class="mt-2 flex gap-3 text-sm">
                        <li v-for="social in member.socials" :key="social.url"><a :href="social.url" target="_blank" rel="noopener" class="text-link hover:underline">{{ social.label }}</a></li>
                    </ul>
                </Reveal>
            </ul>
        </div>
    </section>

    <CtaBand placement="about-cta" />
</template>
