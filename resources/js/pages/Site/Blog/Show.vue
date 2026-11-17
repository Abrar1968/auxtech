<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useScroll } from 'motion-v';
import { motion } from 'motion-v';
import { computed, nextTick, onMounted, ref } from 'vue';
import CtaBand from '@/components/site/CtaBand.vue';
import PostCard from '@/components/site/PostCard.vue';
import WhatsAppButton from '@/components/site/WhatsAppButton.vue';
import AppImage from '@/components/ui/AppImage.vue';
import { useShared } from '@/composables/useSite';
import { useI18n } from '@/lib/i18n';
import type { Post } from '@/types/models';

const props = defineProps<{ post: Post; related: Post[]; whatsappUrl: string }>();
const { seo } = useShared();
const { t, n, locale } = useI18n();
const { scrollYProgress } = useScroll();

const article = ref<HTMLElement>();
const toc = ref<{ id: string; text: string }[]>([]);

/** Table of contents from the article's H2 headings. */
onMounted(async () => {
    await nextTick();
    const headings = Array.from(article.value?.querySelectorAll('h2') ?? []);

    toc.value = headings.map((heading, index) => {
        heading.id ||= `section-${index + 1}`;

        return { id: heading.id, text: heading.textContent ?? '' };
    });
});

const date = computed(() =>
    props.post.published_at ? new Intl.DateTimeFormat(locale.value === 'bn' ? 'bn-BD' : 'en-GB', { dateStyle: 'long' }).format(new Date(props.post.published_at)) : '',
);
const crumbs = computed(() => seo.value.breadcrumbs.slice(0, -1));
</script>

<template>
    <motion.div class="fixed inset-x-0 top-0 z-[60] h-0.5 origin-left bg-brand-500" :style="{ scaleX: scrollYProgress }" aria-hidden="true" />

    <article>
        <header class="relative isolate overflow-hidden bg-ink-950 pt-36 pb-16 text-white sm:pt-44">
            <div aria-hidden="true" class="absolute inset-0 -z-10 bg-grid" />
            <div aria-hidden="true" class="absolute -top-40 left-1/2 -z-10 h-[30rem] w-[50rem] -translate-x-1/2 rounded-full bg-[radial-gradient(closest-side,rgb(1_69_255/0.5),transparent)] blur-2xl" />
            <div class="container-page max-w-3xl">
                <nav aria-label="Breadcrumb" class="mb-6 flex flex-wrap gap-2 text-sm text-white/50">
                    <template v-for="(crumb, index) in crumbs" :key="crumb.url">
                        <span v-if="index > 0">/</span>
                        <Link :href="crumb.url" class="hover:text-white">{{ crumb.name }}</Link>
                    </template>
                </nav>
                <Link v-if="post.category" :href="post.category.url" class="text-sm font-semibold tracking-wider text-brand-300 uppercase">{{ post.category.name }}</Link>
                <h1 class="mt-3 font-display text-4xl leading-tight font-semibold tracking-tight sm:text-5xl">{{ post.title }}</h1>
                <p v-if="post.excerpt" class="mt-5 text-lg text-white/70">{{ post.excerpt }}</p>
                <p class="mt-8 flex flex-wrap gap-x-4 gap-y-1 text-sm text-white/55">
                    <span v-if="post.author">{{ post.author }}</span>
                    <time v-if="post.published_at" :datetime="post.published_at">{{ date }}</time>
                    <span>{{ t(':count min read', { count: n(post.reading_minutes) }) }}</span>
                </p>
            </div>
        </header>

        <div v-if="post.cover" class="bg-ink-950">
            <div class="container-page max-w-5xl translate-y-10">
                <AppImage :image="post.cover" priority sizes="(min-width: 1024px) 1024px, 95vw" class="aspect-[16/9] w-full rounded-3xl shadow-2xl" />
            </div>
        </div>

        <div class="bg-surface pt-20 pb-20">
            <div class="container-page grid max-w-6xl gap-12 lg:grid-cols-[minmax(0,1fr)_16rem]">
                <div ref="article" class="prose-aux max-w-3xl" v-html="post.body" />
                <aside class="hidden lg:block">
                    <div class="sticky top-28 grid gap-6">
                        <nav v-if="toc.length > 1" :aria-label="t('On this page')">
                            <p class="text-sm font-semibold text-fg">{{ t('On this page') }}</p>
                            <ul class="mt-3 grid gap-2 border-l border-line text-sm">
                                <li v-for="item in toc" :key="item.id"><a :href="`#${item.id}`" class="-ml-px block border-l border-transparent pl-4 text-muted transition hover:border-brand-500 hover:text-fg">{{ item.text }}</a></li>
                            </ul>
                        </nav>
                        <div class="rounded-3xl bg-ink-950 p-6 text-white">
                            <p class="font-display text-lg font-semibold">{{ t('Need help with this?') }}</p>
                            <p class="mt-2 text-sm text-white/65">{{ t('Ask the team that wrote it.') }}</p>
                            <div class="mt-4"><WhatsAppButton :href="whatsappUrl" placement="blog-aside" size="sm" block /></div>
                        </div>
                    </div>
                </aside>
            </div>

            <div v-if="post.tags?.length" class="container-page mt-12 max-w-6xl">
                <ul class="flex flex-wrap gap-2">
                    <li v-for="tag in post.tags" :key="tag.url"><Link :href="tag.url" class="rounded-full border border-line px-3 py-1.5 text-sm text-muted hover:text-brand-600">#{{ tag.name }}</Link></li>
                </ul>
            </div>
        </div>
    </article>

    <section v-if="related.length" class="section-y border-t border-line bg-surface">
        <div class="container-page">
            <h2 class="font-display text-3xl font-semibold text-fg">{{ t('Related articles') }}</h2>
            <div class="mt-10 grid gap-10 md:grid-cols-3"><PostCard v-for="item in related" :key="item.id" :post="item" /></div>
        </div>
    </section>
    <CtaBand :whatsapp-url="whatsappUrl" placement="blog-cta" />
</template>
