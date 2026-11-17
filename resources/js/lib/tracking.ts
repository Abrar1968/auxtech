import type { ConversionEventInput, Locale } from '@/types/models';

declare global {
    interface Window {
        dataLayer?: Record<string, unknown>[];
        fbq?: (...args: unknown[]) => void;
    }
}

/**
 * Records a CTA click or form step: server-side (admin dashboard, live feed)
 * and in the GTM dataLayer when it is loaded. sendBeacon survives the
 * visitor leaving the page for WhatsApp.
 */
export function track(type: ConversionEventInput['type'], placement: string, locale: Locale = 'en'): void {
    if (typeof window === 'undefined') {
        return;
    }

    const body: ConversionEventInput = { type, placement, page_url: window.location.href, locale };

    try {
        const blob = new Blob([JSON.stringify(body)], { type: 'application/json' });

        if (!navigator.sendBeacon?.('/api/events', blob)) {
            void fetch('/api/events', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify(body),
                keepalive: true,
            });
        }
    } catch {
        // Tracking must never break a click.
    }

    window.dataLayer?.push({ event: type, placement, page_type: document.body.dataset.page ?? '' });
}

/** Reads Laravel's XSRF cookie for JSON requests made with fetch. */
export function xsrfToken(): string {
    const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);

    return match ? decodeURIComponent(match[1]) : '';
}

export async function postJson<T>(url: string, data: unknown): Promise<{ ok: boolean; status: number; body: T }> {
    const response = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-XSRF-TOKEN': xsrfToken(),
        },
        body: JSON.stringify(data),
    });

    let body = {} as T;

    try {
        body = (await response.json()) as T;
    } catch {
        // Empty or non-JSON response.
    }

    return { ok: response.ok, status: response.status, body };
}
