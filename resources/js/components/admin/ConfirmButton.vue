<script setup lang="ts">
import { AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogOverlay, AlertDialogPortal, AlertDialogRoot, AlertDialogTitle, AlertDialogTrigger } from 'reka-ui';
import { button } from './ui';

/** A button that asks for confirmation before a destructive action. */
withDefaults(defineProps<{ title: string; description?: string; confirmLabel?: string; triggerClass?: string }>(), { confirmLabel: 'Delete' });
const emit = defineEmits<{ confirm: [] }>();
</script>

<template>
    <AlertDialogRoot>
        <AlertDialogTrigger :class="triggerClass ?? button.secondary"><slot /></AlertDialogTrigger>
        <AlertDialogPortal>
            <AlertDialogOverlay class="fixed inset-0 z-[80] bg-ink-950/50" />
            <AlertDialogContent class="fixed top-1/2 left-1/2 z-[90] w-[min(28rem,calc(100vw-2rem))] -translate-1/2 rounded-xl bg-white p-6 shadow-2xl">
                <AlertDialogTitle class="text-lg font-semibold text-[#232428]">{{ title }}</AlertDialogTitle>
                <AlertDialogDescription v-if="description" class="mt-2 text-sm text-[#5c5f66]">{{ description }}</AlertDialogDescription>
                <div class="mt-6 flex justify-end gap-2">
                    <AlertDialogCancel :class="button.secondary">Cancel</AlertDialogCancel>
                    <AlertDialogAction :class="button.danger" @click="emit('confirm')">{{ confirmLabel }}</AlertDialogAction>
                </div>
            </AlertDialogContent>
        </AlertDialogPortal>
    </AlertDialogRoot>
</template>
