<script setup lang="ts">
import { ArrowLeft, ArrowRight, Check } from '@lucide/vue';
import { AnimatePresence, motion } from 'motion-v';
import { computed, reactive, ref, watch } from 'vue';
import LeadForm from '@/components/site/LeadForm.vue';
import PageHero from '@/components/site/PageHero.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import Icon from '@/components/ui/Icon.vue';
import { useShared, whatsappUrl } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';
import { track } from '@/lib/tracking';
import type { PageIntro, Solution } from '@/types/models';

/**
 * Four-step project estimator. Answers are saved with the lead and summarised
 * in a pre-filled WhatsApp message.
 */
const props = defineProps<{ intro: PageIntro; services: { id: number; title: string }[]; solutions: Solution[] }>();

const { site } = useShared();
const { t, n, locale } = useI18n();
const STORAGE = 'aux-estimate';

const answers = reactive({ project: '', features: [] as string[], budget: '', timeline: '' });
const step = ref(0);
const done = ref(false);

try {
    Object.assign(answers, JSON.parse(sessionStorage.getItem(STORAGE) ?? '{}'));
} catch {
    // Storage may be blocked.
}
watch(answers, (value) => {
    try {
        sessionStorage.setItem(STORAGE, JSON.stringify(value));
    } catch {
        // Storage may be blocked.
    }
});

const chosenSolution = computed(() => props.solutions.find((s) => s.name === answers.project));
const featureOptions = computed(() => chosenSolution.value?.feature_groups?.flatMap((g) => g.features.map((f) => f.title)) ?? []);

const budgets = computed(() => [t('Under ৳50,000'), t('৳50,000 to ৳1,50,000'), t('৳1,50,000 to ৳5,00,000'), t('Above ৳5,00,000'), t('Not sure yet')]);
const timelines = computed(() => [t('As soon as possible'), t('Within a month'), t('In 1 to 3 months'), t('Just exploring')]);

const steps = computed(() => [t('Project'), t('Features'), t('Budget'), t('Your details')]);
const canNext = computed(() => [!!answers.project, true, !!answers.budget && !!answers.timeline, true][step.value]);

function next(): void {
    if (step.value === 1 && !featureOptions.value.length) {
        answers.features = [];
    }

    step.value = Math.min(step.value + 1, 3);
    track('estimator_step', `step-${step.value + 1}`, locale.value);
}

function toggle(feature: string): void {
    answers.features = answers.features.includes(feature) ? answers.features.filter((f) => f !== feature) : [...answers.features, feature];
}

const summary = computed(() =>
    [
        `${t('Project')}: ${answers.project}`,
        answers.features.length ? `${t('Features')}: ${answers.features.join(', ')}` : '',
        `${t('Budget')}: ${answers.budget}`,
        `${t('Timeline')}: ${answers.timeline}`,
    ]
        .filter(Boolean)
        .join('. '),
);
const waUrl = computed(() => whatsappUrl(site.value.whatsapp.number, site.value.whatsapp.templates.estimator, summary.value));

function finished(): void {
    done.value = true;

    try {
        sessionStorage.removeItem(STORAGE);
    } catch {
        // Storage may be blocked.
    }
}

const option = (selected: boolean) => [
    'flex min-h-14 w-full items-center gap-3 rounded-2xl border px-5 py-3 text-left transition',
    selected ? 'border-brand-600 bg-brand-50 text-fg ring-4 ring-brand-500/15 dark:bg-brand-950' : 'border-line bg-surface-raised text-fg/85 hover:border-brand-400',
];
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead" />

    <section class="bg-surface pb-24">
        <div class="container-page -mt-10 max-w-3xl">
            <div class="rounded-[2rem] border border-line bg-surface-raised p-6 shadow-[0_40px_80px_-50px_rgb(5_8_22/0.5)] sm:p-10">
                <!-- Progress -->
                <ol class="grid grid-cols-4 gap-2" :aria-label="t('Progress')">
                    <li v-for="(label, index) in steps" :key="label">
                        <div class="h-1.5 overflow-hidden rounded-full bg-surface-muted">
                            <motion.div class="h-full rounded-full bg-brand-600" :animate="{ width: index <= step || done ? '100%' : '0%' }" :transition="{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }" />
                        </div>
                        <p :class="['mt-2 hidden text-xs font-medium sm:block', index === step ? 'text-link' : 'text-muted']" :aria-current="index === step ? 'step' : undefined">
                            {{ n(index + 1) }}. {{ label }}
                        </p>
                    </li>
                </ol>

                <div v-if="done" class="py-10 text-center">
                    <span class="mx-auto grid size-16 place-items-center rounded-full bg-whatsapp/15 text-whatsapp-dark"><Check :size="32" aria-hidden="true" /></span>
                    <h2 class="mt-6 font-display text-3xl font-semibold text-fg">{{ t('Thanks! Your answers are with us.') }}</h2>
                    <p class="mx-auto mt-3 max-w-md text-muted">{{ t('Send them on WhatsApp now and we will reply with an estimate.') }}</p>
                    <div class="mt-8 flex justify-center"><WhatsAppButton :href="waUrl" placement="estimator-done" :label="t('Send on WhatsApp')" size="lg" /></div>
                </div>

                <AnimatePresence v-else mode="wait">
                    <motion.div
                        :key="step"
                        :initial="{ opacity: 0, x: 24 }"
                        :animate="{ opacity: 1, x: 0 }"
                        :exit="{ opacity: 0, x: -24 }"
                        :transition="{ duration: 0.35, ease: [0.16, 1, 0.3, 1] }"
                        class="mt-10"
                    >
                        <fieldset v-if="step === 0">
                            <legend class="font-display text-2xl font-semibold text-fg">{{ t('What do you want to build?') }}</legend>
                            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                <button v-for="solution in solutions" :key="solution.id" type="button" :class="option(answers.project === solution.name)" :aria-pressed="answers.project === solution.name" @click="answers.project = solution.name">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-brand-600 text-white"><Icon :name="solution.icon" :size="18" /></span>
                                    {{ solution.name }}
                                </button>
                                <button v-for="service in services" :key="`s${service.id}`" type="button" :class="option(answers.project === service.title)" :aria-pressed="answers.project === service.title" @click="answers.project = service.title">
                                    {{ service.title }}
                                </button>
                            </div>
                        </fieldset>

                        <fieldset v-else-if="step === 1">
                            <legend class="font-display text-2xl font-semibold text-fg">{{ t('Which features matter most?') }}</legend>
                            <p class="mt-2 text-muted">{{ featureOptions.length ? t('Choose as many as you like.') : t('Nothing to choose here for this project. Continue to the next step.') }}</p>
                            <div class="mt-6 flex flex-wrap gap-2">
                                <button
                                    v-for="feature in featureOptions"
                                    :key="feature"
                                    type="button"
                                    :aria-pressed="answers.features.includes(feature)"
                                    :class="[
                                        'inline-flex min-h-11 items-center gap-2 rounded-full border px-4 text-sm transition',
                                        answers.features.includes(feature) ? 'border-brand-600 bg-brand-600 text-white' : 'border-line text-fg/85 hover:border-brand-400',
                                    ]"
                                    @click="toggle(feature)"
                                >
                                    <Check v-if="answers.features.includes(feature)" :size="14" aria-hidden="true" />{{ feature }}
                                </button>
                            </div>
                        </fieldset>

                        <div v-else-if="step === 2" class="grid gap-10">
                            <fieldset>
                                <legend class="font-display text-2xl font-semibold text-fg">{{ t('Your budget') }}</legend>
                                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                    <button v-for="budget in budgets" :key="budget" type="button" :class="option(answers.budget === budget)" :aria-pressed="answers.budget === budget" @click="answers.budget = budget">{{ budget }}</button>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend class="font-display text-2xl font-semibold text-fg">{{ t('When do you need it?') }}</legend>
                                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                    <button v-for="timeline in timelines" :key="timeline" type="button" :class="option(answers.timeline === timeline)" :aria-pressed="answers.timeline === timeline" @click="answers.timeline = timeline">{{ timeline }}</button>
                                </div>
                            </fieldset>
                        </div>

                        <div v-else>
                            <h2 class="font-display text-2xl font-semibold text-fg">{{ t('Where should we send the estimate?') }}</h2>
                            <p class="mt-2 mb-8 rounded-2xl bg-surface-muted p-4 text-sm text-muted">{{ summary }}</p>
                            <LeadForm
                                source="estimator"
                                placement="estimator"
                                :extra="{ budget_range: answers.budget, meta: { project: answers.project, features: answers.features.join(', '), timeline: answers.timeline }, message: summary }"
                                @submitted="finished"
                            />
                        </div>
                    </motion.div>
                </AnimatePresence>

                <div v-if="!done" class="mt-10 flex items-center justify-between gap-3 border-t border-line pt-6">
                    <Button v-if="step > 0" variant="ghost" @click="step--"><ArrowLeft :size="18" aria-hidden="true" />{{ t('Back') }}</Button>
                    <span v-else />
                    <Button v-if="step < 3" :disabled="!canNext" @click="next">{{ t('Continue') }}<ArrowRight :size="18" aria-hidden="true" /></Button>
                </div>
            </div>
        </div>
    </section>
</template>
