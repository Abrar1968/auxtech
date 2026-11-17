<script setup lang="ts">
import { Check } from '@lucide/vue';
import WhatsAppButton from './WhatsAppButton.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { PricingPlan } from '@/types/models';

defineProps<{ plans: PricingPlan[]; placement?: string }>();

const { t, n, locale } = useI18n();

function price(plan: PricingPlan): string {
    const amount = new Intl.NumberFormat(locale.value === 'bn' ? 'bn-BD' : 'en-IN', { maximumFractionDigits: 0 }).format(plan.price ?? 0);

    return plan.currency === 'BDT' ? `৳${amount}` : `${plan.currency} ${amount}`;
}
</script>

<template>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-[repeat(auto-fit,minmax(16rem,1fr))]">
        <Reveal v-for="(plan, index) in plans" :key="plan.id" :delay="index * 0.08" class="h-full">
            <div
                :class="[
                    'relative flex h-full flex-col rounded-3xl border p-7 transition duration-500 hover:-translate-y-1',
                    plan.is_popular
                        ? 'border-brand-500 bg-ink-950 text-white shadow-[0_30px_70px_-30px_rgb(1_69_255/0.8)]'
                        : 'border-line bg-surface-raised text-fg',
                ]"
            >
                <span
                    v-if="plan.is_popular"
                    class="absolute -top-3 left-7 rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold tracking-wide text-white"
                >
                    {{ t('Most popular') }}
                </span>
                <h3 class="font-display text-xl font-semibold">{{ plan.name }}</h3>
                <p v-if="plan.description" :class="['mt-2 text-sm', plan.is_popular ? 'text-white/65' : 'text-muted']">{{ plan.description }}</p>
                <p class="mt-6 font-display">
                    <template v-if="plan.price !== null">
                        <span class="text-4xl font-semibold tracking-tight">{{ price(plan) }}</span>
                        <span v-if="plan.period" :class="['ml-1 text-sm', plan.is_popular ? 'text-white/60' : 'text-muted']">/ {{ plan.period }}</span>
                    </template>
                    <span v-else class="text-2xl font-semibold">{{ t('Custom quote') }}</span>
                </p>
                <ul v-if="plan.features.length" class="mt-6 grid gap-3 text-[0.95rem]">
                    <li v-for="feature in plan.features" :key="feature" class="flex gap-3">
                        <Check :size="18" :class="['mt-0.5 shrink-0', plan.is_popular ? 'text-whatsapp' : 'text-link']" aria-hidden="true" />
                        {{ feature }}
                    </li>
                </ul>
                <div class="mt-auto pt-8">
                    <WhatsAppButton :href="plan.whatsapp_url" :placement="placement ?? 'pricing-card'" :label="t('Ask about this package')" block />
                </div>
                <span class="sr-only">{{ n(index + 1) }}</span>
            </div>
        </Reveal>
    </div>
</template>
