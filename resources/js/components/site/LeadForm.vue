<script setup lang="ts">
import { CircleCheck, LoaderCircle } from '@lucide/vue';
import { onMounted, reactive, ref } from 'vue';
import WhatsAppButton from './WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import { useI18n } from '@/lib/i18n';
import { postJson, track } from '@/lib/tracking';
import type { LeadInput } from '@/types/models';

/**
 * Contact form. The lead is stored first; the success state then offers
 * WhatsApp so the conversation can continue there.
 */
const props = withDefaults(
    defineProps<{
        services?: { id: number; title: string }[];
        source?: LeadInput['source'];
        placement?: string;
        extra?: Partial<LeadInput>;
        dark?: boolean;
    }>(),
    { services: () => [], source: 'contact_form', placement: 'contact-form', dark: false },
);

const emit = defineEmits<{ submitted: [whatsappUrl: string, uuid: string | null] }>();
const { t, locale } = useI18n();

const form = reactive({ name: '', phone: '', email: '', message: '', service_id: '' as number | '', website: '' });
const errors = ref<Record<string, string>>({});
const state = ref<'idle' | 'sending' | 'sent' | 'error'>('idle');
const whatsappUrl = ref('');
let startedAt = 0;
let started = false;

onMounted(() => (startedAt = Date.now()));

function onFirstInput(): void {
    if (!started) {
        started = true;
        track('form_start', props.placement, locale.value);
    }
}

function validPhone(value: string): boolean {
    const digits = value.replace(/\D/g, '').replace(/^00880|^880/, '0');

    return /^0?1[3-9]\d{8}$/.test(digits);
}

async function submit(): Promise<void> {
    errors.value = {};

    if (form.name.trim().length < 2) errors.value.name = t('Please enter your name.');
    if (!validPhone(form.phone)) errors.value.phone = t('Enter a valid Bangladeshi mobile number, e.g. 01805019415.');
    if (Object.keys(errors.value).length) return;

    state.value = 'sending';

    const payload: LeadInput = {
        name: form.name.trim(),
        phone: form.phone.trim(),
        email: form.email.trim() || undefined,
        message: form.message.trim() || undefined,
        service_id: form.service_id || undefined,
        source: props.source,
        page_url: window.location.href,
        locale: locale.value,
        website: form.website,
        started_at: startedAt,
        ...props.extra,
    };

    try {
        const { ok, status, body } = await postJson<{ whatsapp_url?: string; lead?: string; errors?: Record<string, string[]>; message?: string }>(
            '/api/leads',
            payload,
        );

        if (status === 422 && body.errors) {
            errors.value = Object.fromEntries(Object.entries(body.errors).map(([key, messages]) => [key, messages[0]]));
            state.value = 'idle';
            return;
        }

        if (!ok) {
            state.value = 'error';
            return;
        }

        whatsappUrl.value = body.whatsapp_url ?? '';
        state.value = 'sent';
        track('form_submit', props.placement, locale.value);
        window.fbq?.('track', 'Lead', {}, { eventID: body.lead });
        window.dataLayer?.push({ event: 'generate_lead', placement: props.placement });
        emit('submitted', whatsappUrl.value, body.lead ?? null);
    } catch {
        state.value = 'error';
    }
}

const field = (dark: boolean) =>
    [
        'peer w-full rounded-2xl border px-4 pt-6 pb-2.5 outline-none transition placeholder-transparent focus:border-focus focus:ring-4 focus:ring-brand-600/30',
        dark ? 'border-white/12 bg-white/5 text-white' : 'border-line bg-surface text-fg',
    ].join(' ');
const label = (dark: boolean) =>
    [
        'pointer-events-none absolute top-2 left-4 text-xs font-medium transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:top-2 peer-focus:text-xs',
        dark ? 'text-white/70' : 'text-muted',
    ].join(' ');
</script>

<template>
    <div>
        <div v-if="state === 'sent'" class="py-6 text-center" role="status">
            <CircleCheck :size="48" class="mx-auto text-whatsapp" aria-hidden="true" />
            <h3 :class="['mt-4 font-display text-2xl font-semibold', dark ? 'text-white' : 'text-fg']">{{ t('Thank you, we got your message.') }}</h3>
            <p :class="['mx-auto mt-2 max-w-sm', dark ? 'text-white/65' : 'text-muted']">
                {{ t('We will contact you soon. For a faster reply, continue on WhatsApp.') }}
            </p>
            <div class="mt-6 flex justify-center">
                <WhatsAppButton :href="whatsappUrl || undefined" :placement="`${placement}-success`" :label="t('Continue on WhatsApp')" />
            </div>
        </div>

        <form v-else novalidate class="grid gap-4" @submit.prevent="submit" @input.once="onFirstInput">
            <!-- Spam trap: hidden from people, filled by bots. -->
            <div class="absolute -left-[9999px]" aria-hidden="true">
                <label>Website <input v-model="form.website" type="text" tabindex="-1" autocomplete="off" /></label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="relative">
                    <input id="lead-name" v-model="form.name" type="text" autocomplete="name" placeholder=" " required :class="field(dark)" :aria-invalid="!!errors.name" aria-describedby="lead-name-error" />
                    <label for="lead-name" :class="label(dark)">{{ t('Your name') }}</label>
                    <p v-if="errors.name" id="lead-name-error" class="mt-1.5 text-sm text-red-500" role="alert">{{ errors.name }}</p>
                </div>
                <div class="relative">
                    <input id="lead-phone" v-model="form.phone" type="tel" inputmode="tel" autocomplete="tel" placeholder=" " required :class="field(dark)" :aria-invalid="!!errors.phone" aria-describedby="lead-phone-error" />
                    <label for="lead-phone" :class="label(dark)">{{ t('Mobile number') }}</label>
                    <p v-if="errors.phone" id="lead-phone-error" class="mt-1.5 text-sm text-red-500" role="alert">{{ errors.phone }}</p>
                </div>
            </div>
            <div class="relative">
                <input id="lead-email" v-model="form.email" type="email" autocomplete="email" placeholder=" " :class="field(dark)" />
                <label for="lead-email" :class="label(dark)">{{ t('Email (optional)') }}</label>
                <p v-if="errors.email" class="mt-1.5 text-sm text-red-500" role="alert">{{ errors.email }}</p>
            </div>
            <div v-if="services.length" class="relative">
                <select id="lead-service" v-model="form.service_id" :class="[field(dark), 'appearance-none pt-6']">
                    <option value="">{{ t('Not sure yet') }}</option>
                    <option v-for="service in services" :key="service.id" :value="service.id">{{ service.title }}</option>
                </select>
                <label for="lead-service" :class="[label(dark), '!top-2 !text-xs']">{{ t('What do you need?') }}</label>
            </div>
            <div class="relative">
                <textarea id="lead-message" v-model="form.message" rows="4" placeholder=" " :class="[field(dark), 'resize-y']" />
                <label for="lead-message" :class="label(dark)">{{ t('Tell us about your business') }}</label>
            </div>

            <p v-if="state === 'error'" class="text-sm text-red-500" role="alert">{{ t('Something went wrong. Please try again or message us on WhatsApp.') }}</p>

            <Button type="submit" size="lg" :disabled="state === 'sending'" class="mt-1 justify-self-start">
                <LoaderCircle v-if="state === 'sending'" :size="18" class="animate-spin" aria-hidden="true" />
                {{ state === 'sending' ? t('Sending...') : t('Send message') }}
            </Button>
        </form>
    </div>
</template>
