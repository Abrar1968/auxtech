<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Quote, Star } from '@lucide/vue';
import Reveal from '@/components/ui/Reveal.vue';
import AppImage from '@/components/ui/AppImage.vue';
import { useI18n } from '@/lib/i18n';
import type { Testimonial } from '@/types/models';

defineProps<{ testimonials: Testimonial[] }>();
const { t } = useI18n();
</script>

<template>
    <div class="columns-1 gap-5 md:columns-2 lg:columns-3">
        <Reveal v-for="(item, index) in testimonials" :key="item.id" :delay="(index % 3) * 0.08" class="mb-5 break-inside-avoid">
            <figure class="rounded-3xl border border-line bg-surface-raised p-7">
                <Quote :size="28" class="text-brand-500" aria-hidden="true" />
                <div v-if="item.rating" class="mt-4 flex gap-0.5 text-amber-400" :aria-label="t(':count out of 5 stars', { count: item.rating })">
                    <Star v-for="i in item.rating" :key="i" :size="16" fill="currentColor" aria-hidden="true" />
                </div>
                <blockquote class="mt-4 text-lg leading-relaxed text-fg">{{ item.quote }}</blockquote>
                <figcaption class="mt-6 flex items-center gap-3">
                    <AppImage v-if="item.avatar" :image="item.avatar" sizes="48px" class="size-12 rounded-full" />
                    <span
                        v-else
                        class="grid size-12 place-items-center rounded-full bg-brand-50 font-display font-semibold text-brand-700 dark:bg-brand-950 dark:text-brand-200"
                    >
                        {{ item.author_name.charAt(0) }}
                    </span>
                    <span>
                        <span class="block font-medium text-fg">{{ item.author_name }}</span>
                        <span class="block text-sm text-muted">
                            {{ item.author_role }}
                            <template v-if="item.project_url">
                                · <Link :href="item.project_url" class="text-link hover:underline">{{ item.client ?? t('See the project') }}</Link>
                            </template>
                        </span>
                    </span>
                </figcaption>
            </figure>
        </Reveal>
    </div>
</template>
