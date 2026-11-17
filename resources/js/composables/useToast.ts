import { reactive } from 'vue';

export interface Toast {
    id: number;
    kind: 'success' | 'error' | 'info' | 'lead';
    title: string;
    body?: string;
    href?: string;
}

const toasts = reactive<Toast[]>([]);
let next = 1;

/** Small notification stack for the admin panel. */
export function useToast() {
    function push(toast: Omit<Toast, 'id'>, timeout = 5000): void {
        const id = next++;
        toasts.push({ id, ...toast });
        setTimeout(() => dismiss(id), timeout);
    }

    function dismiss(id: number): void {
        const index = toasts.findIndex((t) => t.id === id);

        if (index !== -1) {
            toasts.splice(index, 1);
        }
    }

    return { toasts, push, dismiss };
}

export function timeAgo(value: string | null | undefined): string {
    if (!value) return '';
    const seconds = Math.round((Date.now() - new Date(value).getTime()) / 1000);
    const steps: [number, Intl.RelativeTimeFormatUnit][] = [
        [60, 'second'],
        [60, 'minute'],
        [24, 'hour'],
        [7, 'day'],
        [4.35, 'week'],
        [12, 'month'],
        [Infinity, 'year'],
    ];
    let amount = seconds;

    for (const [size, unit] of steps) {
        if (Math.abs(amount) < size) {
            return new Intl.RelativeTimeFormat('en', { numeric: 'auto' }).format(-Math.round(amount), unit);
        }
        amount /= size;
    }

    return '';
}

export function formatDate(value: string | null | undefined, withTime = true): string {
    if (!value) return '';

    return new Intl.DateTimeFormat('en-GB', withTime ? { dateStyle: 'medium', timeStyle: 'short' } : { dateStyle: 'medium' }).format(new Date(value));
}

export function label(value: string | null | undefined): string {
    return (value ?? '').replaceAll('_', ' ').replace(/^\w/, (c) => c.toUpperCase());
}
