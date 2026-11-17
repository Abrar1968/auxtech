import { onMounted, ref } from 'vue';

/**
 * Whether rich motion should run: honours prefers-reduced-motion and
 * switches off on low-end devices (docs/03 §6 guardrails).
 */
export function useRichMotion() {
    const enabled = ref(false);

    onMounted(() => {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const nav = navigator as Navigator & { deviceMemory?: number };
        const lowEnd = (nav.deviceMemory ?? 8) <= 4 || (navigator.hardwareConcurrency ?? 8) <= 4;

        enabled.value = !reduced && !lowEnd;
    });

    return enabled;
}

export function prefersReducedMotion(): boolean {
    return typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/** Entrance used by section headings and cards. */
export const fadeUp = {
    initial: { opacity: 0, y: 28 },
    whileInView: { opacity: 1, y: 0 },
    inViewOptions: { once: true, margin: '0px 0px -12% 0px' },
    transition: { duration: 0.7, ease: [0.16, 1, 0.3, 1] },
};
