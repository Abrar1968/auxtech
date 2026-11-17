import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { SharedProps } from '@/types/models';

/** Typed access to the props every page receives. */
export function useShared() {
    const page = usePage();

    return {
        page,
        site: computed(() => page.props.site as SharedProps['site']),
        seo: computed(() => page.props.seo as SharedProps['seo']),
        navigation: computed(() => page.props.navigation as SharedProps['navigation']),
        flash: computed(() => page.props.flash as SharedProps['flash']),
        auth: computed(() => page.props.auth as SharedProps['auth']),
    };
}

/** Builds a wa.me link from a message template (":name" is replaced). */
export function whatsappUrl(number: string, template: string, name = ''): string {
    const text = template.replaceAll(':name', name).trim();

    return `https://wa.me/${number}${text ? `?text=${encodeURIComponent(text)}` : ''}`;
}
