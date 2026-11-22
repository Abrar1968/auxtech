<script setup lang="ts">
import { Plus } from '@lucide/vue';
import { AccordionContent, AccordionHeader, AccordionItem, AccordionRoot, AccordionTrigger } from 'reka-ui';
import type { Faq } from '@/types/models';

defineProps<{ faqs: Faq[] }>();
</script>

<template>
    <AccordionRoot type="single" collapsible class="divide-y divide-line border-y border-line">
        <AccordionItem v-for="faq in faqs" :key="faq.id" :value="String(faq.id)" class="group">
            <AccordionHeader as="h3">
                <AccordionTrigger
                    class="flex w-full items-center justify-between gap-6 py-6 text-left font-display text-lg font-medium text-fg transition hover:text-link"
                >
                    {{ faq.question }}
                    <span
                        class="grid size-9 shrink-0 place-items-center rounded-full border border-line transition duration-300 group-data-[state=open]:rotate-45 group-data-[state=open]:border-brand-600 group-data-[state=open]:bg-brand-600 group-data-[state=open]:text-ink-950"
                    >
                        <Plus :size="18" aria-hidden="true" />
                    </span>
                </AccordionTrigger>
            </AccordionHeader>
            <AccordionContent
                class="overflow-hidden data-[state=closed]:animate-[collapse_0.3s_ease-out] data-[state=open]:animate-[expand_0.35s_cubic-bezier(0.16,1,0.3,1)]"
            >
                <!-- Answers are sanitised on save in the admin panel. -->
                <div class="prose-aux max-w-3xl pb-7 text-muted" v-html="faq.answer" />
            </AccordionContent>
        </AccordionItem>
    </AccordionRoot>
</template>

<style>
@keyframes expand {
    from {
        height: 0;
        opacity: 0;
    }
    to {
        height: var(--reka-accordion-content-height);
        opacity: 1;
    }
}
@keyframes collapse {
    from {
        height: var(--reka-accordion-content-height);
    }
    to {
        height: 0;
    }
}
</style>
