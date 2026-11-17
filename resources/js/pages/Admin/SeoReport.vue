<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CircleCheck, TriangleAlert } from '@lucide/vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import { card } from '@/components/admin/ui';

defineProps<{ issues: { module: string; id: number; title: string | null; problems: string[] }[] }>();
</script>

<template>
    <PageHeader title="SEO report" description="Published pages that are missing search information." />
    <div :class="card">
        <ul>
            <li v-for="issue in issues" :key="`${issue.module}-${issue.id}`" class="border-b border-[#f0f2f7] last:border-0">
                <Link :href="`/admin/${issue.module}/${issue.id}/edit`" class="flex flex-wrap items-center gap-3 px-5 py-3 text-sm hover:bg-[#fafbfd]">
                    <TriangleAlert :size="16" class="text-amber-600" />
                    <span class="font-medium">{{ issue.title }}</span>
                    <span class="text-xs text-[#9aa6c1] capitalize">{{ issue.module.replaceAll('-', ' ') }}</span>
                    <span class="ml-auto text-[#55607a]">{{ issue.problems.join(' · ') }}</span>
                </Link>
            </li>
            <li v-if="!issues.length" class="flex items-center justify-center gap-2 px-5 py-12 text-sm text-green-700"><CircleCheck :size="18" />Every published page has a description and image alt text.</li>
        </ul>
    </div>
</template>
