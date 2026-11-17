<script setup lang="ts">
import { computed } from 'vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import Button from '@/components/ui/Button.vue';
import { useI18n } from '@/lib/i18n';

const props = defineProps<{ status: number }>();
const { t, lp, n } = useI18n();

const message = computed(
    () =>
        ({
            404: [t('Page not found'), t('The page may have moved. These links will get you back on track.')],
            403: [t('Not allowed'), t('You do not have access to this page.')],
            503: [t('Back soon'), t('We are updating the site. Please try again in a few minutes.')],
        })[props.status] ?? [t('Something went wrong'), t('Please try again. If it keeps happening, tell us on WhatsApp.')],
);
</script>

<template>
    <section class="relative isolate grid min-h-[100svh] place-items-center overflow-hidden bg-ink-950 px-4 py-32 text-center text-white">
        <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid" />
        <div aria-hidden="true" class="absolute top-1/2 left-1/2 -z-10 size-[40rem] -translate-1/2 animate-aurora rounded-full bg-[radial-gradient(closest-side,rgb(1_69_255/0.45),transparent)] blur-2xl" />
        <div>
            <p class="font-display text-[7rem] leading-none font-semibold tracking-tight text-gradient sm:text-[10rem]">{{ n(status) }}</p>
            <h1 class="mt-4 font-display text-3xl font-semibold sm:text-4xl">{{ message[0] }}</h1>
            <p class="mx-auto mt-4 max-w-md text-white/65">{{ message[1] }}</p>
            <div class="mt-10 flex flex-wrap justify-center gap-3">
                <Button :href="lp('/')" variant="glass">{{ t('Go to the home page') }}</Button>
                <Button :href="lp('/work')" variant="glass">{{ t('See our work') }}</Button>
                <WhatsAppButton placement="error-page" />
            </div>
        </div>
    </section>
</template>
