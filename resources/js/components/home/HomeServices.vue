<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight } from '@lucide/vue';
import Icon from '@/components/ui/Icon.vue';
import Reveal from '@/components/ui/Reveal.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import type { SectionCopy, Service } from '@/types/models';

/** Bento grid of services; the first one gets the large tile. */
defineProps<{ services: Service[]; copy?: SectionCopy; eyebrow?: string }>();
</script>

<template>
    <section class="section-y bg-surface">
        <div class="container-page">
            <SectionHeading :eyebrow="eyebrow" :title="copy?.title" :lead="copy?.lead" />
            <div class="mt-14 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Reveal
                    v-for="(service, index) in services"
                    :key="service.id"
                    :delay="index * 0.07"
                    :class="index === 0 && services.length > 2 ? 'md:col-span-2 lg:row-span-2' : ''"
                >
                    <Link
                        :href="service.url"
                        prefetch="hover"
                        :class="[
                            'group relative flex h-full flex-col overflow-hidden rounded-3xl border p-7 transition duration-500 ease-out-expo hover:-translate-y-1 sm:p-8',
                            index === 0
                                ? 'border-transparent bg-ink-950 text-white hover:shadow-[0_40px_80px_-40px_rgb(255_201_60/0.9)] dark:border-white/10 dark:bg-ink-800'
                                : 'border-line bg-surface-raised text-fg hover:border-brand-300 hover:shadow-[0_30px_60px_-30px_rgb(255_201_60/0.35)]',
                        ]"
                    >
                        <div
                            v-if="index === 0"
                            aria-hidden="true"
                            class="absolute -right-24 -bottom-24 size-80 rounded-full bg-brand-600/15 blur-3xl transition duration-700 group-hover:scale-125"
                        />
                        <span
                            :class="[
                                'relative grid size-13 place-items-center rounded-2xl transition duration-500 group-hover:scale-110',
                                index === 0 ? 'bg-brand-600 text-ink-950' : 'bg-brand-50 text-link dark:bg-brand-600/15 dark:text-brand-200',
                            ]"
                        >
                            <Icon :name="service.icon" :size="24" />
                        </span>
                        <h3 :class="['relative mt-8 font-display font-semibold', index === 0 ? 'text-3xl sm:text-4xl' : 'text-xl']">{{ service.title }}</h3>
                        <p v-if="service.excerpt" :class="['relative mt-3 leading-relaxed', index === 0 ? 'max-w-md text-lg text-white/70' : 'text-muted']">
                            {{ service.excerpt }}
                        </p>
                        <ul v-if="index === 0 && service.children?.length" class="relative mt-8 flex flex-wrap gap-2">
                            <li v-for="child in service.children" :key="child.id" class="rounded-full border border-white/15 px-3.5 py-1.5 text-sm text-white/75">{{ child.title }}</li>
                        </ul>
                        <ArrowUpRight
                            :size="22"
                            :class="['relative mt-auto self-end pt-6 transition duration-300 group-hover:translate-x-1 group-hover:-translate-y-1', index === 0 ? 'text-white' : 'text-link']"
                            aria-hidden="true"
                        />
                    </Link>
                </Reveal>
            </div>
        </div>
    </section>
</template>
