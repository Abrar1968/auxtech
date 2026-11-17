<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, ArrowRight } from '@lucide/vue';
import CtaBand from '@/components/site/CtaBand.vue';
import PageHero from '@/components/site/PageHero.vue';
import PostCard from '@/components/site/PostCard.vue';
import Button from '@/components/ui/Button.vue';
import Reveal from '@/components/ui/Reveal.vue';
import { useI18n } from '@/lib/i18n';
import type { PageIntro, Post } from '@/types/models';

defineProps<{
    intro: PageIntro;
    posts: Post[];
    pagination: { current: number; last: number; prev: string | null; next: string | null };
    categories: { name: string; url: string }[];
}>();
const { t, lp } = useI18n();
</script>

<template>
    <PageHero :title="intro.heading" :lead="intro.lead">
        <nav v-if="categories.length" :aria-label="t('Categories')" class="flex flex-wrap gap-2">
            <Link :href="lp('/blog')" class="rounded-full glass-dark px-4 py-2 text-sm text-white/80 hover:text-white">{{ t('All') }}</Link>
            <Link v-for="category in categories" :key="category.url" :href="category.url" class="rounded-full glass-dark px-4 py-2 text-sm text-white/80 hover:text-white">{{ category.name }}</Link>
        </nav>
    </PageHero>

    <section class="section-y bg-surface">
        <div class="container-page">
            <div v-if="posts.length" class="grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
                <Reveal v-for="(post, index) in posts" :key="post.id" :delay="(index % 3) * 0.07" :class="index === 0 && pagination.current === 1 ? 'sm:col-span-2' : ''">
                    <PostCard :post="post" />
                </Reveal>
            </div>
            <p v-else class="py-20 text-center text-lg text-muted">{{ t('Articles are on the way.') }}</p>

            <nav v-if="pagination.last > 1" class="mt-16 flex justify-between" :aria-label="t('Pagination')">
                <Button v-if="pagination.prev" :href="pagination.prev" variant="secondary"><ArrowLeft :size="18" aria-hidden="true" />{{ t('Newer') }}</Button>
                <span v-else />
                <Button v-if="pagination.next" :href="pagination.next" variant="secondary">{{ t('Older') }}<ArrowRight :size="18" aria-hidden="true" /></Button>
            </nav>
        </div>
    </section>
    <CtaBand placement="blog-cta" />
</template>
