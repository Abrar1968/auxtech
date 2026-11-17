import { describe, expect, it } from 'vitest';
import names from '../../data/icons.json';
import { icons } from './icons';

describe('icons', () => {
    it('can draw every icon the admin picker offers', () => {
        expect(Object.keys(icons).sort()).toEqual([...names].sort());
    });
});
