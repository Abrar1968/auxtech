import { describe, expect, it } from 'vitest';
import bn from '../../../lang/bn.json';
import { localePath, localizeDigits, translate } from './i18n';

describe('i18n', () => {
    it('returns English keys unchanged and Bangla from lang/bn.json', () => {
        expect(translate('en', 'Chat on WhatsApp')).toBe('Chat on WhatsApp');
        expect(translate('bn', 'Chat on WhatsApp')).toBe((bn as Record<string, string>)['Chat on WhatsApp']);
    });

    it('replaces placeholders', () => {
        expect(translate('en', ':count projects', { count: 3 })).toBe('3 projects');
    });

    it('localises digits and paths', () => {
        expect(localizeDigits(2026, 'bn')).toBe('২০২৬');
        expect(localizeDigits(2026, 'en')).toBe('2026');
        expect(localePath('/work', 'bn')).toBe('/bn/work');
        expect(localePath('/', 'bn')).toBe('/bn');
        expect(localePath('https://wa.me/1', 'bn')).toBe('https://wa.me/1');
    });
});
