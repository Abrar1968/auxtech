<script setup lang="ts">
import { computed } from 'vue';
import PageHero from '@/components/site/PageHero.vue';
import { useI18n } from '@/lib/i18n';

const props = defineProps<{ page: { title: string; body: string | null; template: string; updated_at: string | null } }>();
const { t, locale } = useI18n();

const updated = computed(() =>
    props.page.updated_at ? new Intl.DateTimeFormat(locale.value === 'bn' ? 'bn-BD' : 'en-GB', { dateStyle: 'long' }).format(new Date(props.page.updated_at)) : null,
);
</script>

<template>
    <PageHero :title="page.title" :lead="page.template === 'legal' && updated ? t('Last updated :date', { date: updated }) : null" />
    <section class="section-y bg-surface">
        <div class="container-page max-w-3xl"><div class="prose-aux" v-html="page.body" /></div>
    </section>
</template>
