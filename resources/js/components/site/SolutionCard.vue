<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight } from '@lucide/vue';
import Icon from '@/components/ui/Icon.vue';
import { useI18n } from '@/lib/i18n';
import type { Solution } from '@/types/models';

defineProps<{ solution: Solution }>();
const { tc } = useI18n();
</script>

<template>
    <Link
        :href="solution.url"
        prefetch="hover"
        class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-line bg-surface-raised p-7 transition duration-500 ease-out-expo hover:-translate-y-1 hover:border-brand-300 hover:shadow-[0_30px_60px_-30px_rgb(1_69_255/0.4)]"
    >
        <div class="flex items-start justify-between">
            <span class="grid size-12 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-[0_10px_30px_-10px_rgb(1_69_255)]">
                <Icon :name="solution.icon" :size="22" />
            </span>
            <ArrowUpRight :size="20" class="text-muted transition group-hover:rotate-45 group-hover:text-brand-600" aria-hidden="true" />
        </div>
        <h3 class="mt-6 font-display text-xl font-semibold text-fg">{{ solution.name }}</h3>
        <p v-if="solution.summary" class="mt-2 leading-relaxed text-muted">{{ solution.summary }}</p>
        <p v-if="solution.projects_count" class="mt-auto pt-6 text-sm font-medium text-brand-600">
            {{ tc(':count project', ':count projects', solution.projects_count) }}
        </p>
    </Link>
</template>
