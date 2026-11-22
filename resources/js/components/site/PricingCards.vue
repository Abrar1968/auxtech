<script setup lang="ts">
import { ArrowRight, Check } from '@lucide/vue';
import { Link } from '@inertiajs/vue3';
import WhatsAppButton from './WhatsAppButton.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { PricingPlan } from '@/types/models';

/**
 * Engagement packages. The highlighted plan is the dark card; a plan without a
 * price reads "Custom quote". Everything shown comes from Admin > Pricing.
 */
defineProps<{ plans: PricingPlan[]; placement?: string }>();

const { t, n, lp, locale } = useI18n();

function price(plan: PricingPlan): string {
    const amount = new Intl.NumberFormat(locale.value === 'bn' ? 'bn-BD' : 'en-IN', { maximumFractionDigits: 0 }).format(plan.price ?? 0);

    return plan.currency === 'BDT' ? `৳${amount}` : `${plan.currency} ${amount}`;
}

const number = (index: number) => n(String(index + 1).padStart(2, '0'));
</script>

<template>
    <div class="grid items-stretch gap-6 md:grid-cols-2 xl:grid-cols-[repeat(auto-fit,minmax(18rem,1fr))]">
        <Reveal v-for="(plan, index) in plans" :key="plan.id" :delay="index * 0.08" class="h-full">
            <article
                :class="[
                    'relative flex h-full flex-col overflow-hidden rounded-[1.75rem] border p-8 transition duration-500 hover:-translate-y-1',
                    plan.is_popular
                        ? 'border-ink-950 bg-ink-950 text-white shadow-[0_40px_80px_-40px_rgb(20_21_24/0.7)] dark:border-brand-600/40'
                        : 'border-line bg-surface-raised text-fg hover:shadow-[0_30px_60px_-40px_rgb(20_21_24/0.35)]',
                ]"
            >
                <span v-if="plan.is_popular" aria-hidden="true" class="absolute inset-x-0 top-0 h-1 bg-brand-600" />

                <div class="flex items-center justify-between gap-4">
                    <span :class="['font-mono text-sm', plan.is_popular ? 'text-brand-200' : 'text-link']">{{ number(index) }}</span>
                    <span v-if="plan.is_popular" class="rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold tracking-wide text-ink-950">{{ t('Most popular') }}</span>
                </div>

                <h3 class="mt-6 font-display text-2xl font-semibold tracking-tight">{{ plan.name }}</h3>
                <p v-if="plan.description" :class="['mt-3 leading-relaxed', plan.is_popular ? 'text-white/75' : 'text-muted']">{{ plan.description }}</p>

                <div :class="['mt-7 border-y py-5', plan.is_popular ? 'border-white/10' : 'border-line']">
                    <template v-if="plan.price !== null">
                        <p :class="['text-xs font-semibold tracking-[0.14em] uppercase', plan.is_popular ? 'text-white/65' : 'text-muted']">{{ t('Starting from') }}</p>
                        <p class="mt-1 font-display">
                            <span class="text-4xl font-semibold tracking-tight">{{ price(plan) }}</span>
                            <span v-if="plan.period" :class="['ml-1 text-sm', plan.is_popular ? 'text-white/65' : 'text-muted']">/ {{ plan.period }}</span>
                        </p>
                    </template>
                    <template v-else>
                        <p class="font-display text-2xl font-semibold">{{ t('Custom quote') }}</p>
                        <p :class="['mt-1 text-sm', plan.is_popular ? 'text-white/65' : 'text-muted']">{{ t('Fixed before work starts, after a short call.') }}</p>
                    </template>
                </div>

                <ul v-if="plan.features.length" class="mt-6 grid gap-3.5 text-[0.95rem]">
                    <li v-for="feature in plan.features" :key="feature" class="flex gap-3">
                        <span :class="['mt-0.5 grid size-5 shrink-0 place-items-center rounded-full', plan.is_popular ? 'bg-brand-600 text-ink-950' : 'bg-brand-600/20 text-link']">
                            <Check :size="13" :stroke-width="3" aria-hidden="true" />
                        </span>
                        <span :class="plan.is_popular ? 'text-white/85' : 'text-fg/85'">{{ feature }}</span>
                    </li>
                </ul>

                <div class="mt-auto grid gap-3 pt-9">
                    <WhatsAppButton :href="plan.whatsapp_url" :placement="placement ?? 'pricing-card'" :label="t('Ask about this package')" block />
                    <Link
                        :href="lp('/estimate')"
                        :class="['inline-flex items-center justify-center gap-1.5 text-sm font-medium transition', plan.is_popular ? 'text-white/75 hover:text-white' : 'text-muted hover:text-fg']"
                    >
                        {{ t('Get an estimate') }} <ArrowRight :size="15" aria-hidden="true" />
                    </Link>
                </div>
            </article>
        </Reveal>
    </div>
</template>
