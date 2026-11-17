import { router, usePage } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted } from 'vue';
import type { Seo } from '@/types/models';

/**
 * The server prints SEO tags on the first load. During client-side
 * navigation this keeps them in step with the new page.
 */
export function useSeoSync(): void {
    const page = usePage();
    let stop: (() => void) | undefined;

    function meta(selector: string, attribute: string, value: string | null | undefined): void {
        const element = document.head.querySelector<HTMLMetaElement | HTMLLinkElement>(selector);

        if (element && value != null) {
            element.setAttribute(attribute, value);
        }
    }

    function apply(): void {
        const seo = page.props.seo as Seo | undefined;

        if (!seo) {
            return;
        }

        document.title = seo.title;
        document.documentElement.lang = seo.locale;
        document.body.dataset.page = page.component;

        meta('meta[name="description"]', 'content', seo.description ?? '');
        meta('meta[name="robots"]', 'content', seo.robots);
        meta('link[rel="canonical"]', 'href', seo.canonical);
        meta('meta[property="og:title"]', 'content', seo.title);
        meta('meta[property="og:description"]', 'content', seo.description ?? '');
        meta('meta[property="og:url"]', 'content', seo.canonical);
        meta('meta[property="og:image"]', 'content', seo.image);
        meta('meta[name="twitter:title"]', 'content', seo.title);
        meta('meta[name="twitter:description"]', 'content', seo.description ?? '');

        document.head.querySelectorAll('link[rel="alternate"][hreflang]').forEach((link) => link.remove());
        const alternates = Object.entries(seo.alternates ?? {});

        for (const [locale, href] of [...alternates, ...(seo.alternates?.en ? [['x-default', seo.alternates.en]] : [])]) {
            const link = document.createElement('link');
            link.rel = 'alternate';
            link.hreflang = locale;
            link.href = href as string;
            document.head.appendChild(link);
        }

        let script = document.head.querySelector<HTMLScriptElement>('script[type="application/ld+json"]');

        if (!script) {
            script = document.createElement('script');
            script.type = 'application/ld+json';
            document.head.appendChild(script);
        }

        script.textContent = JSON.stringify(seo.jsonLd);
    }

    onMounted(() => {
        document.body.dataset.page = page.component;
        stop = router.on('navigate', () => requestAnimationFrame(apply));
    });

    onBeforeUnmount(() => stop?.());
}
