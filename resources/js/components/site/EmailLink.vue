<script setup lang="ts">
import { Mail } from '@lucide/vue';
import { computed } from 'vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';
import { track } from '@/lib/tracking';

/** The secondary call to action: email, with a pre-filled subject. */
const props = withDefaults(defineProps<{ placement: string; subject?: string; dark?: boolean; showIcon?: boolean }>(), {
    showIcon: true,
});

const { site } = useShared();
const { t, locale } = useI18n();
const href = computed(() => `mailto:${site.value.email}?subject=${encodeURIComponent(props.subject ?? t('Project enquiry from the website'))}`);
</script>

<template>
    <a
        :href="href"
        :class="[
            'inline-flex min-h-11 items-center gap-2 font-medium underline-offset-4 transition hover:underline',
            dark ? 'text-white/80 hover:text-white' : 'text-fg/80 hover:text-link',
        ]"
        @click="track('email_click', placement, locale)"
    >
        <Mail v-if="showIcon" :size="18" aria-hidden="true" />
        <slot>{{ site.email }}</slot>
    </a>
</template>
