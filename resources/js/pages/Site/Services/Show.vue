<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight, Check } from '@lucide/vue';
import CtaBand from '@/components/site/CtaBand.vue';
import DetailSections from '@/components/site/DetailSections.vue';
import PageHero from '@/components/site/PageHero.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';
import Reveal from '@/components/ui/Reveal.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useI18n } from '@/lib/i18n';
import type { Faq, PricingPlan, Project, Service } from '@/types/models';

defineProps<{ service: Service; projects: Project[]; faqs: Faq[]; plans: PricingPlan[]; whatsappUrl: string }>();
const { t, lp, n } = useI18n();
</script>

<template>
    <PageHero :eyebrow="service.parent?.title ?? null" :title="service.title" :lead="service.tagline ?? service.excerpt">
        <WhatsAppButton :href="whatsappUrl" placement="service-hero" />
        <Button :href="lp('/estimate')" variant="glass">{{ t('Get an estimate') }}</Button>
    </PageHero>

    <section v-if="service.body || service.features?.length" class="section-y bg-surface">
        <div class="container-page grid gap-14 lg:grid-cols-2">
            <Reveal v-if="service.body">
                <p v-if="service.tagline && service.excerpt" class="font-display text-2xl leading-relaxed text-fg">{{ service.excerpt }}</p>
                <div class="prose-aux mt-6" v-html="service.body" />
            </Reveal>
            <div v-if="service.features?.length" :class="!service.body && 'lg:col-span-2'">
                <h2 class="font-display text-2xl font-semibold text-fg">{{ t('What is included') }}</h2>
                <ul class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                    <Reveal v-for="(feature, index) in service.features" :key="index" as="li" :delay="index * 0.04" class="flex gap-4 rounded-2xl border border-line bg-surface-raised p-5">
                        <Check :size="22" class="mt-0.5 shrink-0 text-brand-600" aria-hidden="true" />
                        <span>
                            <span class="block font-medium text-fg">{{ feature.title }}</span>
                            <span v-if="feature.description" class="mt-1 block text-muted">{{ feature.description }}</span>
                        </span>
                    </Reveal>
                </ul>
            </div>
        </div>
    </section>

    <section v-if="service.children?.length" class="section-y bg-surface">
        <div class="container-page">
            <SectionHeading :title="t('Services')" />
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Reveal v-for="(child, index) in service.children" :key="child.id" :delay="index * 0.06">
                    <Link :href="child.url" class="group flex h-full flex-col rounded-3xl border border-line bg-surface-raised p-7 transition hover:-translate-y-1 hover:border-brand-300">
                        <span class="grid size-12 place-items-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-200"><Icon :name="child.icon" :size="22" /></span>
                        <h3 class="mt-6 font-display text-xl font-semibold text-fg">{{ child.title }}</h3>
                        <p v-if="child.excerpt" class="mt-2 text-muted">{{ child.excerpt }}</p>
                        <ArrowUpRight :size="20" class="mt-auto self-end pt-4 text-brand-600 transition group-hover:rotate-45" aria-hidden="true" />
                    </Link>
                </Reveal>
            </div>
        </div>
    </section>

    <section v-if="service.process_steps?.length" class="section-y bg-ink-950 text-white">
        <div class="container-page">
            <SectionHeading :title="t('How we work')" dark />
            <ol class="mt-12 grid gap-4 md:grid-cols-2 lg:grid-cols-[repeat(auto-fit,minmax(14rem,1fr))]">
                <Reveal v-for="(step, index) in service.process_steps" :key="index" as="li" :delay="index * 0.08" class="rounded-3xl glass-dark p-7">
                    <span class="font-display text-sm font-semibold text-brand-300">{{ n(String(index + 1).padStart(2, '0')) }}</span>
                    <h3 class="mt-4 font-display text-xl font-semibold">{{ step.title }}</h3>
                    <p v-if="step.description" class="mt-2 text-white/65">{{ step.description }}</p>
                </Reveal>
            </ol>
        </div>
    </section>

    <DetailSections :projects="projects" :plans="plans" :faqs="faqs" placement="service" />
    <CtaBand :whatsapp-url="whatsappUrl" placement="service-cta" />
</template>
