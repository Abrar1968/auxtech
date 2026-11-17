import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import bn from '../../../lang/bn.json';
import type { Locale } from '@/types/models';

/**
 * Interface strings. English text is the key; Bangla comes from
 * lang/bn.json, the same file the server-side fallback uses.
 */
const dictionaries: Record<Locale, Record<string, string>> = { en: {}, bn };

export function translate(locale: Locale, key: string, replace: Record<string, string | number> = {}): string {
    let text = dictionaries[locale]?.[key] ?? key;

    for (const [name, value] of Object.entries(replace)) {
        text = text.replaceAll(`:${name}`, String(value));
    }

    return text;
}

/** Bangla digits for numbers shown on Bangla pages. */
export function localizeDigits(value: string | number, locale: Locale): string {
    const text = String(value);

    return locale === 'bn' ? text.replace(/\d/g, (d) => '০১২৩৪৫৬৭৮৯'[Number(d)]) : text;
}

/** Prefix an internal path for the current language: "/work" becomes "/bn/work" on Bangla pages. */
export function localePath(path: string, locale: Locale): string {
    if (locale === 'en' || /^https?:\/\//.test(path)) {
        return path;
    }

    return path === '/' ? '/bn' : `/bn${path}`;
}

export function useI18n() {
    const page = usePage();
    const locale = computed(() => (page.props.locale as Locale) ?? 'en');

    return {
        locale,
        t: (key: string, replace: Record<string, string | number> = {}) => translate(locale.value, key, replace),
        lp: (path: string) => localePath(path, locale.value),
        n: (value: string | number) => localizeDigits(value, locale.value),
        /** Singular or plural English key, chosen by count (Bangla has one form). */
        tc: (singular: string, plural: string, count: number) =>
            translate(locale.value, count === 1 ? singular : plural, { count: localizeDigits(count, locale.value) }),
    };
}
