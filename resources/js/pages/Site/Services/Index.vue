<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, ArrowUpRight } from '@lucide/vue';
import CtaBand from '@/components/site/CtaBand.vue';
import PageHero from '@/components/site/PageHero.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Icon from '@/components/ui/Icon.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { PageIntro, Service } from '@/types/models';

defineProps<{ intro: PageIntro; services: Service[] }>();
const { t } = useI18n();
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead">
        <WhatsAppButton placement="services-hero" />
    </PageHero>

    <section class="section-y bg-surface">
        <div class="container-page grid gap-6">
            <Reveal v-for="(service, index) in services" :key="service.id" :delay="0.05">
                <article class="group grid gap-8 rounded-[2rem] border border-line bg-surface-raised p-8 transition duration-500 hover:border-brand-300 hover:shadow-[0_30px_70px_-40px_rgb(255_201_60/0.5)] sm:p-10 lg:grid-cols-[auto_minmax(0,1fr)_minmax(0,1fr)] lg:items-start">
                    <span class="font-display text-sm font-semibold text-muted">0{{ index + 1 }}</span>
                    <div>
                        <span class="grid size-14 place-items-center rounded-2xl bg-brand-600 text-ink-950 shadow-[0_12px_30px_-10px_rgb(255_201_60)]">
                            <Icon :name="service.icon" :size="26" />
                        </span>
                        <h2 class="mt-6 font-display text-3xl font-semibold text-fg sm:text-4xl">
                            <Link :href="service.url" class="hover:text-link">{{ service.title }}</Link>
                        </h2>
                        <p v-if="service.excerpt" class="mt-4 max-w-lg text-lg leading-relaxed text-muted">{{ service.excerpt }}</p>
                        <Link :href="service.url" class="mt-6 inline-flex items-center gap-2 font-medium text-link">
                            {{ t('Learn more') }} <ArrowRight :size="18" class="transition group-hover:translate-x-1" aria-hidden="true" />
                        </Link>
                    </div>
                    <ul v-if="service.children?.length" class="grid gap-2 self-center">
                        <li v-for="child in service.children" :key="child.id">
                            <Link :href="child.url" class="flex items-center justify-between gap-4 rounded-2xl border border-line px-5 py-4 transition hover:border-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-600/10">
                                <span>
                                    <span class="block font-medium text-fg">{{ child.title }}</span>
                                    <span v-if="child.excerpt" class="mt-0.5 block text-sm text-muted">{{ child.excerpt }}</span>
                                </span>
                                <ArrowUpRight :size="18" class="shrink-0 text-link" aria-hidden="true" />
                            </Link>
                        </li>
                    </ul>
                </article>
            </Reveal>
        </div>
    </section>

    <CtaBand placement="services-cta" />
</template>
