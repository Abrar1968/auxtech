<script setup lang="ts">
import { AnimatePresence, motion } from 'motion-v';
import { computed, ref } from 'vue';
import AppImage from '@/components/ui/AppImage.vue';
import Icon from '@/components/ui/Icon.vue';
import { useI18n } from '@/lib/i18n';
import type { SolutionFeatureGroup } from '@/types/models';

/**
 * The Auxtech Ecommerce modules, grouped (Sell / Fulfil / Grow / Control).
 * Choosing a module brings up its screenshot (Admin > Features) in a browser
 * window, or a drawn admin screen when no screenshot has been uploaded.
 */
const props = defineProps<{ groups: SolutionFeatureGroup[] }>();
const { t, n } = useI18n();

const activeGroup = ref(props.groups[0]?.key ?? '');
const group = computed(() => props.groups.find((g) => g.key === activeGroup.value) ?? props.groups[0]);
const activeFeature = ref(group.value?.features[0]?.slug ?? '');
const feature = computed(() => group.value?.features.find((f) => f.slug === activeFeature.value) ?? group.value?.features[0]);
const total = computed(() => props.groups.reduce((sum, g) => sum + g.features.length, 0));

function selectGroup(key: string): void {
    activeGroup.value = key;
    activeFeature.value = props.groups.find((g) => g.key === key)?.features[0]?.slug ?? '';
}
</script>

<template>
    <div>
        <div role="tablist" :aria-label="t('Module groups')" class="flex flex-wrap gap-2">
            <button
                v-for="item in groups"
                :key="item.key"
                role="tab"
                :aria-selected="item.key === activeGroup"
                :class="[
                    'relative min-h-11 rounded-full px-5 text-sm font-medium transition',
                    item.key === activeGroup ? 'text-ink-950' : 'text-white/65 hover:text-white',
                ]"
                @click="selectGroup(item.key)"
            >
                <motion.span
                    v-if="item.key === activeGroup"
                    layout-id="module-tab"
                    class="absolute inset-0 rounded-full bg-brand-600 shadow-[0_8px_30px_-8px_rgb(255_201_60/0.9)]"
                    :transition="{ type: 'spring', stiffness: 380, damping: 32 }"
                />
                <span class="relative">{{ item.name }} <span :class="item.key === activeGroup ? 'text-ink-950/70' : 'text-white/60'">{{ n(item.features.length) }}</span></span>
            </button>
            <span class="ml-auto hidden self-center text-sm text-white/60 sm:block">{{ t(':count modules in one admin panel', { count: n(total) }) }}</span>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
            <div role="tabpanel">
<ul class="grid content-start gap-1.5">
                <li v-for="item in group?.features ?? []" :key="item.slug">
                    <button
                        :class="[
                            'flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left transition',
                            item.slug === feature?.slug ? 'glass-dark text-white' : 'text-white/60 hover:bg-white/5 hover:text-white',
                        ]"
                        :aria-pressed="item.slug === feature?.slug"
                        @click="activeFeature = item.slug"
                        @mouseenter="activeFeature = item.slug"
                    >
                        <span
                            :class="[
                                'grid size-9 shrink-0 place-items-center rounded-xl transition',
                                item.slug === feature?.slug ? 'bg-brand-600 text-ink-950' : 'bg-white/5 text-brand-200',
                            ]"
                        >
                            <Icon :name="item.icon" :size="18" />
                        </span>
                        <span class="font-medium">{{ item.title }}</span>
                    </button>
                </li>
            </ul>
</div>

            <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-ink-900 p-2 shadow-[0_40px_100px_-40px_rgb(255_201_60/0.6)]">
                <div class="flex items-center gap-2 px-3 py-2">
                    <span class="size-2.5 rounded-full bg-white/15" /><span class="size-2.5 rounded-full bg-white/15" /><span class="size-2.5 rounded-full bg-white/15" />
                    <span class="ml-3 truncate text-xs text-white/65">{{ group?.name }} · {{ feature?.title }}</span>
                </div>
                <AnimatePresence mode="wait">
                    <motion.div
                        v-if="feature?.image"
                        :key="`image-${feature.slug}`"
                        :initial="{ opacity: 0, scale: 0.985 }"
                        :animate="{ opacity: 1, scale: 1 }"
                        :exit="{ opacity: 0 }"
                        :transition="{ duration: 0.35, ease: [0.16, 1, 0.3, 1] }"
                        class="relative overflow-hidden rounded-2xl bg-ink-950"
                    >
                        <AppImage :image="feature.image" :alt="feature.image.alt || feature.title" sizes="(min-width: 1024px) 50vw, 100vw" class="aspect-[16/10] w-full object-top" />
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink-950 via-ink-950/90 to-transparent p-5 pt-16 sm:p-7 sm:pt-20">
                            <div class="flex items-start gap-4">
                                <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-brand-600 text-ink-950"><Icon :name="feature.icon" :size="20" /></span>
                                <div class="min-w-0">
                                    <h3 class="font-display text-xl font-semibold text-white sm:text-2xl">{{ feature.title }}</h3>
                                    <p v-if="feature.description" class="mt-1 line-clamp-2 text-sm text-white/75">{{ feature.description }}</p>
                                </div>
                            </div>
                        </div>
                    </motion.div>
                    <motion.div
                        v-else-if="feature"
                        :key="feature.slug"
                        :initial="{ opacity: 0, y: 16, filter: 'blur(6px)' }"
                        :animate="{ opacity: 1, y: 0, filter: 'blur(0px)' }"
                        :exit="{ opacity: 0, y: -12, filter: 'blur(6px)' }"
                        :transition="{ duration: 0.35, ease: [0.16, 1, 0.3, 1] }"
                        class="relative min-h-[22rem] overflow-hidden rounded-2xl bg-gradient-to-br from-ink-800 to-ink-950 p-7 sm:p-9"
                    >
                        <div aria-hidden="true" class="absolute -top-24 -right-24 size-72 rounded-full bg-brand-600/15 blur-3xl" />
                        <span class="relative grid size-14 place-items-center rounded-2xl bg-brand-600 text-ink-950 shadow-[0_10px_40px_-10px_rgb(255_201_60)]">
                            <Icon :name="feature.icon" :size="26" />
                        </span>
                        <h3 class="relative mt-6 font-display text-2xl font-semibold text-white sm:text-3xl">{{ feature.title }}</h3>
                        <p v-if="feature.description" class="relative mt-3 max-w-lg text-white/65">{{ feature.description }}</p>
                        <p v-else-if="group?.description" class="relative mt-3 max-w-lg text-white/65">{{ group.description }}</p>

                        <!-- Drawn admin screen until a screenshot is uploaded. -->
                        <div aria-hidden="true" class="relative mt-8 grid gap-3">
                            <div v-for="row in 3" :key="row" class="flex items-center gap-3 rounded-xl border border-white/8 bg-white/[0.03] px-4 py-3">
                                <span class="size-7 rounded-lg bg-brand-500/25" />
                                <span class="h-2.5 rounded-full bg-white/15" :style="{ width: `${70 - row * 14}%` }" />
                                <span class="ml-auto h-6 w-16 rounded-full" :class="row === 1 ? 'bg-whatsapp/25' : 'bg-white/8'" />
                            </div>
                        </div>
                    </motion.div>
                </AnimatePresence>
            </div>
        </div>
    </div>
</template>
