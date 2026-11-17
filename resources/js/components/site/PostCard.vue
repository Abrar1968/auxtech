<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { FileText } from '@lucide/vue';
import AppImage from '@/components/ui/AppImage.vue';
import { useI18n } from '@/lib/i18n';
import type { Post } from '@/types/models';

defineProps<{ post: Post }>();
const { t, n, locale } = useI18n();

function date(value: string | null): string {
    return value ? new Intl.DateTimeFormat(locale.value === 'bn' ? 'bn-BD' : 'en-GB', { dateStyle: 'medium' }).format(new Date(value)) : '';
}
</script>

<template>
    <article class="group">
        <Link :href="post.url" class="block" prefetch="hover">
            <div class="relative aspect-[16/10] overflow-hidden rounded-2xl bg-surface-muted">
                <AppImage
                    v-if="post.cover"
                    :image="post.cover"
                    sizes="(min-width: 1024px) 33vw, 90vw"
                    class="size-full transition duration-700 ease-out-expo group-hover:scale-105"
                />
                <div v-else class="grid size-full place-items-center bg-gradient-to-br from-brand-600 to-ink-900 text-white/70">
                    <FileText :size="40" aria-hidden="true" />
                </div>
            </div>
            <div class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-muted">
                <span v-if="post.category" class="font-medium text-brand-600">{{ post.category.name }}</span>
                <time v-if="post.published_at" :datetime="post.published_at">{{ date(post.published_at) }}</time>
                <span>{{ t(':count min read', { count: n(post.reading_minutes) }) }}</span>
            </div>
            <h3 class="mt-2 font-display text-xl leading-snug font-semibold text-fg transition group-hover:text-brand-600">{{ post.title }}</h3>
            <p v-if="post.excerpt" class="mt-2 line-clamp-2 text-muted">{{ post.excerpt }}</p>
        </Link>
    </article>
</template>
