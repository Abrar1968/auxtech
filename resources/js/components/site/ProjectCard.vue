<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight } from '@lucide/vue';
import DeviceFrame from './DeviceFrame.vue';
import { useI18n } from '@/lib/i18n';
import type { Project } from '@/types/models';

/** A project in a grid or carousel: screenshot, client, industry and one line. */
withDefaults(defineProps<{ project: Project; dark?: boolean; priority?: boolean }>(), { dark: false });

const { t } = useI18n();
</script>

<template>
    <article class="group relative">
        <Link :href="project.url" class="block rounded-2xl focus-visible:outline-offset-4" prefetch="hover">
            <div class="transition duration-500 ease-out-expo group-hover:-translate-y-1.5">
                <DeviceFrame :image="project.cover" :url="project.live_url" :dark="dark" :priority="priority" sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 90vw" />
            </div>
            <div class="mt-5 flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                        <span v-if="project.industry" :class="dark ? 'text-brand-200' : 'text-link'">{{ project.industry.name }}</span>
                        <span
                            v-if="project.is_demo"
                            :class="['rounded-full px-2 py-0.5', dark ? 'bg-white/10 text-white/70' : 'bg-surface-muted text-muted']"
                        >
                            {{ t('Demo') }}
                        </span>
                    </div>
                    <h3 :class="['mt-1.5 font-display text-xl font-semibold', dark ? 'text-white' : 'text-fg']">{{ project.title }}</h3>
                    <p v-if="project.tagline" :class="['mt-1.5 line-clamp-2 text-[0.95rem]', dark ? 'text-white/60' : 'text-muted']">
                        {{ project.tagline }}
                    </p>
                </div>
                <span
                    :class="[
                        'mt-1 grid size-10 shrink-0 place-items-center rounded-full border transition duration-300 group-hover:rotate-45 group-hover:bg-brand-600 group-hover:text-ink-950',
                        dark ? 'border-white/15 text-white' : 'border-line text-fg',
                    ]"
                >
                    <ArrowUpRight :size="18" aria-hidden="true" />
                </span>
            </div>
        </Link>
    </article>
</template>
