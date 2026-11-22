/**
 * Headline accents written in the admin: words wrapped in *stars* are shown in
 * Fraunces italic (and in yellow in the hero). Everything else uses plain().
 */
export type AccentPart = { text: string; accent: boolean };

export function accentParts(text: string | null | undefined): AccentPart[] {
    if (!text) return [];
    return text
        .split('*')
        .map((part, i) => ({ text: part, accent: i % 2 === 1 }))
        .filter((part) => part.text !== '');
}

export function plain(text: string | null | undefined): string {
    return (text ?? '').replaceAll('*', '');
}
